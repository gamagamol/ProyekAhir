<?php

namespace App\Http\Controllers;

use App\Models\pegawaiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\QuotationModel;
use Illuminate\Support\Facades\DB;
use App\Exports\QuotationExport;
use App\Imports\QuotationImport;
use Maatwebsite\Excel\Facades\Excel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductModel;
use App\Models\CustumorModel;

class QuotationController extends Controller
{
    public $QuotationModel;
    public $pegawai;
    public $pdf;
    public $ProductModel;
    public $CustumorModel;


    public function __construct()
    {
        $this->QuotationModel = new QuotationModel();
        $this->ProductModel = new ProductModel();
        $this->CustumorModel = new CustumorModel();
        $this->pegawai = new pegawaiModel();
    }
    public function index()
    {

        if (request()->get('serch')) {
            $data = $this->QuotationModel->index($_GET['serch']);
        } else {

            $data = $this->QuotationModel->index();
        }
        // dd($data);
        $data = [
            'tittle' => "Quotation Order",
            'data' => $data,
            'deta' => $this->QuotationModel->index()
        ];
        return view('quotation.index', $data);
    }


    public function create()
    {
        //    history
        $history = request()->get('id_pelanggan');
        if ($history) {
            $history = $this->QuotationModel->history($history);
        }


        $id = $this->QuotationModel->id();
        $id_produk = $id['produk'];
        $pelanggan = $id['pelanggan'];




        $pembantu = DB::table('pembantu_penawaran')->where('id_user', (int)Auth::user()->id)->get();
        // dd($pembantu);
        // nama pelanggan
        if (count($pembantu)) {
            $id_pelanggan = $pembantu[0]->id_pelanggan;
            $nama_pelanggan = DB::table('pelanggan')->select("nama_pelanggan")->where("id_pelanggan", "=", $id_pelanggan)->first();
            $nama_pelanggan = $nama_pelanggan->nama_pelanggan;
        } else {
            $nama_pelanggan = 0;
        }

        // job number
        if (count($pembantu) > 0) {

            $nomor_pekerjaan = DB::table('pembantu_penawaran')->select("nomor_pekerjaan")->first();
            $nomor_pekerjaan->nomor_pekerjaan;
        } else {
            $nomor_pekerjaan = 0;
        }


        // nama pegawai

        if (count($pembantu)) {
            $id_pegawai = $pembantu[0]->id_pegawai;
            $nama_pegawai = DB::table('pegawai')->select("nama_pegawai")->where("id_pegawai", "=", $id_pegawai)->first();
            $nama_pegawai = $nama_pegawai->nama_pegawai;
        } else {
            $nama_pegawai = 0;
        }





        $data = [
            'tittle' => 'ADD Quotation',
            'produk' => $id_produk,
            'pelanggan' => $pelanggan,
            'pembantu' => $pembantu,
            'nama_pelanggan' => $nama_pelanggan,
            'nama_pegawai' => $nama_pegawai,
            'nomor_pekerjaan' => $nomor_pekerjaan,
            'history' => $history,
            'kode_transaksi' => $this->QuotationModel->TransactionCode(),
            'services' => DB::table('layanan')->get(),
            'pegawai' => $this->pegawai->getEmployee('SALES'),

        ];
        return view('quotation.insert', $data);
    }

    public function store(Request $request)
    {



        $rules = [
            'kode_transaksi' => "required|unique:transaksi,kode_transaksi",
            'nomor_pekerjaan' => 'required',
            'id_pelanggan' => "required",
            'id_produk' => "required",
            'id_pelanggan' => "required",
            'tebal_transaksi' => "required|regex:/^\d*(\.\d{2})?$/",
            'lebar_transaksi' => "required|regex:/^\d*(\.\d{2})?$/",
            'panjang_transaksi' => "required|regex:/^\d*(\.\d{2})?$/",
            'jumlah' => "required",
            'harga' => "required",
            'tgl_penawaran' => "required",
            'id_pegawai' => "required",
            'nomor_transaksi' => "required",

        ];
        $message = [
            'kode_transaksi.required' => "The TRANSACTION CODE field is required",
            'kode_transaksi.unique' => "The TRANSACTION CODE has already taken",
            'nomor_pekerjaan.required' => "The Job number field is required",
            'tebal_transaksi.required' => "The THICK INQUIRY field is required",
            'lebar_transaksi.required' => "The WIDTH INQUIRY field is required",
            'panjang_transaksi.required' => "The LENGTH INQUIRY field is required",
            'jumlah.required' => "The QTY field is required",
            'harga.required' => "The PRICE field is required",
            'id_produk.required' => "The PRODUCT field is required!",
            'id_pelanggan.required' => "The CUSTUMOR field is required!",
            'tgl_penawaran.required' => "The Date field is required!",
            'id_pegawai.required' => "The Employee field is required!",
            'nomor_transaksi.required' => "The Transaction Number field is required!",


        ];


        $type = $request->input("type");
        $type = ($type == null) ? 1 : $type;
        if ($type == 1) {
            $rules["ongkir"] = "required";
            $message["ongkir.required"] = "The SHIPPMENT field is required";
        } else {
            $rules["berat"] = "required";
            $message["berat.required"] = "The Weight field is required";
        }

        $validated = Validator::make($request->all(), $rules, $message);

        // dd($validated->errors());

        if ($validated->fails()) {
            return redirect('quotation/create')->withErrors($validated)->withInput();
        } else {



            $produk = $request->input("id_produk");
            $produk = explode("|", $request->input('id_produk'));
            $nama_produk = $produk[0];

            $p = DB::table('produk')->where("id_produk", "=", $nama_produk)->first();
            $bentuk_produk = $p->bentuk_produk;
            $tebal_transaksi = $request->input("tebal_transaksi");
            $lebar_transaksi = $request->input("lebar_transaksi");
            $panjang_transaksi = $request->input("panjang_transaksi");
            $jumlah = $request->input("jumlah");
            $layanan = $this->QuotationModel->getServices($request->input("layanan"));
            $type_layanan = $layanan->type;
            $harga = (int) str_replace('.', "", $request->input('harga'));


            $type = $request->input("type");
            $type = ($type == null) ? 1 : $type;

            if ((int)$lebar_transaksi <= 0 && $bentuk_produk == 'FLAT') {
                return redirect()->back()->withErrors(['lebar_transaksi' => 'Fill Width more then 0'])->withInput();
            }

            // Logika penentuan berat
            // deklarasi
            // dd($layanan);

            if ($type == 1) {

                $berat = $this->CalculateWeight($bentuk_produk, $type_layanan, $tebal_transaksi, $lebar_transaksi, $panjang_transaksi, $jumlah);
            } else {
                $berat = $request->berat;
            }

            // dd($berat);
            $subtotal = (float) $berat * (int) str_replace('.', "", $request->input('harga'));

            // dd($subtotal);


            // nama layanan
            // dd($layanan->nama_layanan);
            if (str_contains($layanan->nama_layanan, "_")) {
                // dd("sini");
                $nama_layanan = strtoupper(str_replace("_", "+", $layanan->nama_layanan));
            } else {
                $nama_layanan = $layanan->nama_layanan;
            }

            // dd($nama_layanan);

            $ppn = $subtotal * 0.11;
            $ppn12 = $harga * 0.02;

            if ($type == 1) {
                $total = $subtotal + $ppn;
            } else {
                $total = ($subtotal + $ppn) - $ppn12;
            }

            // dump($subtotal);
            // dump($ppn);
            // dump($ppn12);
            // dump($total);
            // dd($total);


            $data = [
                'kode_transaksi' => strtoupper($request->input("kode_transaksi")),
                'tgl_pembantu' => $request->input("tgl_penawaran"),
                'nomor_pekerjaan' => $request->input("nomor_pekerjaan"),
                'id_pelanggan' => $request->input("id_pelanggan"),
                'id_pegawai' => $request->input("id_pegawai"),
                'nama_produk' => $p->nama_produk,
                'tebal_pembantu' => $request->input("tebal_transaksi"),
                'lebar_pembantu' => $request->input("lebar_transaksi"),
                'panjang_pembantu' => $request->input("panjang_transaksi"),
                'jumlah_pembantu' => $request->input("jumlah"),
                'layanan_pembantu' => $nama_layanan,
                'harga_pembantu' => str_replace('.', "", $request->input('harga')),
                'ongkir_pembantu' => str_replace('.', "", ($request->input('ongkir') == "") ? 0 : $request->input('ongkir')),
                'id_user' => $request->input("id"),
                'tebal_penawaran' => ($type_layanan == 'MILLING' || $type_layanan == 'NF_MILLING') ? $tebal_transaksi + 5 : $tebal_transaksi,
                'lebar_penawaran' => ($type_layanan == 'MILLING' && $bentuk_produk == 'FLAT' || $type_layanan == 'NF_MILLING' && $bentuk_produk == 'FLAT') ? $lebar_transaksi + 5 : $lebar_transaksi,
                'panjang_penawaran' => ($type_layanan == 'MILLING' || $type_layanan == 'NF_MILLING') ? $panjang_transaksi + 5 : $panjang_transaksi,
                'berat_pembantu' => (float)$berat,
                'bentuk_pembantu' => $bentuk_produk,
                'subtotal' => $subtotal,
                'ppn' => $ppn,
                'total' => $total,
                'nomor_transaksi' => $request->input("nomor_transaksi"),
                "id_layanan" => $request->input("layanan"),
                "type" => $type

            ];

            // dd($data);



            $this->QuotationModel->insert_pembantu($data);



            return redirect('quotation/create')->withInput();
        }
    }

    public function insert(Request $request)
    {
        if (count($request->all()) == 2) {
            return redirect("quotation/create")->with('failed', 'Please Cheked Your Quotation!');
        } else {


            $array = $request->all();
            // dd($array);

            unset($array["submit"]);
            unset($array["_token"]);
            $data_transaksi = [];
            $data_penawaran = [];
            $data_detail_penawaran = [];
            for ($i = 1; $i <= count($array); $i++) {
                ${"array$i"} = explode("|", $array["elemen$i"]);

                ${"elemen_transaksi$i"} = [
                    'id' => ${"array$i"}[21],
                    'kode_transaksi' => ${"array$i"}[0],
                    'layanan' => ${"array$i"}[19],
                    'panjang_transaksi' => (float)${"array$i"}[6],
                    'lebar_transaksi' => (float)${"array$i"}[5],
                    'tebal_transaksi' => (float)${"array$i"}[4],
                    'berat' => (float) ${"array$i"}[13],
                    'harga' => (float) ${"array$i"}[14],
                    'jumlah' => (int) ${"array$i"}[7],
                    'subtotal' => (float)${"array$i"}[16],
                    'total' => (float) ${"array$i"}[18],
                    'status_transaksi' => "quotation",
                    'id_pelanggan' => ${"array$i"}[20],
                    'nomor_pekerjaan' => ${"array$i"}[2],
                    'ppn' => (float)${"array$i"}[17],
                    'ongkir' => (float) ${"array$i"}[15],
                    'id_pegawai' => (int) ${"array$i"}[22],
                    'nomor_transaksi' => ${"array$i"}[23],
                    'id_layanan' => ${"array$i"}[24],
                    "type" => (int)${"array$i"}[25]
                ];


                $data_transaksi[] = ${"elemen_transaksi$i"};

                ${"elemen_penawaran$i"} = [
                    "id_transaksi" => 0,
                    "no_penawaran" => 0,
                    "tgl_penawaran" => ${"array$i"}[1],
                    "tebal_penawaran" => (float)${"array$i"}[9],
                    "lebar_penawaran" => (float)${"array$i"}[10],
                    "panjang_penawaran" => (float) ${"array$i"}[11],

                ];
                $data_penawaran[] = ${"elemen_penawaran$i"};

                ${"id_poroduk_detail$i"} = DB::table('produk')->select("id_produk")->where("nama_produk", "=", ${"array$i"}[3])->first();
                ${"id_poroduk_detail$i"} = ${"id_poroduk_detail$i"}->id_produk;

                ${"elemen_detail_penawaran$i"} = [
                    "id_penawaran" => 0,
                    "id_produk" =>  ${"id_poroduk_detail$i"},


                ];
                $data_detail_penawaran[] = ${"elemen_detail_penawaran$i"};
            }

            $tgl_penawaran = $request->input('elemen1');
            $tgl_penawaran = explode("|", $tgl_penawaran);
            $tgl_penawaran = $tgl_penawaran[1];
            // dd($data_transaksi);
            // dd($request->all());
            $no_quotation = $this->QuotationModel->insert($data_transaksi, $data_penawaran, $data_detail_penawaran, $tgl_penawaran);

            return redirect("quotation")->with("success", "Data Entered Successfully, You Quotation number $no_quotation");
        }
    }


    public function show_data()
    {
        $quotation_data = $this->QuotationModel->show_data();
        // dd($quotation_data);
        $data = [
            'tittle' => "Show Data Quotation Success",
            'data' => $quotation_data
        ];
        return view('quotation.show_data', $data);
    }

    public function show($kode_transaksi)
    {
        // echo 'test';die;

        $quotation_data = $this->QuotationModel->show($kode_transaksi);
        // dd($quotation_data);

        $data = [
            'tittle' => "Show Data Quotation Success",
            'data' => $quotation_data
        ];
        return view('quotation.show_data', $data);
    }

    public function delete($id)
    {
        DB::table("pembantu_penawaran")->where('id_pembantu', "=", $id)->delete();
        return redirect("quotation/create");
    }




    public function print($no_transaksi)
    {
        $data = $this->QuotationModel->print($no_transaksi);

        // goods

        $goods = (count($data["goods"]) > 0) ? $data["goods"] : null;
        $service = (count($data["service"]) > 0) ? $data["service"] : null;



        if ($goods != null) {

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('template_report/qtn_template.xlsx');
            $worksheet = $spreadsheet->getActiveSheet();
            $worksheet->getCell('O4')->setValue($goods[0]->tgl_penawaran);
            $worksheet->getCell('O5')->setValue($goods[0]->no_penawaran);
            $worksheet->getCell('O6')->setValue($goods[0]->nama_pelanggan);
            $worksheet->getCell('O7')->setValue($goods[0]->nomor_transaksi);
            $worksheet->getCell('Q11')->setValue(date('Y-m-d', strtotime($goods[0]->tgl_penawaran . ' + 3 days')));
            $worksheet->getCell('Q12')->setValue($goods[0]->nama_pegawai);
            $worksheet->getCell('E16')->setValue($goods[0]->layanan);

            // alamat
            $worksheet->getCell('A12')->setValue($goods[0]->perwakilan);
            $worksheet->getCell('A13')->setValue($goods[0]->nama_pelanggan);
            $worksheet->getCell('A14')->setValue($goods[0]->alamat_pelanggan);
            $baris_awal = 19;
            $subtotal = 0;
            $total = 0;
            $ongkir = 0;
            $worksheet->insertNewRowBefore(20, count($goods));
            for ($i = 0; $i < count($goods); $i++) {

                if ($goods[$i]->type == 1) {


                    $tambahan_baris = $baris_awal + 1;
                    $worksheet->setCellValue("A$tambahan_baris", ($i + 1));
                    $worksheet->setCellValue("B$tambahan_baris", $goods[$i]->nomor_pekerjaan);
                    $worksheet->MergeCells("B$tambahan_baris:C$tambahan_baris");
                    $worksheet->setCellValue("D$tambahan_baris", $goods[$i]->nama_produk);
                    $worksheet->setCellValue("E$tambahan_baris", $goods[$i]->tebal_transaksi);
                    $worksheet->setCellValue("F$tambahan_baris", $goods[$i]->lebar_transaksi);
                    $worksheet->setCellValue("G$tambahan_baris", $goods[$i]->panjang_transaksi);
                    $worksheet->setCellValue("H$tambahan_baris", $goods[$i]->jumlah);
                    $worksheet->setCellValue("I$tambahan_baris", $goods[$i]->nama_produk);
                    $worksheet->setCellValue("J$tambahan_baris", $goods[$i]->tebal_penawaran);
                    $worksheet->setCellValue("K$tambahan_baris", $goods[$i]->lebar_penawaran);
                    $worksheet->setCellValue("L$tambahan_baris", $goods[$i]->panjang_penawaran);
                    $worksheet->setCellValue("M$tambahan_baris", $goods[$i]->jumlah);
                    $worksheet->setCellValue("N$tambahan_baris", $goods[$i]->berat);

                    // $worksheet->setCellValue("O$tambahan_baris", number_format($goods[$i]->harga));
                    // $worksheet->setCellValue("P$tambahan_baris", number_format($goods[$i]->subtotal));


                    // $worksheet->MergeCells("P$tambahan_baris:Q$tambahan_baris");

                    $cell = $worksheet->getCell("O$tambahan_baris");

                    // Set nilai sel
                    $cell->setValue($goods[$i]->harga);

                    // Ambil objek gaya dari sel
                    $style = $cell->getStyle();

                    // Set format mata uang pada sel
                    $style->getNumberFormat()->setFormatCode('#,##0');

                    $worksheet->MergeCells("P$tambahan_baris:Q$tambahan_baris");
                    $cell1 = $worksheet->getCell("P$tambahan_baris");
                    $cell1->setValue($goods[$i]->subtotal);

                    $style1 = $cell1->getStyle();

                    $style1->getNumberFormat()->setFormatCode('#,##0');






                    $subtotal += $goods[$i]->subtotal;
                    $ongkir += $goods[$i]->ongkir;
                    $total += $goods[$i]->total;
                    $baris_awal = $tambahan_baris;
                }
            }
            $baris_setelah = $baris_awal + 2;
            $worksheet->setCellValue("P$baris_setelah", $subtotal);
            $worksheet->MergeCells("P$baris_setelah:Q$baris_setelah");

            $baris_setelah += 1;
            $worksheet->setCellValue("P$baris_setelah", $subtotal * 0.11);
            $worksheet->MergeCells("P$baris_setelah:Q$baris_setelah");

            $baris_setelah += 1;
            $worksheet->setCellValue("P$baris_setelah", $total);
            $worksheet->MergeCells("P$baris_setelah:Q$baris_setelah");

            $baris_setelah += 6;
            $worksheet->setCellValue("D$baris_setelah", "ASD");
            // $worksheet->MergeCells("C$baris_setelah:D$baris_setelah");

            $baris_setelah += 2;
            $worksheet->setCellValue("E$baris_setelah", $goods[0]->nama_pelanggan);
            $worksheet->MergeCells("E$baris_setelah:I$baris_setelah");
        }



        if ($service != null) {
            // ini untuk qtn dengan type 2 (service) bukan file copy!
            $spreadsheet1 = \PhpOffice\PhpSpreadsheet\IOFactory::load('template_report/qtn_template_2.xlsx');
            $worksheet1 = $spreadsheet1->getActiveSheet();
            $worksheet1->getCell('O4')->setValue($service[0]->tgl_penawaran);
            $worksheet1->getCell('O5')->setValue($service[0]->no_penawaran);
            $worksheet1->getCell('O6')->setValue($service[0]->nama_pelanggan);
            $worksheet1->getCell('O7')->setValue($service[0]->nomor_transaksi);
            $worksheet1->getCell('Q11')->setValue(date('Y-m-d', strtotime($service[0]->tgl_penawaran . ' + 3 days')));
            $worksheet1->getCell('Q12')->setValue($service[0]->nama_pegawai);
            $worksheet1->getCell('E16')->setValue($service[0]->layanan);

            // alamat
            $worksheet1->getCell('A12')->setValue($service[0]->perwakilan);
            $worksheet1->getCell('A13')->setValue($service[0]->nama_pelanggan);
            $worksheet1->getCell('A14')->setValue($service[0]->alamat_pelanggan);
            $baris_awal = 19;
            $subtotal = 0;
            $total = 0;
            $ongkir = 0;
            $harga = 0;
            $worksheet1->insertNewRowBefore(20, count($service));
            for ($i = 0; $i < count($service); $i++) {
                if ($service[$i]->type == 2) {


                    $tambahan_baris = $baris_awal + 1;

                    $worksheet1->setCellValue("A$tambahan_baris", ($i + 1));
                    $worksheet1->setCellValue("B$tambahan_baris", $service[$i]->nomor_pekerjaan);
                    $worksheet1->MergeCells("B$tambahan_baris:C$tambahan_baris");

                    $worksheet1->setCellValue("D$tambahan_baris", $service[$i]->nama_produk);
                    $worksheet1->setCellValue("E$tambahan_baris", $service[$i]->tebal_transaksi);
                    $worksheet1->setCellValue("F$tambahan_baris", $service[$i]->lebar_transaksi);
                    $worksheet1->setCellValue("G$tambahan_baris", $service[$i]->panjang_transaksi);
                    $worksheet1->setCellValue("H$tambahan_baris", $service[$i]->jumlah);
                    $worksheet1->setCellValue("I$tambahan_baris", $service[$i]->nama_produk);
                    $worksheet1->setCellValue("J$tambahan_baris", $service[$i]->tebal_penawaran);
                    $worksheet1->setCellValue("K$tambahan_baris", $service[$i]->lebar_penawaran);
                    $worksheet1->setCellValue("L$tambahan_baris", $service[$i]->panjang_penawaran);
                    $worksheet1->setCellValue("M$tambahan_baris", $service[$i]->jumlah);
                    $worksheet1->setCellValue("N$tambahan_baris", $service[$i]->berat);

                    // $worksheet1->setCellValue("O$tambahan_baris", number_format($service[$i]->harga, 2, ',', '.'));

                    $cell = $worksheet1->getCell("O$tambahan_baris");

                    // Set nilai sel
                    $cell->setValue($service[$i]->harga);

                    // Ambil objek gaya dari sel
                    $style = $cell->getStyle();

                    // Set format mata uang pada sel
                    $style->getNumberFormat()->setFormatCode('#,##0');
                    // $worksheet1->setCellValue("P$tambahan_baris", number_format($service[$i]->subtotal, 2, ',', '.'));
                    $worksheet1->MergeCells("P$tambahan_baris:Q$tambahan_baris");
                    $cell1 = $worksheet1->getCell("P$tambahan_baris");
                    $cell1->setValue($service[$i]->subtotal);

                    $style1 = $cell1->getStyle();

                    $style1->getNumberFormat()->setFormatCode('#,##0');


                    $subtotal += $service[$i]->subtotal;
                    $ongkir += $service[$i]->ongkir;
                    $harga += $service[$i]->harga;
                    $total += $service[$i]->total;
                    $baris_awal = $tambahan_baris;
                }
            }
            $baris_setelah = $baris_awal + 2;
            $worksheet1->setCellValue("P$baris_setelah", $subtotal);
            $worksheet1->MergeCells("P$baris_setelah:Q$baris_setelah");

            // ppn 11%
            $baris_setelah += 1;
            $worksheet1->setCellValue("P$baris_setelah", $subtotal * 0.11);
            $worksheet1->MergeCells("P$baris_setelah:Q$baris_setelah");

            // ppn 2%
            $baris_setelah += 1;
            $worksheet1->setCellValue("P$baris_setelah", $harga * 0.02);
            $worksheet1->MergeCells("P$baris_setelah:Q$baris_setelah");

            $baris_setelah += 1;
            $worksheet1->setCellValue("P$baris_setelah", $total);
            $worksheet1->MergeCells("P$baris_setelah:Q$baris_setelah");

            $baris_setelah += 6;
            $worksheet1->setCellValue("D$baris_setelah", "ASD");
            // $worksheet1->MergeCells("C$baris_setelah:D$baris_setelah");

            $baris_setelah += 2;
            $worksheet1->setCellValue("E$baris_setelah", $service[0]->nama_pelanggan);
            $worksheet1->MergeCells("E$baris_setelah:I$baris_setelah");
        }





        $namaFile = str_replace("/", "_", $data["no_penawaran"]);


        if ($goods != null && $service != null) {
            $this->printAll($spreadsheet, $spreadsheet1, $namaFile);
        } else if ($goods != null) {
            $this->printAll($spreadsheet, null, $namaFile);
        } else if ($service != null) {
            $this->printAll(null, $spreadsheet1, $namaFile);
        }
    }


    public function printAll($spreadsheet, $spreadsheet1, $namaFile)
    {
        if ($spreadsheet != null && $spreadsheet1 != null) {

            $zipFile = $namaFile . ".zip";

            $zip = new \ZipArchive();
            if ($zip->open($zipFile, \ZipArchive::CREATE) === true) {
                if ($spreadsheet != null) {


                    $tempFileGoods = public_path('assets/temp/') . "Goods-" . date("Y-m-d") . '.xlsx';
                    $writerGoods = new Xlsx($spreadsheet);
                    $writerGoods->save($tempFileGoods);

                    // Add the stream content to the zip file
                    $zip->addFromString(basename($tempFileGoods), file_get_contents($tempFileGoods));

                    // Optionally, delete the temporary file
                    unlink($tempFileGoods);
                }

                if ($spreadsheet1 != null) {

                    $tempFileService = public_path('assets/temp/') .  "Service-" . date("Y-m-d") . '.xlsx';
                    $writerService = new Xlsx($spreadsheet1);
                    $writerService->save($tempFileService);

                    // Add the stream content to the zip file
                    $zip->addFromString(basename($tempFileService), file_get_contents($tempFileService));

                    // Optionally, delete the temporary file
                    unlink($tempFileService);
                }
                // dd("");
                $zip->close();

                // Set headers for the zip file
                header('Content-Type: application/zip');
                header("Content-Disposition: attachment; filename=$zipFile");
                header('Content-Length: ' . filesize($zipFile));

                // Read and output the zip file
                readfile($zipFile);

                // Optionally, delete the temporary zip file
                unlink($zipFile);
            }
        } else if ($spreadsheet != null) {

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment; filename=$namaFile.xlsx"); // Set nama file excel nya
            header('Cache-Control: max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        } else if ($spreadsheet1 != null) {
            // dd("masuk sini");
            $writer1 = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet1, 'Xls');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment; filename=$namaFile.xlsx"); // Set nama file excel nya
            header('Cache-Control: max-age=0');

            $writer1 = new Xlsx($spreadsheet1);
            $writer1->save('php://output');
        }
    }





    public function addSpreadsheetToZip($zip, $spreadsheet, $filename)
    {
        $tempFile = public_path('assets/temp/') . uniqid($filename) . '.xlsx';
        dd($tempFile);

        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        // Add the stream content to the zip file
        $zip->addFromString($filename, file_get_contents($tempFile));

        // Close the temporary stream
        fclose(fopen($tempFile, 'r'));

        // Optionally, delete the temporary file
        unlink($tempFile);
    }


    public function CalculateWeight($bentuk_produk, $layanan, $tebal_transaksi, $lebar_transaksi, $panjang_transaksi, $jumlah)
    {

        // dd($layanan);

        switch ($bentuk_produk) {
            case "FLAT":
                if ($layanan == "CUTTING") {
                    $tebal_penawaran = $tebal_transaksi;
                    $lebar_penawaran = $lebar_transaksi;
                    $panjang_penawaran = $panjang_transaksi;

                    $berat = $tebal_penawaran * $lebar_penawaran * $panjang_penawaran * 0.000008;
                    // $berat = number_format($berat, 2, '.', '');
                    // return ($berat > 0) ? round($berat) : $berat;
                    $berat = ((float)number_format((float)$berat, 1, '.', '') < 1.0) ? 1.0 : $berat;
                    $berat *= $jumlah;
                    return number_format($berat, 1, ".", "");
                }

                if ($layanan == "NF") {
                    $tebal_penawaran = $tebal_transaksi;
                    $lebar_penawaran = $lebar_transaksi;
                    $panjang_penawaran =  $panjang_transaksi;

                    $berat = $tebal_penawaran * $lebar_penawaran * $panjang_penawaran  * 0.00000785;
                    // $berat = number_format($berat, 2, '.', '');
                    // return ($berat > 0) ? round($berat) : $berat;
                    $berat = ((float)number_format((float)$berat, 1, '.', '') < 1.0) ? 1.0 : $berat;
                    $berat *= $jumlah;

                    return number_format($berat, 1, ".", "");
                }

                if ($layanan == "MILLING") {
                    // //    membuat ukuran dan berat pxl 0,00008
                    // dump($tebal_transaksi);
                    // dump($lebar_transaksi);
                    // dump($panjang_transaksi);
                    $tebal_penawaran = $tebal_transaksi + 5;
                    $lebar_penawaran =  $lebar_transaksi + 5;
                    $panjang_penawaran =  $panjang_transaksi + 5;

                    $berat = $tebal_penawaran * $lebar_penawaran * $panjang_penawaran  * 0.000008;
                    // $berat = number_format($berat, 2, '.', '');
                    // return ($berat > 0) ? round($berat) : $berat;
                    $berat = ((float)number_format((float)$berat, 1, '.', '') < 1.0) ? 1.0 : $berat;
                    $berat *= $jumlah;

                    return number_format($berat, 1, ".", "");
                }

                if ($layanan == "NF_MILLING" || $layanan == "NF MILLING") {
                    //    membuat ukuran dan berat pxl 0,00008
                    $tebal_penawaran = $tebal_transaksi + 5;
                    $lebar_penawaran =  $lebar_transaksi + 5;
                    $panjang_penawaran =  $panjang_transaksi + 5;

                    $berat = $tebal_penawaran * $lebar_penawaran * $panjang_penawaran * 0.00000785;
                    // $berat = number_format($berat, 2, '.', '');
                    // return ($berat > 0) ? round($berat) : $berat;
                    $berat = ((float)number_format((float)$berat, 1, '.', '') < 1.0) ? 1.0 : $berat;
                    $berat *= $jumlah;

                    return number_format($berat, 1, ".", "");
                }




                break;
            case 'CYLINDER':
                if ($layanan == "CUTTING") {
                    //    membuat ukuran dan berat pxl 0,0000625
                    $tebal_penawaran = $tebal_transaksi;
                    $lebar_penawaran = 0;
                    $panjang_penawaran =  $panjang_transaksi;

                    $berat = $tebal_penawaran * $tebal_penawaran * $panjang_penawaran  * 0.00000625;
                    // $berat = number_format($berat, 2, '.', '');
                    // return ($berat > 0) ? round($berat) : $berat;
                    $berat = ((float)number_format((float)$berat, 1, '.', '') < 1.0) ? 1.0 : $berat;
                    $berat *= $jumlah;

                    return number_format($berat, 1, ".", "");
                }
                if ($layanan == "NF") {
                    $tebal_penawaran = $tebal_transaksi;
                    $lebar_penawaran = 0;
                    $panjang_penawaran = $panjang_transaksi;

                    $berat = $tebal_penawaran * $tebal_penawaran * $panjang_penawaran  * 0.00000785;
                    $berat *= $jumlah;

                    // $berat = number_format($berat, 2, '.', '');
                    // return ($berat > 0) ? round($berat) : $berat;
                    $berat = ((float)number_format((float)$berat, 1, '.', '') < 1.0) ? 1.0 : $berat;

                    return number_format($berat, 1, ".", "");
                }
                if ($layanan == "MILLING") {
                    //    membuat ukuran dan berat pxl 0,00008
                    $tebal_penawaran = $tebal_transaksi + 5;
                    $lebar_penawaran = 0;
                    $panjang_penawaran =  $panjang_transaksi + 5;

                    $berat = $tebal_penawaran * $tebal_penawaran * $panjang_penawaran  * 0.00000625;
                    $berat *= $jumlah;

                    $berat = ((float)number_format((float)$berat, 1, '.', '') < 1.0) ? 1.0 : $berat;
                    return number_format($berat, 1, ".", "");
                }
                if ($layanan == "NF_MILLING" || $layanan == "NF MILLING") {
                    //    membuat ukuran dan berat pxl 0,00008
                    $tebal_penawaran = $tebal_transaksi + 5;
                    $lebar_penawaran = 0;
                    $panjang_penawaran =  $panjang_transaksi + 5;

                    $berat = $tebal_penawaran * $tebal_penawaran * $panjang_penawaran  * 0.00000785;
                    $berat *= $jumlah;

                    $berat = ((float)number_format((float)$berat, 1, '.', '') < 1.0) ? 1.0 : $berat;
                    return number_format($berat, 1, ".", "");
                }
                break;
        }
    }



    public function quotationReportDetail()
    {
        $data = [
            'tittle' => 'Quotation Report Detail',
        ];

        return view('quotation.report_detail', $data);
    }

    public function quotationReportDetailAjax()
    {
        $data = $this->QuotationModel->quotationDetailReport(request()->input('month'), request()->input('date'), request()->input('date_to'));

        return response()->json($data);
    }

    public function getDateQuotationAjax()
    {
        return response()->json($this->QuotationModel->getDateQuotation());
    }

    public function exportDetailReport($year_month = null, $date = null, $date_to = null)
    {

        $tgl = '';

        if ($year_month != 0) {
            $tgl = explode('-', $year_month)[1];
        } else if ($date != 0) {
            $tgl = $date;
        } else {

            $tgl = date('Y-m-d');
        }



        return Excel::download(new QuotationExport($year_month, $date, 'detail', $date_to), "Quotation Detail Report_$tgl.xlsx");
    }

    public function customerOmzetReport()
    {
        $data = [
            'tittle' => 'Report Customer Omzet'
        ];

        return view('quotation.customer_omzet_report', $data);
    }


    public function customerOmzetReportAjax()
    {
        // $data = '';

        // if (request()->input('month') && !request()->input('date')) {
        //     $month = explode('-', request()->input('month'))[1];

        //     $data = $this->QuotationModel->customerOmzetReport($month);
        // } elseif (request()->input('date') && !request()->input('month')) {
        //     $date = explode('-', request()->input('date'))[2];

        //     $data = $this->QuotationModel->customerOmzetReport(null, $date);
        // } elseif (request()->input('date') && request()->input('month')) {
        //     $month = explode('-', request()->input('month'))[1];
        //     $date = explode('-', request()->input('date'))[2];

        //     $data = $this->QuotationModel->customerOmzetReport($month, $date);
        // } else {
        //     $data = $this->QuotationModel->customerOmzetReport();
        // }
        // echo $year_month;die;


        return response()->json([
            'message' => 'success',
            'data' => $this->QuotationModel->customerOmzetReport(request()->input('month'), request()->input('date'), request()->input('date_to'))

        ]);
    }

    public function customerOmzetReportExport($year_month = null, $date = null, $date_to = null)
    {
        $tgl_generate = date('Y-m-d');

        return Excel::download(new QuotationExport($year_month, $date, 'customer_omzet', $date_to), "Customer Omzet Report_{$tgl_generate} .xlsx");
    }

    public function outStandingReport()
    {
        $data = [
            'tittle' => 'Out Standing Report'
        ];

        return view('quotation.out_standing_report', $data);
    }

    public function outStandingReportAjax()
    {


        // $data = '';

        // if (request()->input('month') && !request()->input('date')) {
        //     $month = explode('-', request()->input('month'))[1];

        //     $data = $this->QuotationModel->outStandingReport($month);
        // } elseif (request()->input('date') && !request()->input('month')) {
        //     $date = explode('-', request()->input('date'))[2];

        //     $data = $this->QuotationModel->outStandingReport(null, $date);
        // } elseif (request()->input('date') && request()->input('month')) {
        //     $month = explode('-', request()->input('month'))[1];
        //     $date = explode('-', request()->input('date'))[2];

        //     $data = $this->QuotationModel->outStandingReport($month, $date);
        // } else {
        //     $data = $this->QuotationModel->outStandingReport();
        // }



        return response()->json([
            'message' => 'success',
            'data' => $this->QuotationModel->outStandingReport(request()->input('month'), request()->input('date'), request()->input('date_to'))

        ]);
    }


    public function outStandingReportExport($year_month = null, $date = null, $date_to = null)
    {
        $tgl = date('Y-m-d');

        return Excel::download(new QuotationExport($year_month, $date, 'out_standing', $date_to), "Out standing Report_$tgl .xlsx");
    }


    public function quotationReport()
    {

        $data = [
            'tittle' => "Quotation Vs PO Report"
        ];
        return view('quotation.quotation_report', $data);
    }
    public function quotationReportAjax()
    {

        // $data = '';




        // if (request()->input('month') && !request()->input('date')) {
        //     $month = explode('-', request()->input('month'));


        //     $data = $this->QuotationModel->quotationReport($month[0], $month[1]);
        // } elseif (request()->input('date') && !request()->input('month')) {
        //     $date = request()->input('date');

        //     $data = $this->QuotationModel->quotationReport(null, null, $date);
        // } elseif (request()->input('date') && request()->input('month') && !request()->input('date_to')) {

        //     $month = explode('-', request()->input('month'));
        //     $date = request()->input('date');


        //     $data = $this->QuotationModel->quotationReport($month[0], $month[1], $date);
        // } else {
        //     $data = $this->QuotationModel->quotationReport();
        // }


        return response()->json([
            'message' => 'success',
            'data' => $this->QuotationModel->quotationReport(request()->input('month'), request()->input('date'), request()->input('date_to'))

        ]);
    }

    public function quotationReportExport($month = null, $date = null, $date_to = null)
    {

        if ($month == '0') {
            $month = 0;
        }
        if ($date == '0') {
            $date = 0;
        }
        if ($date_to == '0') {
            $date_to = 0;
        }


        // echo gettype($month);
        // echo "<br>";        
        // echo gettype($date);        
        // echo "<br>";        
        // echo gettype($date_to); 
        // die;       
        return Excel::download(new QuotationExport($month, $date, 'quotation', $date_to), 'Quotation VS PO Report.xlsx');
    }




    public function editPembantuPenawaran(Request $request)
    {
        $layanan = $this->QuotationModel->getServices($request->layanan_edit_penawaran);
        $type_layanan = $layanan->type;

        $harga_penawaran = $request->harga_edit_penawaran;
        $berat = $this->CalculateWeight(
            $request->bentuk_edit_penawaran,
            $type_layanan,
            $request->tebal_edit_penawaran,
            $request->lebar_edit_penawaran,
            $request->panjang_edit_penawaran,
            $request->jumlah_edit_penawaran,
        );


        // dump($berat);
        $subtotal = (float) $berat * (int) str_replace('.', "", $harga_penawaran);

        // dd($subtotal);
        $ppn = $subtotal * 0.11;
        $total = $subtotal + $ppn;

        // dump($subtotal);
        // dump($be);
        // dump($ppn);
        // dd($total);

        DB::table('pembantu_penawaran')->where('id_pembantu', $request->id_edit_penawaran)->update([
            'tebal_penawaran' => $request->tebal_edit_penawaran,
            'lebar_penawaran' => $request->lebar_edit_penawaran,
            'panjang_penawaran' => $request->panjang_edit_penawaran,
            'berat_pembantu' => $berat,
            'subtotal' => $subtotal,
            'ppn' => $ppn,
            'total' => $total
        ]);

        return back()->with("success", "Data Quotation Has been successfully Updated ");
    }


    public function import_quotation(Request $request)
    {
        $file = $request->file('quotation-import');

        $import = new QuotationImport;
        $data = Excel::toArray($import, $file);


        if (count($data[0]) == 3) {
            return response()->json(["message" => "fail", "errors" => ["Please Insert New Row and Fill The Cloumn"]]);
        }



        $kode_transaksi = $this->QuotationModel->TransactionCode();
        $errors = [];
        $error = [];

        // dd($data);
        $array_data_insert_pembantu = [];
        for ($i = 0; $i < count($data[0]); $i++) {
            // dump($i);
            if ($i > 2) {

                // dd("sini");
                // validate
                $error =  $this->import_validation($data[0][$i], $i);

                foreach ($error as $err) {

                    array_push($errors, $err);
                }


                if (count($error) == 0) {
                    $nomor_pekerjaan = $data[0][$i][0];
                    $id_pegawai = $this->pegawai->getEmployeeByCode(strtoupper($data[0][$i][1]))->id_pegawai;
                    $id_pelanggan = strtoupper($data[0][$i][2]);
                    $produk = $this->ProductModel->getProductByCode(strtoupper($data[0][$i][3]));
                    $bentuk_produk = $produk->bentuk_produk;
                    $tebal_transaksi = (int)$data[0][$i][4];
                    $lebar_transaksi = (int)$data[0][$i][5];
                    $panjang_transaksi = (int)$data[0][$i][6];
                    $jumlah = $data[0][$i][7];
                    $layanan = $data[0][$i][8];
                    $berat = $data[0][$i][11];


                    if (str_contains($layanan, "_")) {
                        $layanan = strtoupper(str_replace("_", " ", $layanan));
                    } else if (str_contains($layanan, "+")) {
                        $layanan = strtoupper(str_replace("+", "_", $layanan));
                    }


                    $id_layanan = $this->QuotationModel->getIdServices($layanan)->id_layanan;

                    $layanan = $this->QuotationModel->getServices($id_layanan);
                    $type_layanan = $layanan->type;

                    $harga = (float) $data[0][$i][9];
                    $nomor_transaksi = $data[0][$i][10];

                    /**
                     * type 1 goods (normal quotation)
                     * type 2 service (only service quotation)
                     */

                    if ($berat != null || $berat != "") {
                        $berat = $berat;
                        $type = 2;
                    } else {

                        $berat = $this->CalculateWeight($bentuk_produk, $type_layanan, $tebal_transaksi, $lebar_transaksi, $panjang_transaksi, $jumlah);
                        $type = 1;
                    }



                    $subtotal = (float) $berat * (int) $harga;
                    $ppn = $subtotal * 0.11;

                    $total = $subtotal + $ppn;

                    if ($type == 2) {
                        $total -= (int) $harga * 0.02;
                    }



                    $data_transaksi_pembantu = [
                        "kode_transaksi" => $kode_transaksi,
                        "tgl_pembantu" => date("Y-m-d"),
                        "nomor_pekerjaan" => $nomor_pekerjaan,
                        "id_pelanggan" => $id_pelanggan,
                        "nama_produk" => $produk->nama_produk,
                        "id_pegawai" => $id_pegawai,
                        "tebal_pembantu" => $tebal_transaksi,
                        "lebar_pembantu" => $lebar_transaksi,
                        "panjang_pembantu" => $panjang_transaksi,
                        "jumlah_pembantu" => $jumlah,
                        "layanan_pembantu" => $layanan->nama_layanan,
                        "harga_pembantu" => $harga,
                        "ongkir_pembantu" => 0,
                        "id_user" => Auth::user()->id,
                        'tebal_penawaran' => ($type_layanan == 'MILLING' || $type_layanan == 'NF_MILLING') ? $tebal_transaksi + 5 : $tebal_transaksi,
                        'lebar_penawaran' => ($type_layanan == 'MILLING' && $bentuk_produk == 'FLAT' || $type_layanan == 'NF_MILLING' && $bentuk_produk == 'FLAT') ? $lebar_transaksi + 5 : $lebar_transaksi,
                        'panjang_penawaran' => ($type_layanan == 'MILLING' || $type_layanan == 'NF_MILLING') ? $panjang_transaksi + 5 : $panjang_transaksi,
                        'berat_pembantu' => (float)$berat,
                        'bentuk_pembantu' => $bentuk_produk,
                        'subtotal' => $subtotal,
                        'ppn' => $ppn,
                        'total' => $total,
                        'nomor_transaksi' => $nomor_transaksi,
                        "id_layanan" => $id_layanan,
                        "type" => $type


                    ];
                    // dump($data_transaksi_pembantu);
                    array_push($array_data_insert_pembantu, $data_transaksi_pembantu);
                    // $this->QuotationModel->insert_pembantu($data_transaksi_pembantu);
                }
            }
        }


        // dump($errors);
        // dd($array_data_insert_pembantu);

        if (count($errors) == 0) {
            $this->QuotationModel->insert_pembantu($array_data_insert_pembantu);

            return response()->json(["message" => "success"]);
        } else {

            return response()->json(["message" => "fail", "errors" => $errors]);
        }
    }



    public function import_validation($data, $i)
    {


        /**
         * Require (done)
         * type data
         * koma
         * is exist
         */
        // dd("masuk sini");
        // if (is_array($data)) {
        //     // echo $data[0];
        //     dump($data[0]);
        // } else {
        //     dd( "Not an array");
        // }
        $errors = [];
        $id_pegawai = $data[1];
        $id_pelanggan = $data[2];
        $produk = strtoupper($data[3]);
        $tebal_transaksi = $data[4];
        $lebar_transaksi = $data[5];
        $panjang_transaksi = $data[6];
        $jumlah = $data[7];
        $layanan = strtoupper($data[8]);
        $harga = $data[9];
        $nomor_transaksi = $data[10];
        // dd($data);




        $fieldsToCheck = [
            'id_pegawai' => 'Employee Id',
            'id_pelanggan' => 'Customer Id',
            'produk' => 'Product',
            'panjang_transaksi' => 'Transaction Length',
            // 'lebar_transaksi' => 'Transaction Width',
            'tebal_transaksi' => 'Transaction Thickness',
            'jumlah' => 'Quantity',
            'layanan' => 'Processing',
            'harga' => 'Price',
            'nomor_transaksi' => 'Transaction Number',
        ];

        // Melakukan pengujian untuk setiap variabel
        foreach ($fieldsToCheck as $field => $fieldName) {
            if (empty($$field)) {
                array_push($errors, "Please insert $fieldName in row - $i");
            }
        }



        if (str_contains($layanan, "_")) {
            $layanan = strtoupper(str_replace("_", " ", $layanan));
        } else if (str_contains($layanan, "+")) {
            $layanan = strtoupper(str_replace("+", "_", $layanan));
        }


        // check is exist


        // if ((int)$lebar_transaksi <= 0 && $bentuk_produk == 'FLAT') {
        //     return redirect()->back()->withErrors(['lebar_transaksi' => 'Fill Width more then 0'])->withInput();
        // }



        if ($this->pegawai->getEmployeeByCode(strtoupper($id_pegawai)) == null) {
            array_push($errors, "Sales Not Found Please Fill With The Right Code! in row - $i");
        }

        if ($this->CustumorModel->getCustomerByCode($id_pelanggan) == null) {
            array_push($errors, "Customer Not Found Please Fill With The Right Code! in row - $i ");
        }

        if ($this->ProductModel->getProductByCode($produk) == null) {
            array_push($errors, "Product Not Found Please Fill With The Right Code! in row - $i ");
        }

        if ($this->QuotationModel->getIdServices($layanan) == null) {
            array_push($errors, "Service Not Found Please Fill With The Right Code! in row - $i ");
        }



        return $errors;
    }
}

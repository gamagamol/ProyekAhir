<?php

namespace App\Http\Controllers;

use App\Models\pegawaiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\QuotationModel;
use Illuminate\Support\Facades\DB;
use App\Exports\QuotationExport;
use Maatwebsite\Excel\Facades\Excel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class QuotationController extends Controller
{
    public $QuotationModel;
    public $pegawai;
    public $pdf;


    public function __construct()
    {
        $this->QuotationModel = new QuotationModel();
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




        $pembantu = DB::table('pembantu_penawaran')->get();
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


        // Transaction code 

        $transaction_code =
            DB::table('transaksi')
            ->selectRaw("DISTINCT concat('PJ',ifnull(MAX(cast( substr(kode_transaksi,3,3) AS FLOAT)),0)+1) AS kode_transaksi")
            ->first();


        $data = [
            'tittle' => 'ADD Quotation',
            'produk' => $id_produk,
            'pelanggan' => $pelanggan,
            'pembantu' => $pembantu,
            'nama_pelanggan' => $nama_pelanggan,
            'nama_pegawai' => $nama_pegawai,
            'nomor_pekerjaan' => $nomor_pekerjaan,
            'history' => $history,
            'kode_transaksi' => $transaction_code->kode_transaksi,
            'services' => DB::table('layanan')->get(),
            'pegawai' => $this->pegawai->getEmployee('SALES'),

        ];
        // print_r($data);
        // dd($data);
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
            'ongkir' => "required",
            'tgl_penawaran' => "required",
            'id_pegawai' => "required",

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
            'ongkir.required' => "The SHIPPMENT field is required",
            'id_produk.required' => "The PRODUCT field is required!",
            'id_pelanggan.required' => "The CUSTUMOR field is required!",
            'tgl_penawaran.required' => "The Date field is required!",
            'id_pegawai.required' => "The Employee field is required!",


        ];
        $validated = Validator::make($request->all(), $rules, $message);

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
            $layanan = $request->input("layanan");

            if ((int)$lebar_transaksi <= 0 && $bentuk_produk == 'FLAT') {
                return redirect()->back()->withErrors(['lebar_transaksi' => 'Fill Width more then 0'])->withInput();
            }

            // Logika penentuan berat
            // deklarasi

            $berat = $this->CalculateWeight($bentuk_produk, $layanan, $tebal_transaksi, $lebar_transaksi, $panjang_transaksi, $jumlah);

            // dump($berat);
            $subtotal = (float) $berat * (int) str_replace('.', "", $request->input('harga'));

            // dd($subtotal);
            $ppn = $subtotal * 0.11;
            $total = $subtotal + $ppn;
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
                'layanan_pembantu' => $request->input("layanan"),
                'harga_pembantu' => str_replace('.', "", $request->input('harga')),
                'ongkir_pembantu' => str_replace('.', "", $request->input('ongkir')),
                'id_user' => $request->input("id"),
                'tebal_penawaran' => ($layanan == 'MILLING') ? $tebal_transaksi + 5 : $tebal_transaksi,
                'lebar_penawaran' => ($layanan == 'MILLING' && $bentuk_produk == 'FLAT') ? $lebar_transaksi + 5 : $lebar_transaksi,
                'panjang_penawaran' => ($layanan == 'MILLING') ? $panjang_transaksi + 5 : $panjang_transaksi,
                'berat_pembantu' => (float)$berat,
                'bentuk_pembantu' => $bentuk_produk,
                'subtotal' => $subtotal,
                'ppn' => $ppn,
                'total' => $total,


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
            // dump($array);
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
        $data = $this->QuotationModel->show($no_transaksi);




        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('template_report/qtn_template.xlsx');

        $worksheet = $spreadsheet->getActiveSheet();

        $worksheet->getCell('O4')->setValue($data[0]->tgl_penawaran);
        $worksheet->getCell('O5')->setValue($data[0]->no_penawaran);
        $worksheet->getCell('P6')->setValue($data[0]->nama_pelanggan);
        $worksheet->getCell('Q11')->setValue(date('Y-m-d', strtotime($data[0]->tgl_penawaran . ' + 3 days')));
        $worksheet->getCell('Q12')->setValue($data[0]->nama_pegawai);
        $worksheet->getCell('E16')->setValue($data[0]->layanan);

        // alamat
        $worksheet->getCell('A12')->setValue($data[0]->perwakilan);
        $worksheet->getCell('A13')->setValue($data[0]->nama_pelanggan);
        $worksheet->getCell('A14')->setValue($data[0]->alamat_pelanggan);

        // border style
        $border = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => ['argb' => 'FFFF0000'],
                ],
            ],
        ];



        $baris_awal = 19;
        $subtotal = 0;
        $total = 0;
        $ongkir = 0;
        $worksheet->insertNewRowBefore(20, count($data));
        for ($i = 0; $i < count($data); $i++) {


            $tambahan_baris = $baris_awal + 1;

            $worksheet->setCellValue("A$tambahan_baris", ($i + 1));
            $worksheet->setCellValue("B$tambahan_baris", $data[$i]->nomor_pekerjaan);
            $worksheet->MergeCells("B$tambahan_baris:C$tambahan_baris");

            $worksheet->setCellValue("D$tambahan_baris", $data[$i]->nama_produk);
            $worksheet->setCellValue("E$tambahan_baris", $data[$i]->tebal_transaksi);
            $worksheet->setCellValue("F$tambahan_baris", $data[$i]->lebar_transaksi);
            $worksheet->setCellValue("G$tambahan_baris", $data[$i]->panjang_transaksi);
            $worksheet->setCellValue("H$tambahan_baris", $data[$i]->jumlah);
            $worksheet->setCellValue("I$tambahan_baris", $data[$i]->nama_produk);
            $worksheet->setCellValue("J$tambahan_baris", $data[$i]->tebal_penawaran);
            $worksheet->setCellValue("K$tambahan_baris", $data[$i]->lebar_penawaran);
            $worksheet->setCellValue("L$tambahan_baris", $data[$i]->panjang_penawaran);
            $worksheet->setCellValue("M$tambahan_baris", $data[$i]->jumlah);
            $worksheet->setCellValue("N$tambahan_baris", $data[$i]->berat);
            $worksheet->setCellValue("O$tambahan_baris", number_format($data[$i]->harga));
            $worksheet->setCellValue("P$tambahan_baris", number_format($data[$i]->subtotal));
            $worksheet->MergeCells("P$tambahan_baris:Q$tambahan_baris");


            $subtotal += $data[$i]->subtotal;
            $ongkir += $data[$i]->ongkir;
            $total += $data[$i]->total;
            $baris_awal = $tambahan_baris;
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
        $worksheet->setCellValue("E$baris_setelah", $data[0]->nama_pelanggan);
        $worksheet->MergeCells("E$baris_setelah:I$baris_setelah");


        $baris_setelah += 12;
        $worksheet->setCellValue("P$baris_setelah", $data[0]->nama_pengguna);
        $worksheet->setCellValue("E$baris_setelah", $data[0]->perwakilan);





        $namaFile = $data[0]->no_penawaran;

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=$namaFile.xlsx"); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        // $writer->save('report/quotation.xls');


    }




    public function CalculateWeight($bentuk_produk, $layanan, $tebal_transaksi, $lebar_transaksi, $panjang_transaksi, $jumlah)
    {
        switch ($bentuk_produk) {
            case "FLAT":
                if ($layanan == "CUTTING") {
                    $tebal_penawaran = $tebal_transaksi;
                    $lebar_penawaran = $lebar_transaksi;
                    $panjang_penawaran = $panjang_transaksi;

                    $berat = $tebal_penawaran * $lebar_penawaran * $panjang_penawaran * $jumlah * 0.000008;
                    // $berat = number_format($berat, 2, '.', '');
                    // return ($berat > 0) ? round($berat) : $berat;
                    return (float)number_format((float)$berat, 1, '.', '');
                }

                if ($layanan == "NF") {
                    $tebal_penawaran = $tebal_transaksi;
                    $lebar_penawaran = $lebar_transaksi;
                    $panjang_penawaran =  $panjang_transaksi;

                    $berat = $tebal_penawaran * $lebar_penawaran * $panjang_penawaran * $jumlah * 0.00000785;
                    // $berat = number_format($berat, 2, '.', '');
                    // return ($berat > 0) ? round($berat) : $berat;
                    return (float)number_format((float)$berat, 1, '.', '');
                }

                if ($layanan == "MILLING") {
                    //    membuat ukuran dan berat pxl 0,00008
                    $tebal_penawaran = $tebal_transaksi + 5;
                    $lebar_penawaran =  $lebar_transaksi + 5;
                    $panjang_penawaran =  $panjang_transaksi + 5;

                    $berat = $tebal_penawaran * $lebar_penawaran * $panjang_penawaran * $jumlah * 0.000008;
                    // $berat = number_format($berat, 2, '.', '');
                    // return ($berat > 0) ? round($berat) : $berat;
                    return (float)number_format((float)$berat, 1, '.', '');
                }

                if ($layanan == "NF MILLING") {
                    //    membuat ukuran dan berat pxl 0,00008
                    $tebal_penawaran = $tebal_transaksi + 5;
                    $lebar_penawaran =  $lebar_transaksi + 5;
                    $panjang_penawaran =  $panjang_transaksi + 5;

                    $berat = $tebal_penawaran * $lebar_penawaran * $panjang_penawaran * $jumlah * 0.00000785;
                    // $berat = number_format($berat, 2, '.', '');
                    // return ($berat > 0) ? round($berat) : $berat;
                    return (float)number_format((float)$berat, 1, '.', '');
                }




                break;
            case 'CYLINDER':
                if ($layanan == "CUTTING") {
                    //    membuat ukuran dan berat pxl 0,0000625
                    $tebal_penawaran = $tebal_transaksi;
                    $lebar_penawaran = 0;
                    $panjang_penawaran =  $panjang_transaksi;

                    $berat = $tebal_penawaran * $tebal_penawaran * $panjang_penawaran * $jumlah * 0.00000625;
                    // $berat = number_format($berat, 2, '.', '');
                    // return ($berat > 0) ? round($berat) : $berat;
                    return (float)number_format((float)$berat, 1, '.', '');
                }
                if ($layanan == "NF") {
                    $tebal_penawaran = $tebal_transaksi;
                    $lebar_penawaran = 0;
                    $panjang_penawaran = $panjang_transaksi;

                    $berat = $tebal_penawaran * $tebal_penawaran * $panjang_penawaran * $jumlah * 0.00000785;
                    // $berat = number_format($berat, 2, '.', '');
                    // return ($berat > 0) ? round($berat) : $berat;
                    return (float)number_format((float)$berat, 1, '.', '');
                }
                if ($layanan == "MILLING") {
                    //    membuat ukuran dan berat pxl 0,00008
                    $tebal_penawaran = $tebal_transaksi + 5;
                    $lebar_penawaran = 0;
                    $panjang_penawaran =  $panjang_transaksi + 5;

                    $berat = $tebal_penawaran * $tebal_penawaran * $panjang_penawaran * $jumlah * 0.00000625;
                    // $berat = number_format($berat, 2, '.', '');
                    // return ($berat > 0) ? round($berat) : $berat;
                    return (float)number_format((float)$berat, 1, '.', '');
                }
                if ($layanan == "NF MILLING") {
                    //    membuat ukuran dan berat pxl 0,00008
                    $tebal_penawaran = $tebal_transaksi + 5;
                    $lebar_penawaran = 0;
                    $panjang_penawaran =  $panjang_transaksi + 5;

                    $berat = $tebal_penawaran * $tebal_penawaran * $panjang_penawaran * $jumlah * 0.00000785;
                    // $berat = number_format($berat, 2, '.', '');
                    // return ($berat > 0) ? round($berat) : $berat;
                    return (float)number_format((float)$berat, 1, '.', '');
                }
                break;
        }
    }



    public function quotationReportDetail()
    {

        // $data = '';

        // if (request()->input('month')) {
        //     $month = explode('-', request()->input('month'))[1];

        //     $data = $this->QuotationModel->quotationDetailReport($month);
        // } elseif (request()->input('date')) {
        //     $date = explode('-', request()->input('date'))[2];

        //     $data = $this->QuotationModel->quotationDetailReport(null, $date);
        // } elseif (request()->input('date') && request()->input('month')) {
        //     $month = explode('-', request()->input('month'))[1];
        //     $date = explode('-', request()->input('date'))[2];

        //     $data = $this->QuotationModel->quotationDetailReport($month, $date);
        // } else {
        //     $data = $this->QuotationModel->quotationDetailReport();
        // }

        $data = [
            'tittle' => 'Quotation Report Detail',
            // 'data' => $data

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

        $harga_penawaran = $request->harga_edit_penawaran;
        $berat = $this->CalculateWeight(
            $request->bentuk_edit_penawaran,
            $request->layanan_edit_penawaran,
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
}

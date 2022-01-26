<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\QuotationModel;
use Illuminate\Support\Facades\DB;

class QuotationController extends Controller
{
    public $QuotationModel;



    public function __construct()
    {
        $this->QuotationModel = new QuotationModel();
    }
    public function index()
    {

        if (request()->get('serch')) {
            $data = $this->QuotationModel->index($_GET['serch']);
        } else {

            $data = $this->QuotationModel->index();
        }

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

        // Transaction code 

        $transaction_code =
            DB::table('transaksi')
            ->selectRaw("DISTINCT concat('PJ', ifnull( MAX(substring(kode_transaksi,3,2)),0)+1) as kode_transaksi")
            ->first();

        $data = [
            'tittle' => 'ADD Quotation',
            'produk' => $id_produk,
            'pelanggan' => $pelanggan,
            'pembantu' => $pembantu,
            'nama_pelanggan' => $nama_pelanggan,
            'nomor_pekerjaan' => $nomor_pekerjaan,
            'history' => $history,
            'kode_transaksi' => $transaction_code->kode_transaksi,

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
            'tebal_transaksi' => "required|integer",
            'lebar_transaksi' => "required",
            'panjang_transaksi' => "required",
            'jumlah' => "required",
            'harga' => "required",
            'ongkir' => "required",
            'tgl_penawaran' => "required"

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
            'tgl_penawaran.required' => "The CUSTUMOR field is required!",


        ];
        $validated = Validator::make($request->all(), $rules, $message);

        if ($validated->fails()) {
            return redirect('quotation/create')->withErrors($validated)->withInput();
        } else {

            $produk = $request->input("id_produk");
            $produk = explode("|", $request->input('id_produk'));
            $nama_produk = $produk[0];
            $p = DB::table('produk')->where("nama_produk", "=", $nama_produk)->first();
            $bentuk_produk = $p->bentuk_produk;
            $tebal_transaksi = $request->input("tebal_transaksi");
            $lebar_transaksi = $request->input("lebar_transaksi");
            $panjang_transaksi = $request->input("panjang_transaksi");
            $jumlah = $request->input("jumlah");
            $layanan = $request->input("layanan");

            // Logika penentuan berat
            // deklarasi
            switch ($bentuk_produk) {
                case "FLAT":
                    if ($layanan == "CUTTING") {
                        //    membuat ukuran dan berat pxl 0,0000625
                        $tebal_penawaran = $tebal_transaksi;
                        $lebar_penawaran = $lebar_transaksi;
                        $panjang_penawaran = $panjang_transaksi;

                        $berat = $tebal_penawaran * $lebar_penawaran * $panjang_penawaran * $jumlah * 0.00000625;
                        $berat = number_format($berat, 2, '.', '');
                    }
                    if ($layanan == "MILLING") {
                        //    membuat ukuran dan berat pxl 0,00008
                        $tebal_penawaran = $tebal_transaksi + 5;
                        $lebar_penawaran = $lebar_transaksi + 5;
                        $panjang_penawaran = $panjang_transaksi + 5;

                        $berat = $tebal_penawaran * $lebar_penawaran * $panjang_penawaran * $jumlah * 0.000008;
                        $berat = number_format($berat, 2, '.', '');
                    }
                    break;
                case 'CYLINDER':
                    if ($layanan == "CUTTING") {
                        //    membuat ukuran dan berat pxl 0,0000625
                        $tebal_penawaran = $tebal_transaksi;
                        $lebar_penawaran = 0;
                        $panjang_penawaran = $panjang_transaksi;

                        $berat = $tebal_penawaran * $tebal_penawaran * $panjang_penawaran * $jumlah * 0.00000625;
                        $berat = number_format($berat, 2, '.', '');
                    }
                    if ($layanan == "MILLING") {
                        //    membuat ukuran dan berat pxl 0,00008
                        $tebal_penawaran = $tebal_transaksi + 5;
                        $lebar_penawaran = 0;
                        $panjang_penawaran = $panjang_transaksi + 5;

                        $berat = $tebal_penawaran * $tebal_penawaran * $panjang_penawaran * $jumlah * 0.000008;
                        $berat = number_format($berat, 2, '.', '');
                    }
                    break;
            }


            $subtotal = $berat * str_replace('.', "", $request->input('harga'));

            $ppn = $subtotal * 0.1;
            $total = $subtotal + $ppn + str_replace('.', "", $request->input('ongkir'));
            $data = [
                'kode_transaksi' => strtoupper($request->input("kode_transaksi")),
                'tgl_pembantu' => $request->input("tgl_penawaran"),
                'nomor_pekerjaan' => $request->input("nomor_pekerjaan"),
                'id_pelanggan' => $request->input("id_pelanggan"),
                'nama_produk' => $nama_produk,
                'tebal_pembantu' => $request->input("tebal_transaksi"),
                'lebar_pembantu' => $request->input("lebar_transaksi"),
                'panjang_pembantu' => $request->input("panjang_transaksi"),
                'jumlah_pembantu' => $request->input("jumlah"),
                'layanan_pembantu' => $request->input("layanan"),
                'harga_pembantu' => str_replace('.', "", $request->input('harga')),
                'ongkir_pembantu' => str_replace('.', "", $request->input('ongkir')),
                'id_user' => $request->input("id"),
                'tebal_penawaran' => $tebal_penawaran,
                'lebar_penawaran' => $lebar_penawaran,
                'panjang_penawaran' => $panjang_penawaran,
                'berat_pembantu' => $berat,
                'subtotal' => $subtotal,
                'ppn' => $ppn,
                'total' => $total,


            ];

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
                    'panjang_transaksi' => (int)${"array$i"}[6],
                    'lebar_transaksi' => (int)${"array$i"}[5],
                    'tebal_transaksi' => (int)${"array$i"}[4],
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
                ];


                $data_transaksi[] = ${"elemen_transaksi$i"};

                ${"elemen_penawaran$i"} = [
                    "id_transaksi" => 0,
                    "no_penawaran" => 0,
                    "tgl_penawaran" => ${"array$i"}[1],
                    "tebal_penawaran" => (int)${"array$i"}[9],
                    "lebar_penawaran" => (int)${"array$i"}[10],
                    "panjang_penawaran" => (int) ${"array$i"}[11],

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

            $no_quotation = $this->QuotationModel->insert($data_transaksi, $data_penawaran, $data_detail_penawaran, $tgl_penawaran);

            return redirect("show_data")->with("success", "Data Entered Successfully, You Quotation number $no_quotation");
        }
    }


    public function show_data()
    {
        $quotation_data = $this->QuotationModel->show_data();
        $data = [
            'tittle' => "Show Data Quotation Success",
            'data' => $quotation_data
        ];
        return view('quotation.show_data', $data);
    }

    public function show($kode_transaksi)
    {

        $quotation_data = $this->QuotationModel->show($kode_transaksi);

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

    public function print($kode_transaksi)
    {
        dd($quotation_data = $this->QuotationModel->show($kode_transaksi));
    }
}

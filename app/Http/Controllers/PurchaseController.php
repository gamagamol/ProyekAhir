<?php

namespace App\Http\Controllers;

use App\Models\PurchaseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Terbilang;
use function app\helper\penyebut;
use App\Http\Controllers\QuotationController;

class PurchaseController extends Controller
{
    protected $PurchaseModel;
    protected $SupplierModel;
    protected $qc;
    public function __construct()
    {
        $this->PurchaseModel = new PurchaseModel();
        $this->qc = new QuotationController;
    }
    public function index()
    {
        $serch = request()->get('serch');
        if ($serch) {
            $data = $this->PurchaseModel->index($serch);
        } else {
            $data = $this->PurchaseModel->index();
        }
        // dd($data);
        $data = [
            'tittle' => 'Purchase Order',
            "data" => $data,
            'deta' => $this->PurchaseModel->index(),


        ];
        return view('purchase.index', $data);
    }

    public function show($kode_transaksi)
    {

        // dd($this->PurchaseModel->show($kode_transaksi));

        $data = [
            'tittle' => "Create purchase",
            "data" => $this->PurchaseModel->show($kode_transaksi),
            "supplier" => DB::table('pemasok')->get(),
        ];
        return view('purchase.create', $data);
    }

    public function store(Request $request)
    {
        // Persiapan Variable
        // dd($request->input());
        $kode_transaksi = $request->input('kode_transaksi');
        $id_transaksi = $request->input('id_transaksi');
        $tgl_pembelian = $request->input('tgl_pembelian');
        $id_pemasok = $request->input('id_pemasok');
        $unit = $request->input('unit');
        $quotation = $this->PurchaseModel->edit($kode_transaksi);
        $tgl_penjualan = $quotation[0]->tgl_penjualan;
        $id_produk = $request->input('id_produk');
        $id_penawaran = $request->input('id_penawaran');
        $tebal_transaksi = $request->input('tebal_transaksi');
        $lebar_transaksi = $request->input('lebar_transaksi');
        $panjang_transaksi = $request->input('panjang_transaksi');
        $bentuk_produk = $request->input('bentuk_produk');
        $layanan = $request->input('layanan');
        $harga = $request->input('harga');

        $jumlah_unit = $request->input('jumlah');

        $produk = [];
        $arr_produk = [];
        //    check array apa bukan
        if (is_array($id_pemasok)) {


            // Validation Proses
         

            for ($ip = 0; $ip <= count($id_pemasok); $ip++) {
                if ($id_pemasok[$ip] == "null") {
                    return redirect()->back()->with("failed", "Choose Your Supplier ");
                } else {
                    break;
                }
            }

            foreach ($harga as $h) {
                if ($h == null) {
                    return redirect()->back()->with("failed", "Please Fill Coloumn Price");
                } else {
                    if ($h == 0) {
                        return redirect()->back()->with("failed", "Please Fill Coloumn Price More Then 0");
                    }
                }
            }

            // dump($unit);
            // dd($id_produk);

            // mengisi var produk
            for ($ipo = 0; $ipo < count($id_produk); $ipo++) {
                foreach ($quotation as $quoi) {
                    if ($quoi->id_produk == $id_produk[$ipo]) {
                        if ($quoi->id_penawaran == $id_penawaran[$ipo]) {

                            $produk[$ipo] = [
                                'id_produk' => $id_produk[$ipo],
                                'id_penjualan' => $quoi->id_penjualan,
                                'id_penawaran' => $quoi->id_penawaran,
                                'unit' => (int) $unit[$ipo],
                                'harga' => (int)$harga[$ipo],
                                'berat' => (float) $this->qc->CalculateWeight(
                                    $bentuk_produk[$ipo],
                                    $layanan[$ipo],
                                    $tebal_transaksi[$ipo],
                                    $lebar_transaksi[$ipo],
                                    $panjang_transaksi[$ipo],
                                    (int) $unit[$ipo]
                                ),
                                'jumlah_unit' => $jumlah_unit[$ipo],
                            ];
                        }
                    }
                }
            }
            // dump($produk);
            // mengisi arr produk
            $ipdk = 0;
            foreach ($quotation as $quo) {
                $total_unit_produk = 0;
                $total_harga_produk = 0;
                foreach ($produk as $prdk) {

                    if ($prdk['id_produk'] == $quo->id_produk) {
                        if ($prdk['id_penawaran'] == $quo->id_penawaran) {
                            $arr_produk[$ipdk] = [
                                'id_produk' => $quo->id_produk,
                                'id_penawaran' => $quo->id_penawaran,
                                'unit' => $total_unit_produk += $prdk['unit'],
                                // 'jumlah_unit' => $prdk['jumlah_unit'],
                                'harga' => $total_harga_produk += $prdk['harga']



                            ];
                            $ipdk++;
                        }
                    }
                }
            }
            // chek iteem array produk
            // dd($arr_produk);

            foreach ($arr_produk as $apdk) {
                foreach ($produk as $aprdk) {

                    for ($pdkv = 0; $pdkv < count($id_produk); $pdkv++) {
                        if ($produk[$pdkv]['unit'] == null) {
                            return redirect()->back()->with("failed", "Please Fill Unit Coloumn more then 0 ");
                        } else {
                            if ($id_produk[$pdkv] == $apdk['id_produk']) {
                                if ($id_penawaran[$pdkv] == $apdk['id_penawaran']) {

                                    // validasi jumlah produk

                                    if ($apdk['unit'] > $aprdk['jumlah_unit']) {
                                        return redirect()->back()->with("failed", "Please Fill Unit Coloumn with Less Value");
                                    }
                                }

                                // validasi harga

                                //  else {
                                //     if ($apdk['harga'] > $quo1->harga) {
                                //         return redirect()->back()->with("failed", "Please Fill Price Coloumn with Less Value");
                                //     }
                                // }
                            }
                        }
                    }
                }
            }
        }
        else{
            if ($request->input('id_pemasok')==null) {
                return redirect()->back()->with("failed", "Please click the add button for choose your supplier ");
            }
        }
   


        $rules = [
            'tgl_pembelian' => " after_or_equal:$tgl_penjualan",


        ];
        $message = [
            "tgl_pembelian.after_or_equal" => "Choose a date after the quotation date or equal",

        ];
        $validated = Validator::make($request->all(), $rules, $message);
        if ($validated->fails()) {
            return redirect()->back()->with("failed", "Choose a date after the sales date or equal");
        }

        //    kumpulan array data penjualan
        $data_pembelian = [];
        $data_detail_pembelian = [];
        $array_no_pembelian = [];

        // Persiapan no penjualan
        $no_pembelian = $this->PurchaseModel->no_pembelian($tgl_pembelian, $id_pemasok);

        if (is_array($id_pemasok)) {

            foreach ($no_pembelian as $np) {
                $no_quotation = explode('-', $tgl_pembelian);
                $no_pembelian = "PO/$np/$no_quotation[0]/$no_quotation[1]/$no_quotation[2]";
                array_push($array_no_pembelian, $no_pembelian);
            }
        } else {
            $no_quotation = explode('-', $tgl_pembelian);
            $no_pembelian = "PO/$no_pembelian[0]/$no_quotation[0]/$no_quotation[1]/$no_quotation[2]";
        }



        // pengisian array data penjualan
        // mencari harga dan total
        if (is_array($id_pemasok)) {

            $i = 0;
            foreach ($array_no_pembelian as $anp) {
                if ($id_produk[$i] == $produk[$i]['id_produk']) {

                    $data_pembelian[$i] = [
                        'id_penjualan' => $produk[$i]['id_penjualan'],
                        'id_transaksi' => $id_transaksi[$i],
                        'no_pembelian' => $anp,
                        'tgl_pembelian' => $tgl_penjualan
                    ];



                    $data_detail_pembelian[$i] = [
                        'id_pembelian' => 0,
                        'id_produk' => $id_produk[$i],
                        'jumlah_detail_pembelian' => $unit[$i],
                        'harga_detail_pembelian' => $produk[$i]['harga'],
                        'total_detail_pembelian' => ($produk[$i]['harga'] * $produk[$i]['berat']) + (($produk[$i]['harga'] * $produk[$i]['berat']) * 0.11),
                        'berat_detail_pembelian' => $produk[$i]['berat'],
                        'subtotal_detail_pembelian' => $produk[$i]['harga'] * $produk[$i]['berat'],
                        'ppn_detail_pembelian' => ($produk[$i]['harga'] * $produk[$i]['berat']) * 0.11,



                    ];
                }

                $i++;
            }
        } else {
            $i = 0;
            foreach ($quotation as $quos) {
                $data_pembelian[$i] = [
                    'id_penjualan' => $quos->id_penjualan,
                    'id_transaksi' => $quos->id_transaksi,
                    'no_pembelian' => $no_pembelian,
                    'tgl_pembelian' => $tgl_penjualan
                ];



                $data_detail_pembelian[$i] = [
                    'id_pembelian' => 0,
                    'id_produk' => $quos->id_produk,
                    'jumlah_detail_pembelian' => $quos->jumlah_unit,
                    'harga_detail_pembelian' => $quos->harga,
                    'total_detail_pembelian' => ($quos->harga * $quos->berat) + ($quos->harga * $quos->berat) * 0.11,
                    'berat_detail_pembelian' => $quos->berat,
                    'subtotal_detail_pembelian' => $quos->harga * $quos->berat,
                    'ppn_detail_pembelian' => ($quos->harga * $quos->berat) * 0.11,



                ];
                $i++;
            }
        }
        // jangan di hapus untuk check isi array
        // dump($data_pembelian);
        // dd($data_detail_pembelian);


        $no_pembelian = $this->PurchaseModel->insert_penjualan($id_transaksi, $data_pembelian, $data_detail_pembelian, $id_pemasok, $kode_transaksi);

        return redirect('purchase')->with('success', "Data entered successfully Please Chek Your Detail Transaction for more information");
    }
    public function detail($no_pembelian)
    {

        $data = $this->PurchaseModel->detail(str_replace("-", "/", $no_pembelian));
        $data = [
            'tittle' => "Detail purchase Order",
            'data' => $data
        ];
        return view('purchase.detail', $data);
    }


    public function print($no_transaksi)
    {
        $total = $this->PurchaseModel->detail(str_replace("-", "/", $no_transaksi));

        foreach ($total as $t) {

            $total = penyebut($t->total);
        }
        $data = [
            'tittle' => "Print purchase Order",
            'data' => $this->PurchaseModel->detail(str_replace("-", "/", $no_transaksi)),
            'total' => $total
        ];
        return view('purchase.print', $data);
    }
}

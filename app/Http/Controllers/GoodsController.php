<?php

namespace App\Http\Controllers;

use App\Models\GoodsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GoodsController extends Controller
{
    protected $goods;
    public function __construct()
    {
        $this->goods = new GoodsModel();
    }
    public function index()
    {
        $serch = request()->get('serch');
        if ($serch) {
            $data = $this->goods->index($serch);
        } else {
            $data = $this->goods->index();
        }



        $data = [
            'tittle' => 'Goods Receipt',
            "data" => $data,
            'deta' => $this->goods->index()


        ];
        // dd($data);
        return view('goods.index', $data);
    }

    public function show($no_pembelian)
    {
        $no_pembelian = str_replace('-', '/', $no_pembelian);
        $data = [
            'tittle' => "Create Goods Receipt",
            "data" => $this->goods->show($no_pembelian),
        ];
        // dd($data);

        return view('goods.create', $data);
    }

    public function store(Request $request)
    {
        // variabel yang di butuhkan 
        $kode_transaksi = $request->input('kode_transaksi');
        $tgl_penerimaan = $request->input('tgl_penerimaan');
        $unit = $request->input('unit');
        $id_produk = $request->input('id_produk');
        $no_pembelian = $request->input('no_pembelian');

        // persiapan array
        $produk = [];
        $arr_produk = [];

        if ($unit) {

            $purchase = $this->goods->edit($no_pembelian);
            $id_transaksi = $request->input('id_transaksi');
        } else {
            $id_transaksi = [];
            $purchase = $this->goods->edit($no_pembelian, $kode_transaksi);
            foreach ($purchase as $pcssi) {
                array_push($id_transaksi, $pcssi->id_transaksi);
            }
        }







        if ($unit) {

            // mengisi variable produk
            for ($ipo = 0; $ipo < count($id_produk); $ipo++) {
                foreach ($purchase as $quoi) {
                    if ($quoi->id_produk == $id_produk[$ipo]) {
                        $produk[$ipo] = [
                            'id_produk' => $id_produk[$ipo],
                            'unit' => (int) $unit[$ipo],

                        ];
                    }
                }
            }

            // // mengisi arr produk
            $ipdk = 0;
            foreach ($purchase as $quo) {
                $total_unit_produk = 0;
                foreach ($produk as $prdk) {
                    if ($prdk['id_produk'] == $quo->id_produk) {
                        $arr_produk[$ipdk] = [
                            'id_produk' => $quo->id_produk,
                            'unit' => $total_unit_produk += $prdk['unit'],
                            'jumlah_unit' => $quo->jumlah_detail_pembelian,



                        ];
                    }
                }
                $ipdk++;
            }

            // validasi unit produk
            foreach ($arr_produk as $apdk) {
                foreach ($purchase as $quo1) {

                    for ($pdkv = 0; $pdkv < count($id_produk); $pdkv++) {
                        if ($produk[$pdkv]['unit'] == null) {
                            return redirect()->back()->with("failed", "Please Fill Unit Coloumn more then 0 ");
                        } else {
                            if ($id_produk[$pdkv] == $apdk['id_produk']) {
                                if ($apdk['unit'] > $apdk['jumlah_unit']) {
                                    return redirect()->back()->with("failed", "Please Fill Unit Coloumn with Less Value");
                                }
                            }
                        }
                    }
                }
            }
        }



        $tgl_purchase = $purchase[0]->tgl_pembelian;

        // $rules = [
        //     'tgl_penerimaan' => " after_or_equal:$tgl_purchase",
        // ];
        // $message = [
        //     "tgl_penerimaan.after_or_equal" => "Choose a date after the purchase date or equal"
        // ];
        // $validated = Validator::make($request->all(), $rules, $message);
        // if ($validated->fails()) {
        //     return redirect()->back()->with("failed", "Choose a date after the purchase date or equal");
        // }


        //    kumpulan array data penjualan
        $data_penerimaan = [];
        $data_detail_penerimaan = [];
        $arr_no_penerimaan = [];

        // Persiapan no penerimaan

        $no_penerimaan = $this->goods->no_penerimaan($tgl_penerimaan, $unit);
        if (is_array($no_penerimaan)) {


            foreach ($no_penerimaan as $anp) {

                $no_purchase = explode('-', $tgl_penerimaan);
                $no_penerimaan = "GR/$anp/$no_purchase[0]/$no_purchase[1]/$no_purchase[2]";
                array_push($arr_no_penerimaan, $no_penerimaan);
            }
        } else {
            $no_purchase = explode('-', $tgl_penerimaan);
            $no_penerimaan = "GR/$no_penerimaan/$no_purchase[0]/$no_purchase[1]/$no_purchase[2]";
            array_push($arr_no_penerimaan, $no_penerimaan);
        }

        if ($unit) {
            # code...
            $i = 0;
            foreach ($arr_no_penerimaan as $anps) {
                if ($id_produk[$i] == $produk[$i]['id_produk']) {
                    $data_penerimaan[$i] = [
                        'id_transaksi' => $id_transaksi[$i],
                        'no_penerimaan' => $anps,
                        'tgl_penerimaan' => $tgl_penerimaan
                    ];


                    $data_detail_penerimaan[$i] = [
                        'id_penerimaan_barang' => 0,
                        'id_produk' => $id_produk[$i],
                        'jumlah_detail_penerimaan' => $unit[$i]
                    ];
                }
                $i++;
            }
        } else {
            $i = 0;
            foreach ($purchase as $pcss) {

                $data_penerimaan[$i] = [
                    'id_pembelian' => $pcss->id_pembelian,
                    'id_transaksi' => $pcss->id_transaksi,
                    'no_penerimaan' => $no_penerimaan,
                    'tgl_penerimaan' => $tgl_penerimaan
                ];


                $data_detail_penerimaan[$i] = [
                    'id_penerimaan_barang' => 0,
                    'id_produk' => $pcss->id_produk,
                    'jumlah_detail_penerimaan' => $pcss->jumlah_detail_pembelian
                ];
                $i++;
            }
        }
        // check isi variable akhir
        // dd(count($id_transaksi));
        // dump($purchase);
        // dump($data_penerimaan);
        // dd($data_detail_penerimaan);


        $this->goods->insert_penerimaan($id_transaksi, $data_penerimaan, $data_detail_penerimaan, $unit);

        return redirect('goods')->with('success', "Data entered successfully Please Chek Your Detail Transaction for more information");
    }
    public function detail($no_pembelian, $no_penerimaan)
    {

        $data = $this->goods->detail(str_replace("-", "/", $no_pembelian), str_replace("-", "/", $no_penerimaan));
       
        $data = [
            'tittle' => "Detail Goods Receipt",
            'data' => $data
        ];
        return view('goods.detail', $data);
    }
}

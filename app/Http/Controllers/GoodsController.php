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
        return view('goods.index', $data);
    }

    public function show($kode_transaksi)
    {
        $data = [
            'tittle' => "Create Goods Receipt",
            "data" => $this->goods->show($kode_transaksi),
        ];
        return view('goods.create', $data);
    }

    public function store(Request $request)
    {
        $kode_transaksi = $request->input('kode_transaksi');
        $tgl_penerimaan = $request->input('tgl_penerimaan');


        $purchase = $this->goods->edit($kode_transaksi);
        $tgl_purchase = $purchase[0]->tgl_pembelian;
        $rules = [
            'tgl_penerimaan' => " after_or_equal:$tgl_purchase",
        ];
        $message = [
            "tgl_penerimaan.after_or_equal" => "Choose a date after the purchase date or equal"
        ];
        $validated = Validator::make($request->all(), $rules, $message);
        if ($validated->fails()) {
            return redirect()->back()->with("failed", "Choose a date after the purchase date or equal");
        }


        //    kumpulan array data penjualan
        $id_transaksi = [];
        $data_penerimaan = [];
        $data_detail_penerimaan = [];

        // Persiapan no penjualan
        $no_penerimaan = $this->goods->no_penerimaan($tgl_penerimaan);
        $no_purchase = explode('-', $tgl_penerimaan);
        $no_penerimaan = "GR/$no_penerimaan/$no_purchase[0]/$no_purchase[1]/$no_purchase[2]";



        for ($i = 0; $i < count($purchase); $i++) {

            $id_transaksi[] = $purchase[$i]->id_transaksi;

            $data_penerimaan[] = [
                'id_transaksi' => $purchase[$i]->id_transaksi,
                'no_penerimaan' => $no_penerimaan,
                'tgl_penerimaan' => $tgl_penerimaan
            ];



            $data_detail_penerimaan[] = [
                'id_penerimaan_barang' => 0,
                'id_produk' => $purchase[$i]->id_produk,
            ];
        }



        $no_penerimaan = $this->goods->insert_penerimaan($id_transaksi, $data_penerimaan, $data_detail_penerimaan);
        return redirect('goods')->with('success', "Data entered successfully, Your goods Number $no_penerimaan ");
    }
    public function detail($no_penerimaan)
    {

        $data = $this->goods->detail(str_replace("-", "/", $no_penerimaan));
        $data = [
            'tittle' => "Detail Goods Receipt",
            'data' => $data
        ];
        return view('goods.detail', $data);
    }
}

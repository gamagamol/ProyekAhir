<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeliveryModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class DeliveryController extends Controller
{
    public $model;
    public $PDF;
    public function __construct()
    {
        $this->model = new DeliveryModel();

    }
    public function index()
    {
        $serch = request()->get('serch');
        if ($serch) {
            $data = $this->model->index($serch);
        } else {
            $data = $this->model->index();
        }

        $data = [
            'tittle' => "Delivery Order",
            'data' => $data,
            'deta' => $this->model->index()
        ];
        return view('delivery.index', $data);
    }

    public function show($kode_transaksi)
    {
        $sales = $this->model->show($kode_transaksi);
        $data = [
            'tittle' => "Delivery Order",
            "data" => $sales
        ];
        return view("delivery.create", $data);
    }

    public function store(Request $request)
    {

        $tgl_pengiriman = $request->post('tgl_pengiriman');
        $id_transaksi = $request->post('id_transaksi');
        if ($id_transaksi == null) {
            return back()->with("failed", "Please select the item you want to send first!");
        }
        $penjualan = $this->model->data($id_transaksi);
        $penerimaan = DB::table('penerimaan_barang')->select('tgl_penerimaan')->where('id_transaksi', "=", $id_transaksi)->first();
        $tgl_penerimaan = $penerimaan->tgl_penerimaan;

        $tgl_penjualan = $penjualan[0]->tgl_penjualan;
        $rules = [
            'tgl_pengiriman' => " after_or_equal:$tgl_penerimaan",
        ];
        $message = [
            "tgl_pengiriman.after_or_equal" => "Choose a date after the goods receipt date or equal"
        ];
        $validated = Validator::make($request->all(), $rules, $message);
        if ($validated->fails()) {
            return redirect()->back()->with("failed", "Choose a date after the goods receipt date or equal");
        }
        // membuat no pengiriman
        $no_pengiriman = $this->model->no_delivery($tgl_pengiriman);
        // dd($no_pengiriman);
        $no_penjualan = $penjualan[0]->no_penjualan;
        $no_penjualan = explode("-", $tgl_pengiriman);

        $no_pengiriman = "DO/$no_pengiriman/$no_penjualan[0]/$no_penjualan[1]/$no_penjualan[2]";

        $id_transaksi = [];
        $data_pengiriman = [];
        $data_detail_pengiriman = [];

        for ($i = 0; $i < count($penjualan); $i++) {
            $id_transaksi[] = $penjualan[$i]->id_transaksi;

            $data_pengiriman[$i] = [
                'id_transaksi' => $penjualan[$i]->id_transaksi,
                'no_pengiriman' => $no_pengiriman,
                'tgl_pengiriman' => $tgl_pengiriman,
            ];

            $data_detail_pengiriman[$i] = [
                'id_pengiriman' => 0,
                'id_produk' => $penjualan[$i]->id_produk,
                'id_penjualan' => $penjualan[$i]->id_penjualan,
            ];
        }
        $this->model->insert_delivery($id_transaksi, $data_pengiriman, $data_detail_pengiriman);

        return redirect('delivery')->with('success', "Data Entered Successfully, Your Delivery Number $no_pengiriman");
    }
    public function edit($kode_transaksi)
    {
        $sales = $this->model->edit($kode_transaksi);


        //    kumpulan array data penjualan
        $id_transaksi = [];
        $data_pengiriman = [];
        $data_detail_pengiriman = [];

        // Persiapan no pengiriman
        $no_pengiriman = explode('/', $sales[0]->no_penjualan);
        $no_pengiriman[0] = "DO";
        $no_pengiriman = "$no_pengiriman[0]/$no_pengiriman[1]/$no_pengiriman[2]/$no_pengiriman[3]/$no_pengiriman[4]";
        for ($i = 0; $i < count($sales); $i++) {

            $id_transaksi[] = $sales[$i]->id_transaksi;

            $data_pengiriman[] = [
                'id_transaksi' => $sales[$i]->id_transaksi,
                'no_pengiriman' => $no_pengiriman,
                'tgl_pengiriman' => date("Y-m-d")
            ];


            $data_detail_pengiriman[] = [
                'id_pengriman' => 0,
                'id_produk' => $sales[$i]->id_produk,
                'id_penjualan' => $sales[$i]->id_penjualan,
            ];
        }
        dd($data_pengiriman);

        $no_pengiriman = $this->model->insert_delivery($id_transaksi, $data_pengiriman, $data_detail_pengiriman);
        return redirect('sales')->with('success', "Data entered successfully, Your Sales Number $no_pengiriman ");
    }

    public function detail($no_pengiriman)
    {

        $data = $this->model->detail(str_replace("-", "/", $no_pengiriman));
        $data = [
            'tittle' => "Detail Delivery Order",
            'data' => $data
        ];
        return view('delivery.detail', $data);
    }


    public function print($no_transaksi){

        
      $no_transaksi=str_replace('-','/',$no_transaksi);


        $data=[
            'tittle'=>"Print Delivery Document",
            'data'=> $this->model->index($no_transaksi),
        ];

        return view('delivery.print',$data);
        
    }
}

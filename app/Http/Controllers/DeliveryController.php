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

    public function show($no_penerimaan)
    {
        $no_penerimaan = str_replace('-', '/', $no_penerimaan);
        $penerimaan = $this->model->show($no_penerimaan);
        // dd($penerimaan);
        $data = [
            'tittle' => "Delivery Order",
            "data" => $penerimaan
        ];
        return view("delivery.create", $data);
    }

    public function store(Request $request)
    {
        // perispan Variable
        $tgl_pengiriman = $request->post('tgl_pengiriman');
        $id_transaksi = $request->post('id_transaksi');
        $no_penerimaan = $request->input('no_penerimaan');
        $penerimaan = $this->model->edit($no_penerimaan);
        $tgl_penerimaan = $penerimaan[0]->tgl_penerimaan;
        $unit = $request->input('unit');
        $id_produk = $request->input('id_produk');
        // persiapan array
        $produk = [];
        $arr_produk = [];
       

        if ($unit) {


            // mengisi variable produk
            for ($ipo = 0; $ipo < count($id_produk); $ipo++) {
                foreach ($penerimaan as $quoi) {
                    if ($quoi->id_produk == $id_produk[$ipo]) {
                        $produk[$ipo] = [
                            'id_produk' => $id_produk[$ipo],
                            'unit' => (int) $unit[$ipo],
                            'id_penjualan'=>$quoi->id_penjualan

                        ];
                    }
                }
            }


            // // mengisi arr produk
            $ipdk = 0;
            foreach ($penerimaan as $quo) {
                $total_unit_produk = 0;
                foreach ($produk as $prdk) {
                    if ($prdk['id_produk'] == $quo->id_produk) {
                        $arr_produk[$ipdk] = [
                            'id_produk' => $quo->id_produk,
                            'unit' => $total_unit_produk += $prdk['unit'],
                            'jumlah_unit' => $quo->jumlah_detail_penerimaan,



                        ];
                    }
                }
                $ipdk++;
            }


            // validasi unit produk
            foreach ($arr_produk as $apdk) {
                foreach ($penerimaan as $quo1) {

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



        // check isi produk dan array produk
        // dump($produk);
        // dd($arr_produk);





        if ($id_transaksi == null) {
            return back()->with("failed", "Please select the item you want to send first!");
        }
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

        //    kumpulan array data penjualan
        $data_pengiriman = [];
        $data_detail_pengiriman= [];
        $arr_no_pengiriman = [];

        // Persiapan no penerimaan
    

            $no_pengiriman = $this->model->no_delivery($tgl_penerimaan, $unit);


        if (is_array($no_pengiriman)) {


            foreach ($no_pengiriman as $anp) {

                $no_purchase = explode('-', $tgl_pengiriman);
                $no_penerimaan = "DO/$anp/$no_purchase[0]/$no_purchase[1]/$no_purchase[2]";
                array_push($arr_no_pengiriman, $no_penerimaan);
            }
        } else {
            $no_purchase = explode('-',
                $tgl_pengiriman
            );
            $no_penerimaan = "DO/$no_pengiriman/$no_purchase[0]/$no_purchase[1]/$no_purchase[2]";
            array_push($arr_no_pengiriman, $no_penerimaan);

        }



        if ($unit) {
            # code...
            $i = 0;
            foreach ($arr_no_pengiriman as $anps) {
                if ($id_produk[$i] == $produk[$i]['id_produk']) {
                    $data_pengiriman[$i] = [
                        'id_transaksi' => $id_transaksi[$i],
                        'no_penerimaan' => $anps,
                        'tgl_pengiriman' => $tgl_pengiriman
                    ];


                    $data_detail_pengiriman[$i] = [
                        'id_penerimaan_barang' => 0,
                        'id_produk' => $id_produk[$i],
                        'id_penjualan'=>$produk[$i]['id_penjualan'],
                        'jumlah_detail_penerimaan' => $unit[$i]
                    ];
                }
                $i++;
            }
        } else {
            $i = 0;
            foreach ($penerimaan as $pcss) {
                $data_pengiriman[$i] = [
                        'id_transaksi' => $pcss->id_transaksi,
                        'no_pengiriman' => $arr_no_pengiriman[0],
                        'tgl_pengiriman' => $tgl_pengiriman
                    ];


                $data_detail_pengiriman[$i] = [
                    'id_pengiriman' => 0,
                    'id_produk' => $pcss->id_produk,
                    'id_penjualan'=>$pcss->id_penjualan,
                    'jumlah_detail_pengiriman' => $pcss->jumlah_detail_penerimaan
                ];
                $i++;
            }
        }
        // check isi data yang mau di insert
       
        $this->model->insert_delivery($id_transaksi,$data_pengiriman,$data_detail_pengiriman,$unit);

        return redirect('delivery')->with('success', "Data entered successfully,Please Click Detail For more Information");
        
  
    }





    // public function edit($kode_transaksi)
    // {
    //     $sales = $this->model->edit($kode_transaksi);


    //     //    kumpulan array data penjualan
    //     $id_transaksi = [];
    //     $data_pengiriman = [];
    //     $data_detail_pengiriman = [];

    //     // Persiapan no pengiriman
    //     $no_pengiriman = explode('/', $sales[0]->no_penjualan);
    //     $no_pengiriman[0] = "DO";
    //     $no_pengiriman = "$no_pengiriman[0]/$no_pengiriman[1]/$no_pengiriman[2]/$no_pengiriman[3]/$no_pengiriman[4]";
    //     for ($i = 0; $i < count($sales); $i++) {

    //         $id_transaksi[] = $sales[$i]->id_transaksi;

    //         $data_pengiriman[] = [
    //             'id_transaksi' => $sales[$i]->id_transaksi,
    //             'no_pengiriman' => $no_pengiriman,
    //             'tgl_pengiriman' => date("Y-m-d")
    //         ];


    //         $data_detail_pengiriman[] = [
    //             'id_pengriman' => 0,
    //             'id_produk' => $sales[$i]->id_produk,
    //             'id_penjualan' => $sales[$i]->id_penjualan,
    //         ];
    //     }
    //     dd($data_pengiriman);

    //     $no_pengiriman = $this->model->insert_delivery($id_transaksi, $data_pengiriman, $data_detail_pengiriman);
    //     return redirect('sales')->with('success', "Data entered successfully, Your Sales Number $no_pengiriman ");
    // }













    public function detail($no_pengiriman)
    {

        $data = $this->model->detail(str_replace("-", "/", $no_pengiriman));
        $data = [
            'tittle' => "Detail Delivery Order",
            'data' => $data
        ];
        return view('delivery.detail', $data);
    }


    public function print($no_transaksi)
    {


        $no_transaksi = str_replace('-', '/', $no_transaksi);


        $data = [
            'tittle' => "Print Delivery Document",
            'data' => $this->model->index($no_transaksi),
        ];

        return view('delivery.print', $data);
    }
}

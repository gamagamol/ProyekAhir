<?php

namespace App\Http\Controllers;

use App\Models\PaymentVendorModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class PaymentVendorController extends Controller
{
    public $model;
    public function __construct()
    {
        $this->model = new PaymentVendorModel();
    }
    public function index()
    {
        $serch=request()->get('serch');
        if ($serch) {
            $data=$this->model->index($serch);
        }else{
            $data=$this->model->index();
        }
        $data = [
            'tittle' => "Payment To Vendor",
            'data' => $data,
            'deta'=>$this->model->index(),
            

        ];
        return view('paymentvendor.index', $data);
    }

    public function show($no_pembelian)
    {
        $no_pembelian = str_replace('-', '/', $no_pembelian);
        $data = [
            'tittle' => "Create Paymnet To Vendor",
            'data' => $this->model->show($no_pembelian),
        ];
        return view('paymentvendor.create', $data);
    }
    public function store(Request $request)
    {
        $kode_transaksi = $request->input('kode_transaksi');
        $tgl_pembelian = $request->input('tgl_pembelian');



        $purchase = $this->model->edit($kode_transaksi);

        $tgl_pembelian = $purchase[0]->tgl_pembelian;
        $rules = [
            'tgl_pembelian' => " after_or_equal:$tgl_pembelian",
        ];
        $message = [
            "tgl_pembelian.after_or_equal" => "Choose a date after the purchase date or equal",
        ];
        $validated = Validator::make($request->all(), $rules, $message);
        if ($validated->fails()) {
            return redirect()->back()->with("failed", "Choose a date after the purchase date or equal");
        }

        //    kumpulan array data pembayaran vendor
        $data_pembelian = [];


        for ($i = 0; $i < count($purchase); $i++) {


            $data_pembelian[$i] = [
                'id_transaksi' => $purchase[$i]->id_transaksi,
                'id_pembelian'=>$purchase[$i]->id_pembelian,
                'no_pembayaran_vendor' => $purchase[$i]->no_pembelian,
                'tgl_pembayaran_vendor' => $tgl_pembelian
            ];



           
        }
       $this->model->insert($data_pembelian);
        return redirect('paymentvendor')->with('success','Payment to vendor has been success');

        
    }

    public function detail($no_pembelian)
    {

        $data = $this->model->detail(str_replace("-", "/", $no_pembelian));
        $data = [
            'tittle' => "Detail Payment To Vendor",
            'data' => $data
        ];
        return view('paymentvendor.detail', $data);
    }
}

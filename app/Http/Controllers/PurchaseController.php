<?php

namespace App\Http\Controllers;

use App\Models\PurchaseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    protected $PurchaseModel;
    protected $SupplierModel;
    public function __construct()
    {
        $this->PurchaseModel = new PurchaseModel();
    }
    public function index()
    {
        $serch=request()->get('serch');
        if ($serch) {
            $data = $this->PurchaseModel->index($serch);
            
        }else{
            $data = $this->PurchaseModel->index();

        }

        $data = [
            'tittle' => 'Purchase Order',
            "data" => $data,
            'deta'=> $this->PurchaseModel->index(),


        ];
        return view('purchase.index', $data);
    }

    public function show($kode_transaksi)
    {

        $data = [
            'tittle' => "Create purchase",
            "data" => $this->PurchaseModel->show($kode_transaksi),
            "supplier" => DB::table('pemasok')->get(),
        ];
        return view('purchase.create', $data);
    }

    public function store(Request $request)
    {
        $kode_transaksi = $request->input('kode_transaksi');
        $tgl_pembelian = $request->input('tgl_pembelian');
        $id_pemasok = $request->input('id_pemasok');
       if ($id_pemasok==null) {
            return redirect()->back()->with("failed", "Choose Your Supplier");
           
       }


        $quotation = $this->PurchaseModel->edit($kode_transaksi);

        $tgl_penjualan = $quotation[0]->tgl_penjualan;
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
        $id_transaksi = [];
        $data_pembelian = [];
        $data_detail_pembelian = [];

        // Persiapan no penjualan
        $no_pembelian = $this->PurchaseModel->no_pembelian($tgl_pembelian);
        $no_quotation = explode('-', $tgl_pembelian);
        $no_pembelian = "PSC/$no_pembelian/$no_quotation[0]/$no_quotation[1]/$no_quotation[2]";




        for ($i = 0; $i < count($quotation); $i++) {

            $id_transaksi[] = $quotation[$i]->id_transaksi;

            $data_pembelian[] = [
                'id_transaksi' => $quotation[$i]->id_transaksi,
                'no_pembelian' => $no_pembelian,
                'tgl_pembelian' => $tgl_penjualan
            ];



            $data_detail_pembelian[] = [
                'id_pembelian' => 0,
                'id_produk' => $quotation[$i]->id_produk,
            ];
        }

       

        $no_pembelian = $this->PurchaseModel->insert_penjualan($id_transaksi, $data_pembelian, $data_detail_pembelian,$id_pemasok,$kode_transaksi);
        return redirect('purchase')->with('success', "Data entered successfully, Your purchase Number $no_pembelian ");
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
}

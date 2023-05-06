<?php

namespace App\Http\Controllers;

use App\Models\QuotationModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\SalesModel;

class SalesController extends Controller
{
    protected $SalesModel;
    protected $quotationModel;
    public function __construct()
    {
        $this->SalesModel = new SalesModel();
        $this->quotationModel = new QuotationModel();
    }
    public function index()
    {
        $serch = request()->get('serch');
        if ($serch) {

            $data = $this->SalesModel->index($serch);
        } else {
            $data = $this->SalesModel->index();
        }
        // dump(DB::table('penjualan')->distinct()->select('no_penjualan')->get());
        $data = [
            'tittle' => 'Sales Order',
            "data" => $data,
            "deta" => DB::table('penjualan')->distinct()->select('no_penjualan')->get()


        ];
        return view('sales.index', $data);
    }

    public function show($kode_transaksi)
    {
        $data = [
            'tittle' => "Create Sales",
            "data" => $this->SalesModel->show($kode_transaksi),
        ];
        return view('sales.create', $data);
    }

    public function store(Request $request)
    {
        $kode_transaksi = $request->input('kode_transaksi');
        $tgl_penjualan = $request->input('tgl_penjualan');
        // check data
        $quotation = $this->SalesModel->edit($kode_transaksi);
        $tgl_quotation = $quotation[0]->tgl_penawaran;
        $rules = [
            'tgl_penjualan' => " after_or_equal:$tgl_quotation",
        ];
        $message = [
            "tgl_penjualan.after_or_equal" => "Choose a date after the quotation date or equal"
        ];
        $validated = Validator::make($request->all(), $rules, $message);
        if ($validated->fails()) {
            return redirect()->back()->with("failed", "Choose a date after the quotation date or equal");
        }

        if (count($quotation) != count($request->id_transaksi)) {

            $id_transaksi_tidak_terpakai = [];

            for ($j = 0; $j < count($quotation); $j++) {
                foreach ($request->id_transaksi as $id_transaksi) {
                    if ($id_transaksi != $quotation[$j]->id_transaksi) {
                        array_push($id_transaksi_tidak_terpakai, $quotation[$j]->id_transaksi);
                    }
                }
            }

            $this->quotationModel->updateIdTidakTerpakai($id_transaksi_tidak_terpakai, ['tidak_terpakai' => 1]);


            $quotation = $this->SalesModel->edit($kode_transaksi, $request->id_transaksi);
        }



        //    kumpulan array data penjualan
        $id_transaksi = [];
        $data_penjualan = [];
        $data_detail_penjualan = [];

        // Persiapan no penjualan
        $no_penjualan = $this->SalesModel->no_penjualan($tgl_penjualan);
        $tgl_penjualan = $request->input('tgl_penjualan');
        $no_quotation = explode('-', $tgl_penjualan);
        $no_penjualan = "SO/$no_penjualan/$no_quotation[0]/$no_quotation[1]/$no_quotation[2]";




        for ($i = 0; $i < count($quotation); $i++) {

            $id_transaksi[] = $quotation[$i]->id_transaksi;

            $data_penjualan[] = [
                'id_transaksi' => $quotation[$i]->id_transaksi,
                'no_penjualan' => $no_penjualan,
                'tgl_penjualan' => $tgl_penjualan
            ];



            $data_detail_penjualan[] = [
                'id_penjualan' => 0,
                'id_produk' => $quotation[$i]->id_produk,
                'jumlah_detail_penjualan' => $quotation[$i]->jumlah
            ];
        }

        // update data quotation


        // dump($data_penjualan);
        // dump($data_detail_penjualan);
        // die;


        $no_penjualan = $this->SalesModel->insert_penjualan($id_transaksi, $data_penjualan, $data_detail_penjualan, $kode_transaksi);
        return redirect()->route('sales.detail', str_replace('/', '-', $no_penjualan))
            ->with('success', "Data entered successfully, Your Sales Number $no_penjualan ");
    }
    public function detail($no_penjualan)
    {

        $data = $this->SalesModel->detail(str_replace("-", "/", $no_penjualan));
        $data = [
            'tittle' => "Detail Sales Order",
            'data' => $data
        ];
        return view('sales.detail', $data);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\PurchaseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Foreach_;
use Terbilang;
use function app\helper\penyebut;

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
        $serch = request()->get('serch');
        if ($serch) {
            $data = $this->PurchaseModel->index($serch);
        } else {
            $data = $this->PurchaseModel->index();
        }

        $data = [
            'tittle' => 'Purchase Order',
            "data" => $data,
            'deta' => $this->PurchaseModel->index(),


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
        // Persiapan Variable
        $kode_transaksi = $request->input('kode_transaksi');
        $id_transaksi=$request->input('id_transaksi');
        $tgl_pembelian = $request->input('tgl_pembelian');
        $id_pemasok = $request->input('id_pemasok');
        $unit = $request->input('unit');
        $quotation = $this->PurchaseModel->edit($kode_transaksi);
        $tgl_penjualan = $quotation[0]->tgl_penjualan;
        $id_produk = $request->input('id_produk');

        $produk = [];
        $arr_produk = [];




        // Validation Proses

        if (!$request->input('id_pemasok')) {
            return redirect()->back()->with("failed", "Please click the add button for choose your supplier ");
        }

        for ($ip = 0; $ip <= count($id_pemasok); $ip++) {
            if ($id_pemasok[$ip] == "null") {
                return redirect()->back()->with("failed", "Choose Your Supplier ");
            } else {
                break;
            }
        }

        // mengisi var produk
        for ($ipo = 0; $ipo < count($id_produk); $ipo++) {
                $produk[$ipo] = [
                    'id_produk' => $id_produk[$ipo],
                    'unit' => (int) $unit[$ipo],
                ];
        }
        // mengisi arr produk
        $ipdk = 0;
        foreach ($quotation as $quo) {
            $total_unit_produk = 0;
            foreach ($produk as $prdk) {
                if ($prdk['id_produk'] == $quo->id_produk) {
                    $arr_produk[$ipdk] = [
                        'id_produk' => $quo->id_produk,
                        'unit' => $total_unit_produk += $prdk['unit'],
                        'jumlah_unit' => $quo->jumlah_detail_penjualan,
                    ];
                }
            }
            $ipdk++;
        }
        foreach ($arr_produk as $apdk) {
            foreach ($quotation as $quo1) {

                for ($pdkv = 0; $pdkv < count($id_produk); $pdkv++) {
                    if ($produk[$pdkv]['unit'] == null) {
                        return redirect()->back()->with("failed", "Please Fill Unit Coloumn more then 0 ");
                    } else {
                        if ($id_produk[$pdkv] == $apdk['id_produk']) {
                            if ($apdk['unit'] > $quo1->jumlah_detail_penjualan) {
                                return redirect()->back()->with("failed", "Please Fill Unit Coloumn with Less Value");
                            }
                        }
                    }
                }
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

        foreach ($no_pembelian as $np) {
            $no_quotation = explode('-', $tgl_pembelian);
            $no_pembelian = "PO/$np/$no_quotation[0]/$no_quotation[1]/$no_quotation[2]";
            array_push($array_no_pembelian, $no_pembelian);
        }


        // pengisian array data penjualan
        $i=0;
        foreach ($array_no_pembelian as $anp) {



                $data_pembelian[] = [
                    'id_transaksi' => $id_transaksi[$i],
                    'no_pembelian' => $anp,
                    'tgl_pembelian' => $tgl_penjualan
                ];



                $data_detail_pembelian[] = [
                    'id_pembelian' => 0,
                    'id_produk' => $id_produk[$i],
                    'jumlah_detail_pembelian' => $unit[$i],
                ];
            $i++;

        }
       



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

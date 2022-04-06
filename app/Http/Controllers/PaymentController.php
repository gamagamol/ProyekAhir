<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PaymentModel;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;


class PaymentController extends Controller
{
    public $model;
    public function __construct()
    {
        $this->model = new PaymentModel();
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
            'tittle' => 'Payment',
            'data' => $data,
            'deta' => $this->model->index()

        ];
        return view('payment.index', $data);
    }



    public function show($no_penerimaan)
    {
        $no_penerimaan = str_replace("-", "/", $no_penerimaan);
        // dd($no_penerimaan);

        $data = [
            'tittle' => "Create Payment",
            'data' => $this->model->show($no_penerimaan),
        ];
        return view('payment.create', $data);
    }
    public function store(Request $request)
    {

        // persiapan data pembyaran
        $id_transaksi = $request->input('id_transaksi');
        $no_pembayaran = $request->input('no_tagihan');
        $tgl_pembayaran = $request->input('tgl_pembayaran');
        $id_produk = $request->input('id_produk');

        $tgl_tagihan =
            DB::table('tagihan')
            ->select('tgl_tagihan')
            ->where('id_transaksi', "=", $id_transaksi[0])
            ->first();

        $tgl_tagihan = $tgl_tagihan->tgl_tagihan;

        $rules = [
            'tgl_pembayaran' => " after_or_equal:$tgl_tagihan",
        ];
        $message = [
            "tgl_pembayaran.after_or_equal" => "Choose a date after the bill payment date or equal"
        ];
        $validated = Validator::make($request->all(), $rules, $message);
        if ($validated->fails()) {
            return redirect()->back()->with("failed", "Choose a date after the delivery date or equal");
        }


        // persiapan

        $data_pembayaran = [];
        $data_detail_pembayaran = [];
        for ($i = 0; $i < count($id_transaksi); $i++) {
            $data_pembayaran[$i] = [
                'id_transaksi' => $id_transaksi[$i],
                'no_pembayaran' => $no_pembayaran[$i],
                'tgl_pembayaran' => $tgl_pembayaran,


            ];
            $data_detail_pembayaran[$i] = [
                'id_pembayaran' => 0,
                'id_produk' => $id_produk[$i]
            ];
        }
        // dd($id_transaksi);
        $nominal=0;
        if (count($id_transaksi)>1) {
            for ($i=0; $i <count($id_transaksi) ; $i++) { 
                    $id_jurnal = DB::table('jurnal')
                    ->select('nominal')
                    ->where('id_transaksi', "=", $id_transaksi[$i])
                    ->where('kode_akun', "=", 112)
                    ->first();
                    if ($id_jurnal) {
                        $nominal=$id_jurnal->nominal;
                }
            }
        }

       

        $this->model->insert($id_transaksi, $data_pembayaran, $data_detail_pembayaran,$nominal);

        return redirect('payment')->with('success', " Data Entered Successfully Payment number $no_pembayaran[0]");
    }

    public function detail($no_transaksi)
    {
        $no_transaksi = str_replace('-', '/', $no_transaksi);




        $data = [
            'tittle' => 'Payment',
            'data' => $this->model->detail($no_transaksi),

        ];
        return view('payment.detail', $data);
    }
}

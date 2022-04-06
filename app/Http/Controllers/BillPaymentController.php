<?php

namespace App\Http\Controllers;

use App\Models\BillPaymentModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use NumberFormatter;
use Symfony\Component\Console\Helper\FormatterHelper;
use Terbilang;

use function app\helper\penyebut;

class BillPaymentController extends Controller
{
    protected $model;
    public function __construct()
    {
        $this->model = new BillPaymentModel();
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
            'tittle' => "Bill Payment ",
            'data' => $data,
            'deta' => $this->model->index()
        ];
        return view('bill.index', $data);
    }



    public function show($no_pengiriman)
    {

        // $array = $this->model->create($id_transaksi, $tgl_pengiriman);
        // $id_transaksi = $array['id_transaksi'];
        // $data = $array['data'];
        $no_pengiriman= str_replace("-", "/", $no_pengiriman);
        $data = $this->model->show($no_pengiriman);
        // dump($no_pengiriman);
        // dd($data);
        $data = [
            "tittle" => "Bill payment",
            'data' => $data,
        ];
        return view('bill.create', $data);
    }

    public function store(Request $request)
    {
        // prepare data

        $id_transaksi = $request->input('id_transaksi');
        $id_pengiriman = $request->input('id_pengiriman');


        $tgl_pengiriman =
            DB::table('pengiriman')
            ->select('tgl_pengiriman')
            ->where('id_pengiriman', "=", $id_pengiriman[0])
            ->first();

        $tgl_pengiriman = $tgl_pengiriman->tgl_pengiriman;
        $rules = [
            'tgl_pembayaran' => " after_or_equal:$tgl_pengiriman",
        ];
        $message = [
            "tgl_pembayaran.after_or_equal" => "Choose a date after the delivery date or equal"
        ];
        $validated = Validator::make($request->all(), $rules, $message);
        if ($validated->fails()) {
            return redirect()->back()->with("failed", "Choose a date after the delivery date or equal");
        }





        // set no pembayaran
        $no_pengiriman = DB::table('pengiriman')
            ->select('no_pengiriman')
            ->where('id_transaksi', "=", $id_transaksi[0])
            ->first();
        $no_pengiriman = explode("-", $request->input('tgl_pembayaran'));





        $no_tagihan = $this->model->no_tagihan($request->input('tgl_pembayaran'));
        $no_tagihan = "INV/$no_tagihan/$no_pengiriman[0]/$no_pengiriman[1]/$no_pengiriman[2]";
        //  set when table able to insert

        $data_transaksi = [
            'status_transaksi' => "bill",
        ];
        // set table payment able insert
        $data_tagihan = [];
        for ($i = 0; $i < count($id_transaksi); $i++) {
            ${"data_tagihan$i"} = [
                'id_transaksi' => (int)$id_transaksi[$i],
                'id_pengiriman' => (int)$id_pengiriman[$i],
                'no_tagihan' => $no_tagihan,
                'tgl_tagihan' => $request->input('tgl_pembayaran'),

            ];
            $data_tagihan[$i] = ${"data_tagihan$i"};
        }



        $this->model->insert($data_transaksi, $data_tagihan, $id_transaksi);
        return redirect('bill')->with('success', " Data Entered Successfully Inovice number $no_tagihan");
    }


    public function detail($no_tagihan)
    {

        $data = $this->model->detail(str_replace("-", "/", $no_tagihan));
        $data = [
            'tittle' => "Detail Bill Payment",
            'data' => $data
        ];
        return view('bill.detail', $data);
    }

    public function print($no_transaksi)
    {
        $no_transaksi = str_replace('-', '/', $no_transaksi);
        $total = $this->model->detail($no_transaksi);
        $ttl=0;
        foreach ($total as $t) {
          $ttl+= $t->total;
            
        }
      

        $data = [
            'tittle' => "Print INVOICE",
            'data' => $this->model->detail($no_transaksi),
            'total_penyebut' => $total = penyebut($ttl),

        ];
        return view('bill.print', $data);
    }



    public function bill_email($no_transaksi){
        $no_transaksi = str_replace('-', '/', $no_transaksi);
        $total = $this->model->detail($no_transaksi);
        $ttl = 0;
        foreach ($total as $t) {
            $ttl += $t->total;
        }


        $data = [
            'tittle' => "Print INVOICE",
            'data' => $this->model->detail($no_transaksi),
            'total_penyebut' => $total = penyebut($ttl),

        ];
        return $data;
    }
}

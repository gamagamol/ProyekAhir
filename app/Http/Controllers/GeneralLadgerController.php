<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GeneralLadgerModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GeneralLadgerController extends Controller
{
    public $model;
    public function __construct()
    {
        $this->model = new GeneralLadgerModel();
    }
    public function index()
    {
        // persiapan data 
        $data = $this->model->index();
        $saldo_awal = $this->model->saldo_awal();
        $kas = [];
        $revenue = [];
        $piutang = [];
        $ppn = [];
        $ongkir = [];
        $purchase = [];
        $payable = [];
        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]->kode_akun == 111) {
                $kas[$i] = $data[$i];
            }
            if ($data[$i]->kode_akun == 411) {
                $revenue[$i] = $data[$i];
            }
            if ($data[$i]->kode_akun == 112) {
                $piutang[$i] = $data[$i];
            }
            if ($data[$i]->kode_akun == 211) {
                $ppn[$i] = $data[$i];
            }
            if ($data[$i]->kode_akun == 212) {
                $ongkir[$i] = $data[$i];
            }
            if ($data[$i]->kode_akun == 500) {
                $purchase[$i] = $data[$i];
            }
            if ($data[$i]->kode_akun == 200) {
                $payable[$i] = $data[$i];
            }
        }
        $saldo_awal_piutang = $this->model->saldo_awal_piutang();
        if ($saldo_awal->count() == 0) {
            $saldo_awal_kas = 0;
            $saldo_awal_ppn = 0;
            $saldo_awal_penjualan = 0;
            $saldo_awal_ongkir = 0;
            $saldo_awal_pembelian = 0;
            $saldo_awal_utang = 0;
        } else {

            //   persiapan saldo awal
            $saldo_awal_kas = $saldo_awal[0]->saldo_awal;
            $saldo_awal_utang = $saldo_awal[1]->saldo_awal;
            $saldo_awal_ppn = $saldo_awal[2]->saldo_awal;
            $saldo_awal_ongkir = $saldo_awal[3]->saldo_awal;
            $saldo_awal_penjualan = $saldo_awal[4]->saldo_awal;
            $saldo_awal_pembelian = $saldo_awal[5]->saldo_awal;
        }




        $data = [
            'tittle' => "General Ledger",
            'data' => $this->model->index(),
            'kas' => $kas,
            'revenue' => $revenue,
            'piutang' => $piutang,
            'purchase' => $purchase,
            'payable' => $payable,
            'ppn' => $ppn,
            'ongkir' => $ongkir,
            'saldo_awal_kas' => $saldo_awal_kas,
            'saldo_awal_ppn' => $saldo_awal_ppn,
            'saldo_awal_penjualan' => $saldo_awal_penjualan,
            'saldo_awal_ongkir' => $saldo_awal_ongkir,
            'saldo_awal_piutang' => $saldo_awal_piutang,
            'saldo_awal_utang' => $saldo_awal_utang,
            'saldo_awal_pembelian' => $saldo_awal_pembelian,

        ];
        return view("ledger.index", $data);
    }
    public function create()
    {
        $account = $this->model->account();
        $data = [
            'tittle' => " Filter General Ladger",
            'account' => $account
        ];
        return view('ledger.create', $data);
    }
    public function store(Request $request)
    {
        $rules = [
            'kode_akun' => 'required',
            'tgl1' => 'required',
            'tgl2' => 'required|after_or_equal:tgl1',

        ];
        $message = [
            'kode_akun.required' => "The ACCOUNT NAME field is required",
            'tgl1.required' => "The DATE START field is required",
            'tgl2.required' => "The DATE FINISH field is required",
            'tgl2.after_or_equal' => "Choose a date after the start date or equal",


        ];
        $validated = Validator::make($request->all(), $rules, $message);
        if ($validated->fails()) {
            return redirect('ledger/create')->withErrors($validated)->withInput();
        } else {
            $array = [
                'kode_akun' => $request->input('kode_akun'),
                'tgl1' => $request->input('tgl1'),
                'tgl2' => $request->input('tgl2'),
            ];
            return  $this->show($array);
        }
    }


    public function show($array)
    {
        
      
        $data = [
            'tittle' => "General Ladger",
            'kas' => $this->model->show($array),
            'saldo_awal'=>$this->model->saldo_awal_show($array['kode_akun'],$array['tgl1']),
            'kode_akun'=>$array['kode_akun'],
            'tanggal'=>[$array['tgl1'], $array['tgl2'],],
        ];
        // dd($data);

        
       
        
        return view('ledger.show', $data);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\DashboardModel;
use Illuminate\Http\Request;
use App\Models\GeneralLadgerModel;

class DashboardController extends Controller
{
    public $model;
    public function __construct()
    {
        $this->model = new GeneralLadgerModel();
        $this->DM = new DashboardModel();
    }
    public function index()
    {
        // persiapan data 
        $data = $this->model->index();
        $saldo_awal = $this->model->saldo_awal();
        $revenue = [];
        $piutang = [];
        for ($i = 0; $i < count($data); $i++) {

            if ($data[$i]->kode_akun == 411) {
                $revenue[$i] = $data[$i];
            }
            if ($data[$i]->kode_akun == 112) {
                $piutang[$i] = $data[$i];
            }
        }
        $saldo_awal_piutang = $this->model->saldo_awal_piutang();
        if ($saldo_awal->count() == 0) {
            $saldo_awal_penjualan = 0;
        } else {

            //   persiapan saldo awal
            $saldo_awal_penjualan = $saldo_awal[4]->saldo_awal;
        }

        // how make total sales
        $total_sales = 0;
        foreach ($revenue as $r) {
            $total_sales = $saldo_awal_penjualan + $r->nominal;
        }

        // how make total AR

        $total_AR = 0;
        foreach ($piutang as $r) {
            if ($r->posisi_db_cr == 'debit') {
                $total_AR = $saldo_awal_piutang + $r->nominal;
            } elseif ($r->posisi_db_cr == 'kredit') {
                if ($saldo_awal_piutang > 0) {
                    $total_AR = $saldo_awal_piutang - $r->nominal;
                } else {
                    $total_AR = $r->nominal - $r->nominal;
                }
            }
        }


        // membuat persentasi transaksi
        

        $data = [
            'tittle' => "DashBoard",
            'sales' => $total_sales,
            'recivable' => $total_AR,
            'grafik' => $this->DM->grafik(),
            'tagihan'=>round($this->DM->persentase_tagihan()),
        ];
        return view("dashboard.index", $data);
    }

    public  function notif(){
        if ($this->DM->notif()<1) {
            return $array=0;
        }   
        else{

            $array=[
                    'length'=>count($this->DM->notif()),
                    'data'=> $this->DM->notif(),
                ];
                return $array; 
        }
    }

    
}

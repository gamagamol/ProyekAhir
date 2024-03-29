<?php

namespace App\Http\Controllers;

use App\Models\DashboardModel;
use Illuminate\Http\Request;
use App\Models\GeneralLadgerModel;
use App\Models\ReportDetailSalesModel;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public $model;
    public $DM;
    public $RDSM;
    public function __construct()
    {
        $this->model = new GeneralLadgerModel();
        $this->DM = new DashboardModel();
        $this->RDSM = new ReportDetailSalesModel();
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
            $saldo_awal_penjualan = $saldo_awal[3]->saldo_awal;
        }


        $sales =
            DB::select("SELECT sum(subtotal) as sales from transaksi
        join pelanggan on transaksi.id_pelanggan=pelanggan.id_pelanggan
        join penjualan on penjualan.id_transaksi=transaksi.id_transaksi
        join tagihan on tagihan.id_transaksi=transaksi.id_transaksi");

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
        $persentase_pembayaran = $this->DM->persentase_tagihan();
        if ($persentase_pembayaran) {
            $tagihan = round($persentase_pembayaran[0]->persentase_pembayaran, 2);
        } else {
            $tagihan = 0;
        }

        // menghitung total utang

        $total_utang =  DB::select("select (
            select sum(total_detail_pembelian) as total_pembayaran_vendor from pembelian 
            join detail_transaksi_pembelian on pembelian.id_pembelian=detail_transaksi_pembelian.id_pembelian
            left join pembayaranvendor on pembelian.id_pembelian=pembayaranvendor.id_pembelian where idpembayaranvendor is  null
            ) - (
            select sum(total_detail_pembelian) as total_pembayaran_vendor from pembelian 
            join detail_transaksi_pembelian on pembelian.id_pembelian=detail_transaksi_pembelian.id_pembelian
            left join pembayaranvendor on pembelian.id_pembelian=pembayaranvendor.id_pembelian where idpembayaranvendor is not null
            )  total_utang
            from pembelian
            limit 1
            ");

        if ($total_utang) {
            $payable =
                $total_utang[0]->total_utang;
        } else {
            $payable = 0;
        }

        $data = [
            'tittle' => "DashBoard",
            'sales' => $sales[0]->sales,
            'recivable' => DB::table('transaksi')->where('status_transaksi', '=', 'bill')->sum('total'),
            'grafik' => $this->DM->grafik(),
            'tagihan' => $tagihan,
            'payable' => $payable
        ];
        // dd($data);
        return view("dashboard.index", $data);
    }

    public  function notif()
    {
        if ($this->DM->notif() < 1) {
            return $array = 0;
        } else {

            $array = [
                'length' => count($this->DM->notif()),
                'data' => $this->DM->notif(),
            ];

            // dd($array);
            return $array;
        }
    }
}

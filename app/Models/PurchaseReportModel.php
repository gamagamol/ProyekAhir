<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PurchaseReportModel extends Model
{
    use HasFactory;
    public function index()
    {
        return DB::table('transaksi')
            ->selectRaw('nama_pemasok,no_pembelian,tgl_pembelian, DATE_ADD(tgl_pembelian, INTERVAL 45 DAY) AS DUE_DATE,subtotal')
            ->join('pemasok',"transaksi.id_pemasok","=","pemasok.id_pemasok")
            ->join('pembelian',"pembelian.id_transaksi","=","transaksi.id_transaksi")
            ->paginate(5);
    }
    public function total()
    {
        return DB::table('transaksi')
            ->selectRaw('sum(total) as total')
            ->join('penawaran', 'penawaran.id_transaksi', '=', 'transaksi.id_transaksi')
            ->groupBy('tgl_penawaran')
            ->get();
    }
}

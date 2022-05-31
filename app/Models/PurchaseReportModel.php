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
            ->selectRaw('nama_pemasok,no_pembelian,tgl_pembelian,no_pembayaran_vendor,tgl_pembayaran_vendor,sum(subtotal_detail_pembelian) as subtotal_detail_pembelian,DATE_ADD(tgl_pembelian, INTERVAL 45 DAY) AS DUE_DATE')
            ->join('pemasok', 'pemasok.id_pemasok', 'transaksi.id_pemasok')
            ->join('penjualan', 'penjualan.id_transaksi', 'transaksi.id_transaksi')
            ->join('pembelian', 'pembelian.id_penjualan', 'penjualan.id_penjualan')
            ->join('pembayaranvendor', 'pembelian.id_pembelian', 'pembayaranvendor.id_pembelian')
            ->join('detail_transaksi_pembelian', 'pembelian.id_pembelian', 'detail_transaksi_pembelian.id_pembelian')
            ->groupBy('no_pembelian')
            ->get();
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

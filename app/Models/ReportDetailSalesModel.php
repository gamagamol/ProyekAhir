<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReportDetailSalesModel extends Model
{
    use HasFactory;
    public function index()
    {
        // return DB::table('transaksi')
        //     ->selectRaw('DISTINCT nama_pelanggan,no_penjualan,no_tagihan,tgl_tagihan,subtotal,total,sum(total) as total_keseluruhan')
        //     ->join('penjualan', 'penjualan.id_transaksi', "=", 'transaksi.id_transaksi')
        //     ->join("tagihan", "tagihan.id_transaksi", "=", "transaksi.id_transaksi")
        //     ->join("pelanggan", "transaksi.id_pelanggan", "=", "pelanggan.id_pelanggan")
        //     ->groupBy('transaksi.id_pelanggan')

        //     ->get();


        return DB::table('penjualan')
            ->selectRaw(' pelanggan.id_pelanggan,nama_pelanggan,no_penjualan,tagihan.no_tagihan,tagihan.tgl_tagihan,transaksi.subtotal,transaksi.total')
            ->join("transaksi", "penjualan.id_transaksi", "=", "transaksi.id_transaksi")
            ->join("tagihan", "tagihan.id_transaksi", "=", "transaksi.id_transaksi")
            ->join("pelanggan", "transaksi.id_pelanggan", "=", "pelanggan.id_pelanggan")
            ->whereIn('pelanggan.id_pelanggan', function ($query) {
                $query->select('id_pelanggan')
                    ->from('pelanggan');
            })
            ->distinct()
            ->get()
            ->toArray();
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

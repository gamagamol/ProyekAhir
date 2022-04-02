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




    public function status_transaki($no_penawaran = null)
    {
        if ($no_penawaran) {
            return   DB::table('transaksi')
                ->selectRaw('kode_transaksi,no_penawaran,no_penjualan,no_pembelian,no_penerimaan,no_pengiriman,no_tagihan,no_pembayaran,nama_pelanggan,nama_pengguna ')
                ->leftJoin('penawaran', 'penawaran.id_transaksi', '=', 'transaksi.id_transaksi')
                ->leftJoin('penjualan', 'penjualan.id_transaksi', '=', 'transaksi.id_transaksi')
                ->leftJoin('pembelian', 'pembelian.id_transaksi', '=', 'transaksi.id_transaksi')
                ->leftJoin('penerimaan_barang', 'penerimaan_barang.id_transaksi', '=', 'transaksi.id_transaksi')
                ->leftJoin('pengiriman', 'pengiriman.id_transaksi', '=', 'transaksi.id_transaksi')
                ->leftJoin('tagihan', 'tagihan.id_transaksi', '=', 'transaksi.id_transaksi')
                ->leftJoin('pembayaran', 'pembayaran.id_transaksi', '=', 'transaksi.id_transaksi')
                ->leftJoin('pelanggan', 'transaksi.id_pelanggan', '=', 'pelanggan.id_pelanggan')
                ->leftJoin('pengguna', 'pengguna.id', '=', 'transaksi.id')
                ->groupBy('kode_transaksi')
                ->having('no_penawaran','=',$no_penawaran)
                ->paginate(5);
        } else {

            return   DB::table('transaksi')
                ->selectRaw('kode_transaksi,no_penawaran,no_penjualan,no_pembelian,no_penerimaan,no_pengiriman,no_tagihan,no_pembayaran,nama_pelanggan,nama_pengguna ')
                ->leftJoin('penawaran', 'penawaran.id_transaksi', '=', 'transaksi.id_transaksi')
                ->leftJoin('penjualan', 'penjualan.id_transaksi', '=', 'transaksi.id_transaksi')
                ->leftJoin('pembelian', 'pembelian.id_transaksi', '=', 'transaksi.id_transaksi')
                ->leftJoin('penerimaan_barang', 'penerimaan_barang.id_transaksi', '=', 'transaksi.id_transaksi')
                ->leftJoin('pengiriman', 'pengiriman.id_transaksi', '=', 'transaksi.id_transaksi')
                ->leftJoin('tagihan', 'tagihan.id_transaksi', '=', 'transaksi.id_transaksi')
                ->leftJoin('pembayaran', 'pembayaran.id_transaksi', '=', 'transaksi.id_transaksi')
                ->leftJoin('pelanggan', 'transaksi.id_pelanggan', '=', 'pelanggan.id_pelanggan')
                ->leftJoin('pengguna', 'pengguna.id', '=', 'transaksi.id')
                ->groupBy('kode_transaksi')
                ->paginate(5);
        }
    }
}

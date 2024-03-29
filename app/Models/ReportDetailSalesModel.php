<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReportDetailSalesModel extends Model
{
    use HasFactory;
    public function index($search=null)
    {

        if($search){
            $query="and  pelanggan.id_pelanggan = '$search'";
        }else{
            $query="";
        }
        

        return DB::select("SELECT tgl_tagihan,nama_pelanggan,no_penjualan,no_tagihan,subtotal from transaksi
        join pelanggan on transaksi.id_pelanggan=pelanggan.id_pelanggan
        join penjualan on penjualan.id_transaksi=transaksi.id_transaksi
        join tagihan on tagihan.id_transaksi=transaksi.id_transaksi
        where transaksi.tidak_terpakai=0 
        $query
        ");
    }

    public function total()
    {
        return DB::table('transaksi')
            ->selectRaw('sum(total) as total')
            ->join('penawaran', 'penawaran.id_transaksi', '=', 'transaksi.id_transaksi')
            ->groupBy('tgl_penawaran')
            ->get();
    }




    public function status_transaki($no_penjualan = null)
    {
        if ($no_penjualan) {
            return   DB::table('transaksi')
                ->selectRaw('kode_transaksi,no_penawaran,no_penjualan,no_pembelian,no_penerimaan,no_pengiriman,no_tagihan,no_pembayaran,nama_pelanggan,nama_pengguna ,tgl_penjualan')
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
                ->having('no_penjualan', '=', $no_penjualan)
                ->get();
        } else {

            return   DB::table('transaksi')
                ->selectRaw('kode_transaksi,no_penawaran,no_penjualan,no_pembelian,no_penerimaan,no_pengiriman,no_tagihan,no_pembayaran,nama_pelanggan,nama_pengguna ,tgl_penawaran ')
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
                ->get();
        }
    }
}

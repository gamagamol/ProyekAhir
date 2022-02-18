<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DashboardModel extends Model
{
    use HasFactory;
    public function grafik()
    {
        return DB::table('transaksi')
            ->selectRaw(" 
                        round (CAST(((SELECT DISTINCT COUNT(kode_transaksi) FROM transaksi WHERE status_transaksi='quotation')/(SELECT DISTINCT COUNT(kode_transaksi) FROM transaksi)*100) as float)) as quotation, 
                        round (CAST(((SELECT DISTINCT COUNT(kode_transaksi) FROM transaksi WHERE status_transaksi='sales')/(SELECT DISTINCT COUNT(kode_transaksi) FROM transaksi)*100) as float)) as sales ,
                        round (CAST(((SELECT DISTINCT COUNT(kode_transaksi) FROM transaksi WHERE status_transaksi='purchase')/(SELECT DISTINCT COUNT(kode_transaksi) FROM transaksi)*100) as float)) as purchase ,
                        round (CAST(((SELECT DISTINCT COUNT(kode_transaksi) FROM transaksi WHERE status_transaksi='goods')/(SELECT DISTINCT COUNT(kode_transaksi) FROM transaksi)*100) as float)) as goods ,
                        round (CAST(((SELECT DISTINCT COUNT(kode_transaksi) FROM transaksi WHERE status_transaksi='delivery')/(SELECT DISTINCT COUNT(kode_transaksi) FROM transaksi)*100) as float)) as delivery ,
                        round (CAST(((SELECT DISTINCT COUNT(kode_transaksi) FROM transaksi WHERE status_transaksi='bill')/(SELECT DISTINCT COUNT(kode_transaksi) FROM transaksi)*100) as float)) as bill ,
                        round (CAST(((SELECT DISTINCT COUNT(kode_transaksi) FROM transaksi WHERE status_transaksi='payment')/(SELECT DISTINCT COUNT(kode_transaksi) FROM transaksi)*100) as float)) as payment 
                        ")
            ->first();
    }

    public function persentase_tagihan()
    {
        $tagihan = DB::table('tagihan')
            ->selectRaw('count(id_transaksi) as id')
            ->whereBetween('tgl_tagihan', ['2022-01-01', '2022-12-31'])
            ->first();

        $pembayaran = DB::table('pembayaran')
            ->selectRaw('count(id_transaksi) as id')
            ->whereBetween('tgl_pembayaran', ['2022-01-01', '2022-12-31'])
            ->first();

        return (int)$pembayaran->id / $tagihan->id * 100;
    }

    public function notif()
    {
        $ar = DB::table('transaksi')
            ->selectRaw("tgl_tagihan,nama_pelanggan,no_tagihan,berat,total,layanan,nama_pengguna, DATE_ADD(tgl_tagihan, INTERVAL 31 DAY) AS DUE_DATE,no_pengiriman,kode_transaksi")
            ->join("pelanggan", "transaksi.id_pelanggan", "=", "pelanggan.id_pelanggan")
            ->join("pengguna", "transaksi.id", "=", "pengguna.id")
            ->join('penawaran', "penawaran.id_transaksi", "=", "transaksi.id_transaksi")
            ->join('pengiriman', "pengiriman.id_transaksi", "=", "transaksi.id_transaksi")
            ->join('detail_transaksi_pengiriman', "detail_transaksi_pengiriman.id_pengiriman", "=", "pengiriman.id_pengiriman")
            ->join('detail_transaksi_penawaran', "detail_transaksi_penawaran.id_penawaran", "=", "penawaran.id_penawaran")
            ->join("produk", "detail_transaksi_pengiriman.id_produk", "=", "produk.id_produk")
            ->join("tagihan", "tagihan.id_transaksi", "=", "transaksi.id_transaksi")
            ->where('status_transaksi', "=", "bill")
            ->groupBy('tgl_tagihan', "no_tagihan")
            ->orderBy('DUE_DATE', 'asc')
            ->limit(2)
            ->get()
            ->toArray();
        
           $ap = DB::table('transaksi')
            ->selectRaw('nama_pemasok,no_pembelian,tgl_pembelian, DATE_ADD(tgl_pembelian, INTERVAL 45 DAY) AS DUE_DATE,subtotal')
            ->join('penawaran', 'penawaran.id_transaksi', '=', 'transaksi.id_transaksi')
            ->join('detail_transaksi_penawaran', 'detail_transaksi_penawaran.id_penawaran', '=', 'penawaran.id_penawaran')
            ->join("produk", 'detail_transaksi_penawaran.id_produk', '=', 'produk.id_produk')
            ->join("pelanggan", 'transaksi.id_pelanggan', '=', 'pelanggan.id_pelanggan')
            ->join("pemasok", 'transaksi.id_pemasok', '=', 'pemasok.id_pemasok')
            ->join("pengguna", 'transaksi.id', '=', 'pengguna.id')
            ->join('penjualan', "penjualan.id_transaksi", "transaksi.id_transaksi")
            ->join('pembelian', "pembelian.id_transaksi", "transaksi.id_transaksi")
            ->whereNotIn('id_pembelian', function ($query) {
                $query->select('id_pembelian')
                    ->from('pembayaranvendor');
            })
            ->groupBy('no_pembelian')
            ->orderBy("DUE_DATE","asc")
            ->limit(2)
            ->get()
            ->toArray();

            $array=array_merge($ap,$ar);
            return $array;

    }
}

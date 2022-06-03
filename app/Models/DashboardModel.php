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
        return DB::select("SELECT 
            distinct (select 
            count(kode_transaksi) 
            from transaksi where status_transaksi='payment') / (select 
            count(kode_transaksi) 
            from transaksi ) *100 AS persentase_pembayaran
            from transaksi");
    }

    public function notif()
    {


        $ap = DB::table('transaksi')
            ->selectRaw('Datediff( CURDATE(),tgl_pembelian) ,no_pembelian as no_transaksi,tgl_pembelian, DATE_ADD(tgl_pembelian, INTERVAL 45 DAY) AS DUE_DATE,subtotal')
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
            ->whereRaw('Datediff( CURDATE(),tgl_pembelian) >= 30')
            ->groupBy('no_pembelian')
            ->orderBy("DUE_DATE", "asc")
            ->limit(2)
            ->get()
            ->toArray();

        $ar = DB::select("SELECT no_tagihan as no_transaksi,tgl_tagihan,  DATE_ADD(tgl_tagihan, INTERVAL 31 DAY) AS DUE_DATE,nama_pelanggan, sum(total) as total,abs( Datediff( CURDATE(),tgl_tagihan) )as selisih, 
           sum( total) AS total_selisih from transaksi 
           join pelanggan on transaksi.id_pelanggan = pelanggan.id_pelanggan
            join penjualan on penjualan.id_transaksi=transaksi.id_transaksi
            join pembelian on pembelian.id_penjualan=penjualan.id_penjualan
            join penerimaan_barang on penerimaan_barang.id_pembelian = pembelian.id_pembelian
             join  pengiriman on pengiriman.id_penerimaan_barang = penerimaan_barang.id_penerimaan_barang 
          join  detail_transaksi_pengiriman on detail_transaksi_pengiriman.id_pengiriman =  pengiriman.id_pengiriman
      join tagihan on pengiriman.id_pengiriman = tagihan.id_pengiriman
            where status_transaksi='bill   '
            group by no_tagihan
            order by tgl_tagihan desc
            limit 1
           ");

        $array = array_merge($ap, $ar);
        return $array;
        //  return $ap;
    }
}

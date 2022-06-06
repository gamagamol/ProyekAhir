<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\TextUI\XmlConfiguration\Group;

class AgingScheduleModel extends Model
{
    use HasFactory;
    public function index($id_pelanggan = null)
    {
       

<<<<<<< HEAD
        return DB::select("select no_tagihan,tgl_tagihan,  DATE_ADD(tgl_tagihan, INTERVAL 31 DAY) AS DUE_DATE,nama_pelanggan, sum(total) as total,abs( Datediff( CURDATE(),tgl_tagihan) )as selisih, 
           sum( total) AS total_selisih from transaksi 
           join pelanggan on transaksi.id_pelanggan = pelanggan.id_pelanggan
         
            join penjualan on penjualan.id_transaksi=transaksi.id_transaksi
            join pembelian on pembelian.id_penjualan=penjualan.id_penjualan
            join penerimaan_barang on penerimaan_barang.id_pembelian = pembelian.id_pembelian
             join  pengiriman on pengiriman.id_penerimaan_barang = penerimaan_barang.id_penerimaan_barang 
          join  detail_transaksi_pengiriman on detail_transaksi_pengiriman.id_pengiriman =  pengiriman.id_pengiriman
      join tagihan on pengiriman.id_pengiriman = tagihan.id_pengiriman
         
            where status_transaksi='bill'
            group by no_tagihan
            order by tgl_tagihan desc
         
           
           ");
=======
        return DB::select("SELECT  no_tagihan,tgl_tagihan, DATE_ADD(tgl_tagihan, INTERVAL 31 DAY) AS DUE_DATE,nama_pelanggan, sum(total) as total, 
        Datediff( tgl_tagihan,tgl_tagihan) as selisih,tgl_tagihan, 
        sum( total) AS total_selisih from transaksi 
         join penawaran on penawaran.id_transaksi = transaksi.id_transaksi    
        join penjualan on  penjualan.id_transaksi=transaksi.id_transaksi
        join pembelian on penjualan.id_penjualan=pembelian.id_penjualan
        join detail_transaksi_pembelian on pembelian.id_pembelian=detail_transaksi_pembelian.id_pembelian
        join penerimaan_barang on penerimaan_barang.id_pembelian=pembelian.id_pembelian
        join detail_penerimaan_barang on detail_penerimaan_barang.id_penerimaan_barang = penerimaan_barang.id_penerimaan_barang
       join pengiriman on pengiriman.id_penerimaan_barang=penerimaan_barang.id_penerimaan_barang
        join detail_transaksi_pengiriman on detail_transaksi_pengiriman.id_pengiriman=pengiriman.id_pengiriman 
        join tagihan on tagihan.id_pengiriman=pengiriman.id_pengiriman
        join produk on detail_penerimaan_barang.id_produk = produk.id_produk
        join pelanggan on pelanggan.id_pelanggan=transaksi.id_pelanggan
        where status_transaksi='bill'
        
        group by no_tagihan
        ");
>>>>>>> wandi
    }
}

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
    }
}

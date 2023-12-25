<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Exception;



class GeneralReport extends Model
{
    use HasFactory;

    public function omzetReport($year_month = null, $date = null, $date_to = null)
    {

        $query = '';


        $month = '';
        $year = '';


        if ($year_month != 0) {
            $year_month = explode('-', $year_month);

            $year = $year_month[0];
            $month = $year_month[1];
        }



        if ($year_month && $date == null) {


            $query = "AND YEAR(tgl_penawaran)='$year'AND MONTH(tgl_penawaran)=$month";
        } elseif ($date && $year_month == null && $date_to == null) {

            $query = "AND tgl_penawaran='$date'";
        } elseif ($year_month && $date && $date_to == null) {
            $query = "AND YEAR(tgl_penawaran)='$year'AND MONTH(tgl_penawaran)=$month and tgl_penawaran=$date";
        } else if ($date && $date_to) {


            $query = "AND tgl_penawaran between '$date' and '$date_to'";
        } else if ($date && $date_to && $year_month) {

            $query = "AND YEAR(tgl_penawaran)='$year'AND MONTH(tgl_penawaran)=$month AND tgl_penawaran between '$date' and '$date_to'";
        }
     

        $data =  DB::select("
            SELECT transaksi.id_transaksi, no_penawaran,tgl_penawaran,no_penjualan,tgl_penjualan,nama_pelanggan,
            no_pembelian,tgl_pembelian,nama_pemasok,no_pengiriman,tgl_pengiriman,no_tagihan,tgl_tagihan,
            no_pembayaran,tgl_pembayaran,transaksi.subtotal ,transaksi.ppn,transaksi.total,subtotal_detail_pembelian,ppn_detail_pembelian,total_detail_pembelian,nama_pegawai
            FROM transaksi
			join penawaran on penawaran.id_transaksi = transaksi.id_transaksi
            join penjualan on  penjualan.id_transaksi=transaksi.id_transaksi
            left join pembelian on pembelian.id_penjualan=penjualan.id_penjualan
			left join detail_transaksi_pembelian on detail_transaksi_pembelian.id_pembelian=pembelian.id_pembelian
            left join penerimaan_barang on penerimaan_barang.id_pembelian=pembelian.id_pembelian
            left join pengiriman on penerimaan_barang.id_penerimaan_barang=pengiriman.id_penerimaan_barang
			left join tagihan on tagihan.id_pengiriman=pengiriman.id_pengiriman
            left join pembayaran on pembayaran.id_transaksi=transaksi.id_transaksi
            join pelanggan on transaksi.id_pelanggan = pelanggan.id_pelanggan
            join pegawai on transaksi.id_pegawai = pegawai.id_pegawai
            join pemasok on pembelian.id_pemasok = pemasok.id_pemasok
            where penawaran.tidak_terpakai=0 $query
            order by tgl_penawaran desc");

           

        return $data;
       
    }
}

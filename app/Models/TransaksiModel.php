<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class TransaksiModel extends Model
{
    use HasFactory;


    public function getTransaksi($id_transaksi = null)
    {

        if ($id_transaksi) {
            return DB::table('transaksi')->where('id_transaksi', '=', $id_transaksi)->first();
        }
        return DB::table('transaksi')->get();
    }

    public function transactionNumberTracking($date = null)
    {

        $where = '';
       
            
        if ($date) {
            $date = explode('-', $date);
            $where = "where YEAR(tgl_penjualan)='$date[0]' and MONTH(tgl_penjualan) ='$date[1]'";
        }

        $penjualan =DB::select("SELECT DISTINCT tgl_penjualan,no_penjualan FROM penjualan $where");
   

        $transactionNumbersArr = [];



        $transactionNumbers = DB::select("select tgl_penawaran,no_penawaran,
                tgl_penjualan,no_penjualan,
                tgl_pembelian,no_pembelian,
                tgl_penerimaan,no_penerimaan,
                tgl_pengiriman,no_pengiriman,
                tgl_tagihan,no_tagihan,
                tgl_pembayaran,no_pembayaran
                from transaksi 
                left join penawaran on transaksi.id_transaksi = penawaran.id_transaksi
                left join penjualan on transaksi.id_transaksi = penjualan.id_transaksi
                left join pembelian on pembelian.id_penjualan=penjualan.id_penjualan
                left join penerimaan_barang on penerimaan_barang.id_pembelian=pembelian.id_pembelian
                left join pengiriman on pengiriman.id_penerimaan_barang=penerimaan_barang.id_penerimaan_barang
                left join tagihan on tagihan.id_pengiriman=pengiriman.id_pengiriman
                left join pembayaran on pembayaran.id_transaksi=transaksi.id_transaksi
                $where
                ");



        foreach ($penjualan as $t) {
            $arr = [];

            // $arr['tgl_penawaran'] = $t->tgl_penawaran;
            // $arr['no_penawaran'] = $t->no_penawaran;

            $arr['tgl_penjualan'] = $t->tgl_penjualan;
            $arr['no_penjualan'] = $t->no_penjualan;
            $arr['pembelian'] = [];
            $arr['penerimaan'] = [];
            $arr['pengiriman'] = [];
            $arr['tgl_pembelian'] = [];
            $arr['tgl_penerimaan'] = [];
            $arr['tgl_pengiriman'] = [];

            foreach ($transactionNumbers as $tpembelian) {
                if ($t->no_penjualan == $tpembelian->no_penjualan) {

                    array_push($arr['tgl_pembelian'], $tpembelian->tgl_pembelian);
                    array_push($arr['pembelian'], $tpembelian->no_pembelian);
                }
            }


            foreach ($transactionNumbers as $tpenerimaan) {

                if ($t->no_penjualan == $tpenerimaan->no_penjualan) {
                    array_push($arr['tgl_penerimaan'], $tpenerimaan->tgl_penerimaan);
                    array_push($arr['penerimaan'], $tpenerimaan->no_penerimaan);
                }
            }

            foreach ($transactionNumbers as $tpengiriman) {
                if ($t->no_penjualan == $tpengiriman->no_penjualan) {
                    array_push($arr['tgl_pengiriman'], $tpengiriman->tgl_pengiriman);
                    array_push($arr['pengiriman'], $tpengiriman->no_pengiriman);
                }
            }

            foreach ($transactionNumbers as $ttagihan) {
                if ($t->no_penjualan == $ttagihan->no_penjualan) {
                    $arr['tgl_tagihan'] = $ttagihan->tgl_tagihan;
                    $arr['tagihan'] = $ttagihan->no_tagihan;
                }
            }
            foreach ($transactionNumbers as $tpembayaran) {
                if ($t->no_penjualan == $tpembayaran->no_penjualan) {
                    $arr['tgl_pembayaran'] = $tpembayaran->tgl_pembayaran;
                    $arr['pembayaran'] = $tpembayaran->no_pembayaran;
                }
            }



            array_push($transactionNumbersArr, $arr);
        }

        return $transactionNumbersArr;
    }
}

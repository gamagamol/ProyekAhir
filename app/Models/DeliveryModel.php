<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Js;

class DeliveryModel extends Model
{
    use HasFactory;

    public function index($id = null)
    {

        if ($id) {
            $query = "  where  no_pengiriman = '$id'";
        } else {
            $query = "";
        }
        return DB::select("
        select pi.tgl_pengiriman,pi.no_pengiriman,t.nomor_pekerjaan,nama_pelanggan,nama_pengguna,t.status_transaksi from pengiriman pi
        join transaksi t on t.id_transaksi = pi.id_transaksi
        join penerimaan_barang pb on pb.id_penerimaan_barang = pi.id_penerimaan_barang
        join pelanggan pl on pl.id_pelanggan = t.id_pelanggan
        join pengguna pg on pg.id = t.id
        left join detail_penerimaan_barang dtb on dtb.id_penerimaan_barang = pb.id_penerimaan_barang
            $query
            group by no_pengiriman
            ");
    }

    public function show($no_penerimaan)
    {

        $no_pengiriman =
            DB::select("SELECT distinct no_pengiriman FROM penerimaan_barang
            join detail_penerimaan_barang on detail_penerimaan_barang.id_penerimaan_barang= penerimaan_barang.id_penerimaan_barang
            left join pengiriman on pengiriman.id_penerimaan_barang =penerimaan_barang.id_penerimaan_barang
            where no_penerimaan='$no_penerimaan'");


        if ($no_pengiriman[0]->no_pengiriman == null) {
            $query = "and jumlah_detail_penerimaan >ifnull( jumlah_detail_pengiriman,0)  
             group by transaksi.id_transaksi ";
        } else {
            $query = " and  jumlah_detail_penerimaan >ifnull( jumlah_detail_pengiriman,0)
            group by transaksi.id_transaksi
            having jumlah_detail_penerimaan > ifnull( sudah_terkirim,0)";
        }

        return DB::select(
            "SELECT   *,penerimaan_barang.no_penerimaan,no_pengiriman, transaksi.id_transaksi , penjualan.id_penjualan ,
            penerimaan_barang.id_penerimaan_barang ,penawaran.id_penawaran,jumlah_detail_penerimaan,
            case 
            when jumlah_detail_pengiriman > 0 then sum(jumlah_detail_pengiriman)
            end as
            sudah_terkirim,
            jumlah_detail_pengiriman,
            sisa_detail_pengiriman ,detail_penerimaan_barang.id_produk FROM transaksi 
            join penawaran on penawaran.id_transaksi = transaksi.id_transaksi
            join penjualan on  penjualan.id_transaksi=transaksi.id_transaksi
            join pembelian on pembelian.id_penjualan=penjualan.id_penjualan
            join penerimaan_barang on penerimaan_barang.id_pembelian=pembelian.id_pembelian
            join detail_penerimaan_barang on detail_penerimaan_barang.id_penerimaan_barang = penerimaan_barang.id_penerimaan_barang
            left join pengiriman on pengiriman.id_penerimaan_barang = penerimaan_barang.id_penerimaan_barang
            left join detail_transaksi_pengiriman on detail_transaksi_pengiriman.id_pengiriman=pengiriman.id_pengiriman 
            join pelanggan on pelanggan.id_pelanggan=transaksi.id_pelanggan
            join pengguna on pengguna.id=transaksi.id
            join produk on detail_penerimaan_barang.id_produk=produk.id_produk
			where no_penerimaan='$no_penerimaan'
            $query
            "
        );
    }
    public function data($id_transaksi)
    {

        $data = [];
        for ($i = 0; $i <= count($id_transaksi) - 1; $i++) {

            $date = DB::table('transaksi')
                ->selectRaw('transaksi.id_transaksi,penjualan.no_penjualan,penjualan.id_penjualan,id_produk,tgl_penjualan')
                ->join("penjualan", "penjualan.id_transaksi", "=", "transaksi.id_transaksi")
                ->join("detail_transaksi_penjualan", "detail_transaksi_penjualan.id_penjualan", "=", "penjualan.id_penjualan")
                ->where('transaksi.id_transaksi', "=", $id_transaksi[$i])
                ->get();
            $data[] = $date[0];
        }
        return $data;
    }

    public function no_delivery($tgl_pengiriman, $unit)
    {
        //  menentukan no delivery
        if ($unit) {
            $arr_no_pengiriman = [];
            for ($i = 0; $i < count($unit); $i++) {

                $no_pengiriman =
                    DB::table('pengiriman')
                    ->selectRaw("DISTINCT ifnull(max(substring(no_pengiriman,4,1)),0) +1 as no_pengiriman")
                    ->where("tgl_pengiriman", "=", $tgl_pengiriman)
                    ->first();
                $no_pengiriman = (int)$no_pengiriman->no_pengiriman;
                $no_pengiriman += $i;
                array_push($arr_no_pengiriman, $no_pengiriman);
            }

            return $arr_no_pengiriman;
        } else {
            $no_pengiriman =
                DB::table('pengiriman')
                ->selectRaw("DISTINCT ifnull(max(substring(no_pengiriman,4,1)),0) +1 as no_pengiriman")
                ->where("tgl_pengiriman", "=", $tgl_pengiriman)
                ->first();
            $no_pengiriman = (int)$no_pengiriman->no_pengiriman;
            return $no_pengiriman;
        }
    }

    public function insert_delivery($id_transaksi, $data_pengirimian, $data_detail_pengiriman, $unit)
    {
        // mengubah data transaksi

        for ($i = 0; $i <= count($id_transaksi) - 1; $i++) {
            DB::table('transaksi')
                ->where("id_transaksi", "=", $id_transaksi[$i])
                ->update(['status_transaksi' => "delivery"]);
        }

        // insert data transaksi

        DB::table('pengiriman')->insert($data_pengirimian);


        // perispan data detail transaksi penjualan


        if ($unit) {
            for ($i = 0; $i < count($data_detail_pengiriman); $i++) {

                $id_pengiriman = DB::table('pengiriman')
                    ->select('id_pengiriman')
                    ->where('id_transaksi', "=", $id_transaksi[$i])
                    ->get();
                $id_pengiriman = json_decode(json_encode($id_pengiriman), true);
                $id_pengiriman = end($id_pengiriman);
                $data_detail_pengiriman[$i]['id_pengiriman'] = $id_pengiriman['id_pengiriman'];
            }
            // dd($data_detail_pengiriman);
            //     insert data detail penjualan transaksi
            DB::table('detail_transaksi_pengiriman')->insert($data_detail_pengiriman);
        } else {
            for ($i = 0; $i < count($data_detail_pengiriman); $i++) {
                $id_pengiriman = DB::table('pengiriman')
                    ->select('id_pengiriman')
                    ->where('id_transaksi', "=", $id_transaksi[$i])
                    ->get();
                $id_pengiriman = json_decode(json_encode($id_pengiriman), true);
                $id_pengiriman = end($id_pengiriman);
                $data_detail_pengiriman[$i]['id_pengiriman'] = $id_pengiriman['id_pengiriman'];
            }

            //     insert data detail penjualan transaksi
            DB::table('detail_transaksi_pengiriman')->insert($data_detail_pengiriman);
        }
    }

    public function detail($no_pengiriman)
    {


        return  DB::select(
            "SELECT * FROM transaksi
             join penawaran on penawaran.id_transaksi = transaksi.id_transaksi
            join penjualan on  penjualan.id_transaksi=transaksi.id_transaksi
            join pembelian on pembelian.id_penjualan=penjualan.id_penjualan
			join penerimaan_barang on penerimaan_barang.id_pembelian=pembelian.id_pembelian
            join detail_penerimaan_barang on detail_penerimaan_barang.id_penerimaan_barang = penerimaan_barang.id_penerimaan_barang
            join pengiriman on penerimaan_barang.id_penerimaan_barang=pengiriman.id_penerimaan_barang
            join detail_transaksi_pengiriman on detail_transaksi_pengiriman.id_pengiriman=pengiriman.id_pengiriman
            join produk on detail_penerimaan_barang.id_produk = produk.id_produk
			join pelanggan on transaksi.id_pelanggan = pelanggan.id_pelanggan
            join pemasok on pembelian.id_pemasok = pemasok.id_pemasok
            where no_pengiriman ='$no_pengiriman' and transaksi.tidak_terpakai=0
            Order BY tgl_pengiriman asc,no_pengiriman asc

        
        "


        );
    }


    public function edit($no_penerimaan)
    {
        // dd($no_penerimaan);

        $no_pengiriman =
            DB::select("SELECT distinct no_pengiriman FROM penerimaan_barang
            join detail_penerimaan_barang on detail_penerimaan_barang.id_penerimaan_barang= penerimaan_barang.id_penerimaan_barang
            left join pengiriman on pengiriman.id_penerimaan_barang =penerimaan_barang.id_penerimaan_barang
            
            where no_penerimaan='$no_penerimaan'");


        for ($i = 0; $i < count($no_pengiriman); $i++) {
            if ($no_pengiriman[$i]->no_pengiriman) {
                $query = " and jumlah_detail_penerimaan >= ifnull( jumlah_detail_pengiriman,0) ";
                break;
            } else {
                $query = " and jumlah_detail_penerimaan > ifnull( jumlah_detail_pengiriman,0)";
            }
        }
      

        return DB::select(
            "SELECT  *,penerimaan_barang.no_penerimaan,no_pengiriman, transaksi.id_transaksi , penjualan.id_penjualan ,penerimaan_barang.id_penerimaan_barang ,penawaran.id_penawaran, jumlah_detail_penerimaan,
         jumlah_detail_pengiriman as jumlah_detail_pengiriman,jumlah_detail_penerimaan- jumlah_detail_pengiriman as sisa_detail_penerimaan
            ,tgl_penerimaan,detail_penerimaan_barang.id_produk,harga,pembelian.id_pembelian FROM transaksi 
         join penawaran on penawaran.id_transaksi = transaksi.id_transaksi    
        join penjualan on  penjualan.id_transaksi=transaksi.id_transaksi
        join pembelian on penjualan.id_penjualan=pembelian.id_penjualan
        join detail_transaksi_pembelian on pembelian.id_pembelian=detail_transaksi_pembelian.id_pembelian
        join penerimaan_barang on penerimaan_barang.id_pembelian=pembelian.id_pembelian
        join detail_penerimaan_barang on detail_penerimaan_barang.id_penerimaan_barang = penerimaan_barang.id_penerimaan_barang
        left join pengiriman on pengiriman.id_penerimaan_barang=penerimaan_barang.id_penerimaan_barang
        left join detail_transaksi_pengiriman on detail_transaksi_pengiriman.id_pengiriman=pengiriman.id_pengiriman 
        join produk on detail_penerimaan_barang.id_produk = produk.id_produk
        join pelanggan on pelanggan.id_pelanggan=transaksi.id_pelanggan
        where no_penerimaan='$no_penerimaan' $query
         "
        );
    }


    public function print($no_pengiriman)
    {



        return  DB::select(
            "SELECT * FROM transaksi
             join penawaran on penawaran.id_transaksi = transaksi.id_transaksi
            join penjualan on  penjualan.id_transaksi=transaksi.id_transaksi
            join pembelian on pembelian.id_penjualan=penjualan.id_penjualan
			join penerimaan_barang on penerimaan_barang.id_pembelian=pembelian.id_pembelian
            join detail_penerimaan_barang on detail_penerimaan_barang.id_penerimaan_barang = penerimaan_barang.id_penerimaan_barang
            join pengiriman on penerimaan_barang.id_penerimaan_barang=pengiriman.id_penerimaan_barang
            join detail_transaksi_pengiriman on detail_transaksi_pengiriman.id_pengiriman=pengiriman.id_pengiriman
            join produk on detail_penerimaan_barang.id_produk = produk.id_produk
			join pelanggan on transaksi.id_pelanggan = pelanggan.id_pelanggan
            join pemasok on pembelian.id_pemasok = pemasok.id_pemasok
            where no_pengiriman ='$no_pengiriman' and transaksi.tidak_terpakai=0
            Order BY tgl_pengiriman asc,no_pengiriman asc
        ");
    }
}

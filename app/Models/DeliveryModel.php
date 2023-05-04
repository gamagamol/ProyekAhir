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
            $query = "  and  no_pengiriman = '$id'";
     

        } else {
            $query = "";
        }

        // return DB::select("SELECT b.*,(select sum( jumlah_detail_penerimaan)  from penerimaan_barang 
		// 		join pembelian on penerimaan_barang.id_pembelian=pembelian.id_pembelian            
		// 		join penjualan on  pembelian.id_penjualan=penjualan.id_penjualan  
		// 		join detail_penerimaan_barang on detail_penerimaan_barang.id_penerimaan_barang = penerimaan_barang.id_penerimaan_barang
		// 		where no_pembelian=b.no_pembelian) as jumlah_detail_penerimaan,
        //         (select sum( jumlah_detail_pengiriman)  from pengiriman
		// 			join penerimaan_barang on penerimaan_barang.id_penerimaan_barang=pengiriman.id_penerimaan_barang 
		// 			join pembelian on penerimaan_barang.id_pembelian=pembelian.id_pembelian            
		// 			join penjualan on  pembelian.id_penjualan=penjualan.id_penjualan  
		// 			join detail_transaksi_pengiriman on detail_transaksi_pengiriman.id_pengiriman = pengiriman.id_pengiriman
		// 			where no_pembelian=b.no_pembelian
        //         ) as jumlah_detail_pengiriman
                
        //         from(
        //         select transaksi.id_transaksi,max(tgl_pengiriman) as tgl_pengiriman,no_pengiriman,nomor_pekerjaan,nama_pelanggan,nama_pengguna ,status_transaksi,no_pembelian from transaksi
        //         join penawaran on penawaran.id_transaksi = transaksi.id_transaksi    
        //         join penjualan on  penjualan.id_transaksi=transaksi.id_transaksi
        //         join pembelian on penjualan.id_penjualan=pembelian.id_penjualan
        //         join penerimaan_barang on penerimaan_barang.id_pembelian=pembelian.id_pembelian
        //         join detail_penerimaan_barang on detail_penerimaan_barang.id_penerimaan_barang = penerimaan_barang.id_penerimaan_barang
        //         left join pengiriman on pengiriman.id_penerimaan_barang=penerimaan_barang.id_penerimaan_barang
        //         left join detail_transaksi_pengiriman on detail_transaksi_pengiriman.id_pengiriman=pengiriman.id_pengiriman 
        //         join produk on detail_penerimaan_barang.id_produk = produk.id_produk
        //         join pelanggan on pelanggan.id_pelanggan=transaksi.id_pelanggan
        //         join pengguna on pengguna.id=transaksi.id
              
        //         group by no_pembelian
        //         order by tgl_pengiriman desc,no_pengiriman desc
        //                     ) b
        //         where b.no_pengiriman is not null");

        return DB::select("SELECT b.*,(select sum( jumlah_detail_penerimaan)  from penerimaan_barang 
				join pembelian on penerimaan_barang.id_pembelian=pembelian.id_pembelian            
				join penjualan on  pembelian.id_penjualan=penjualan.id_penjualan  
				join detail_penerimaan_barang on detail_penerimaan_barang.id_penerimaan_barang = penerimaan_barang.id_penerimaan_barang
				where no_penjualan=b.no_penjualan) as jumlah_detail_penerimaan,
                (select sum( jumlah_detail_pengiriman)  from pengiriman
					join penerimaan_barang on penerimaan_barang.id_penerimaan_barang=pengiriman.id_penerimaan_barang 
					join pembelian on penerimaan_barang.id_pembelian=pembelian.id_pembelian            
					join penjualan on  pembelian.id_penjualan=penjualan.id_penjualan  
					join detail_transaksi_pengiriman on detail_transaksi_pengiriman.id_pengiriman = pengiriman.id_pengiriman
					where no_penjualan=b.no_penjualan
                ) as jumlah_detail_pengiriman
                
                from(
                select transaksi.id_transaksi,max(tgl_pengiriman) as tgl_pengiriman,no_pengiriman,
                nomor_pekerjaan,nama_pelanggan,nama_pengguna ,status_transaksi,no_penjualan from transaksi
                join penawaran on penawaran.id_transaksi = transaksi.id_transaksi    
                join penjualan on  penjualan.id_transaksi=transaksi.id_transaksi
                join pembelian on penjualan.id_penjualan=pembelian.id_penjualan
                join penerimaan_barang on penerimaan_barang.id_pembelian=pembelian.id_pembelian
                join detail_penerimaan_barang on detail_penerimaan_barang.id_penerimaan_barang = penerimaan_barang.id_penerimaan_barang
                left join pengiriman on pengiriman.id_penerimaan_barang=penerimaan_barang.id_penerimaan_barang
                left join detail_transaksi_pengiriman on detail_transaksi_pengiriman.id_pengiriman=pengiriman.id_pengiriman 
                join produk on detail_penerimaan_barang.id_produk = produk.id_produk
                join pelanggan on pelanggan.id_pelanggan=transaksi.id_pelanggan
                join pengguna on pengguna.id=transaksi.id
              
                group by no_penjualan
                order by tgl_pengiriman desc,no_pengiriman desc
                            ) b
                ");
    }

    public function show($no_penerimaan)
    {

        $no_pengiriman =
            DB::select("SELECT distinct no_pengiriman FROM ibaraki_db.penerimaan_barang
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

        //   dd($no_pengiriman[0]->no_pengiriman);

        // dd($query);
        // return DB::select(
        //     "SELECT   *,penerimaan_barang.no_penerimaan,no_pengiriman, transaksi.id_transaksi , penjualan.id_penjualan ,
        //     penerimaan_barang.id_penerimaan_barang ,penawaran.id_penawaran,jumlah_detail_penerimaan,
        //     case 
        //     when jumlah_detail_pengiriman > 0 then sum(jumlah_detail_pengiriman)
        //     end as
        //     sudah_terkirim,
        //     jumlah_detail_pengiriman,
        //     sisa_detail_pengiriman ,detail_penerimaan_barang.id_produk FROM ibaraki_db.transaksi 
        //     join penjualan on  penjualan.id_transaksi=transaksi.id_transaksi
        //     join penerimaan_barang on penerimaan_barang.id_transaksi=transaksi.id_transaksi
        //     join detail_penerimaan_barang on detail_penerimaan_barang.id_penerimaan_barang = penerimaan_barang.id_penerimaan_barang
        //     join penawaran on penawaran.id_transaksi = transaksi.id_transaksi
        //     left join pengiriman on pengiriman.id_transaksi = transaksi.id_transaksi
        //     left join detail_transaksi_pengiriman on detail_transaksi_pengiriman.id_pengiriman=pengiriman.id_pengiriman 
        //     join pelanggan on pelanggan.id_pelanggan=transaksi.id_pelanggan
        //     join pengguna on pengguna.id=transaksi.id
        //     join produk on detail_penerimaan_barang.id_produk=produk.id_produk
		// 	where no_penerimaan='$no_penerimaan'
        //     $query
        //     "
        // );
        return DB::select(
            "SELECT   *,penerimaan_barang.no_penerimaan,no_pengiriman, transaksi.id_transaksi , penjualan.id_penjualan ,
            penerimaan_barang.id_penerimaan_barang ,penawaran.id_penawaran,jumlah_detail_penerimaan,
            case 
            when jumlah_detail_pengiriman > 0 then sum(jumlah_detail_pengiriman)
            end as
            sudah_terkirim,
            jumlah_detail_pengiriman,
            sisa_detail_pengiriman ,detail_penerimaan_barang.id_produk FROM ibaraki_db.transaksi 
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
            join pelanggan on transaksi.id_pelanggan = pelanggan.id_pelanggan
            join pemasok on transaksi.id_pemasok = pemasok.id_pemasok
            join penawaran on penawaran.id_transaksi = transaksi.id_transaksi
            join penjualan on  penjualan.id_transaksi=transaksi.id_transaksi
            join pembelian on pembelian.id_penjualan=penjualan.id_penjualan
            join penerimaan_barang on penerimaan_barang.id_pembelian=pembelian.id_pembelian
            join detail_penerimaan_barang on detail_penerimaan_barang.id_penerimaan_barang = penerimaan_barang.id_penerimaan_barang
            join pengiriman on penerimaan_barang.id_penerimaan_barang=pengiriman.id_penerimaan_barang
            join detail_transaksi_pengiriman on detail_transaksi_pengiriman.id_pengiriman=pengiriman.id_pengiriman
            join produk on detail_penerimaan_barang.id_produk = produk.id_produk
            where no_penjualan ='$no_pengiriman'
            Order BY tgl_pengiriman asc,no_pengiriman asc

        
        "


        );
    }


    public function edit($no_penerimaan)
    {
        // dd($no_penerimaan);
        
        $no_pengiriman =
            DB::select("SELECT distinct no_pengiriman FROM ibaraki_db.penerimaan_barang
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
        // dump($no_pengiriman);
        // dd($query);



        return DB::select(
            "SELECT  *,penerimaan_barang.no_penerimaan,no_pengiriman, transaksi.id_transaksi , penjualan.id_penjualan ,penerimaan_barang.id_penerimaan_barang ,penawaran.id_penawaran, jumlah_detail_penerimaan,
         jumlah_detail_pengiriman as jumlah_detail_pengiriman,jumlah_detail_penerimaan- jumlah_detail_pengiriman as sisa_detail_penerimaan
            ,tgl_penerimaan,detail_penerimaan_barang.id_produk,harga FROM transaksi 
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

        

        return DB::select("SELECT 
                (select max(tgl_pengiriman) as tgl_pengiriman from pengiriman 
                join detail_transaksi_pengiriman on pengiriman.id_pengiriman=detail_transaksi_pengiriman.id_pengiriman
                join produk on produk.id_produk = detail_transaksi_pengiriman.id_produk
                where no_pengiriman= b.no_pengiriman and pengiriman.id_pengiriman=b.id_pengiriman
                group by produk.id_produk) tgl_pengiriman,b.*
                FROM (
                SELECT max(pengiriman.id_pengiriman) as id_pengiriman,no_pengiriman,transaksi.*,produk.*,nama_pelanggan,alamat_pelanggan,perwakilan,nama_pengguna,no_penjualan,jumlah_detail_penjualan FROM transaksi 
            join penawaran on penawaran.id_transaksi=transaksi.id_transaksi
            join penjualan on penjualan.id_transaksi=transaksi.id_transaksi
            join pembelian on pembelian.id_penjualan = penjualan.id_penjualan
            join detail_transaksi_pembelian on  pembelian.id_pembelian=detail_transaksi_pembelian.id_pembelian
            join penerimaan_barang on penerimaan_barang.id_pembelian = pembelian.id_pembelian
                join detail_transaksi_penjualan on detail_transaksi_penjualan.id_penjualan=penjualan.id_penjualan
                join pengiriman on pengiriman.id_penerimaan_barang=penerimaan_barang.id_penerimaan_barang
                join detail_transaksi_pengiriman on pengiriman.id_pengiriman=detail_transaksi_pengiriman.id_pengiriman
                join produk on produk.id_produk = detail_transaksi_pengiriman.id_produk
                join pelanggan on pelanggan.id_pelanggan = transaksi.id_pelanggan
                join pengguna on pengguna.id=transaksi.id
                where no_penjualan ='$no_pengiriman'
                group by transaksi.id_transaksi) b 
                order by tgl_pengiriman asc");


    }



 
}

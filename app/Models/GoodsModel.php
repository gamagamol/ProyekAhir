<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use function app\helper\no_transaksi;


class GoodsModel extends Model
{
    use HasFactory;
    public function index($id = null)
    {
        if ($id) {
            $query = "where no_penerimaan='$id'";
        } else {
            $query = "";
        }



        return DB::select(
            "SELECT b.tgl_penerimaan,b.nomor_transaksi,b.no_penerimaan,b.no_pengiriman,b.nomor_pekerjaan,b.nama_pelanggan,b.nama_pengguna,
                (select sum( jumlah_detail_pengiriman) from penerimaan_barang 
                join detail_penerimaan_barang on detail_penerimaan_barang.id_penerimaan_barang = penerimaan_barang.id_penerimaan_barang
                join transaksi on penerimaan_barang.id_transaksi = transaksi.id_transaksi
                left join pengiriman on pengiriman.id_penerimaan_barang = penerimaan_barang.id_penerimaan_barang
                left join detail_transaksi_pengiriman on pengiriman.id_pengiriman = detail_transaksi_pengiriman.id_pengiriman
                  where no_penerimaan=b.no_penerimaan

                )jumlah_detail_pengiriman ,
                (SELECT sum(jumlah_detail_penerimaan) FROM detail_penerimaan_barang join penerimaan_barang on penerimaan_barang.id_penerimaan_barang = detail_penerimaan_barang.id_penerimaan_barang
                where no_penerimaan=b.no_penerimaan
                ) jumlah_detail_penerimaan,b.no_pembelian
                from(
            SELECT  transaksi.id_transaksi,nomor_pekerjaan, no_penerimaan,no_pengiriman, 
            pengiriman.id_penerimaan_barang, jumlah_detail_penerimaan,
            sum(jumlah_detail_pengiriman) as jumlah_detail_pengiriman,sisa_detail_pengiriman,
            nama_pelanggan,nama_pengguna,tgl_penerimaan,no_pembelian ,nomor_transaksi FROM transaksi
             join pembelian on pembelian.id_transaksi = transaksi.id_transaksi
             join penerimaan_barang on penerimaan_barang.id_pembelian = pembelian.id_pembelian
            join detail_penerimaan_barang on detail_penerimaan_barang.id_penerimaan_barang=penerimaan_barang.id_penerimaan_barang
            left join pengiriman on pengiriman.id_transaksi = transaksi.id_transaksi
            left join detail_transaksi_pengiriman on detail_transaksi_pengiriman.id_pengiriman=pengiriman.id_pengiriman 
             join pelanggan on pelanggan.id_pelanggan=transaksi.id_pelanggan
            join pengguna on pengguna.id=transaksi.id
            join pemasok on pembelian.id_pemasok =  pemasok.id_pemasok
            $query
            group by no_penerimaan
           ) b
           group by b.no_penerimaan
        --    having jumlah_detail_penerimaan != ifnull(jumlah_detail_pengiriman,0)
            order by b.tgl_penerimaan desc,b.no_penerimaan desc
           "
        );
    }
    public function show($no_pembelian)
    {


        return    DB::select("SELECT * FROM pembelian join detail_transaksi_pembelian 
            on pembelian.id_pembelian=detail_transaksi_pembelian.id_pembelian
            join transaksi on transaksi.id_transaksi=pembelian.id_transaksi
            join produk on detail_transaksi_pembelian.id_produk=produk.id_produk
			join penawaran on penawaran.id_transaksi=transaksi.id_transaksi
            join detail_transaksi_penawaran on detail_transaksi_penawaran.id_penawaran=penawaran.id_penawaran
            join pelanggan on pelanggan.id_pelanggan=transaksi.id_pelanggan
            join pengguna on pengguna.id=transaksi.id
            join pemasok on pembelian.id_pemasok =  pemasok.id_pemasok
			where no_pembelian='$no_pembelian'
            
            ");
    }

    public function edit($no_pembelian, $kode_transaksi = null)
    {
        // jangan di hapus dulu 
        // kalo ingin ada penerimaan beberapa  kali pake kdoe transaksi dalam parameter
        // if ($kode_transaksi) {
        //     return DB::table('transaksi')
        //         ->selectRaw('transaksi.id_transaksi,pembelian.id_pembelian,pembelian.tgl_pembelian,pembelian.no_pembelian,detail_transaksi_pembelian.id_produk,jumlah_detail_pembelian')
        //         ->join('pembelian', 'pembelian.id_transaksi', '=', 'transaksi.id_transaksi')
        //         ->join('detail_transaksi_pembelian', 'detail_transaksi_pembelian.id_pembelian', '=', 'pembelian.id_pembelian')
        //         ->where('kode_transaksi', '=', $kode_transaksi)
        //         ->get();
        // } else {
        //     return DB::table('transaksi')
        //         ->selectRaw('transaksi.id_transaksi,pembelian.id_pembelian,pembelian.tgl_pembelian,pembelian.no_pembelian,detail_transaksi_pembelian.id_produk,jumlah_detail_pembelian')
        //         ->join('pembelian', 'pembelian.id_transaksi', '=', 'transaksi.id_transaksi')
        //         ->join('detail_transaksi_pembelian', 'detail_transaksi_pembelian.id_pembelian', '=', 'pembelian.id_pembelian')
        //         ->where('no_pembelian', '=', $no_pembelian)
        //         ->get();
        // }


        return DB::table('transaksi')
            ->selectRaw('transaksi.id_transaksi,pembelian.id_pembelian,pembelian.tgl_pembelian,pembelian.no_pembelian,detail_transaksi_pembelian.id_produk,jumlah_detail_pembelian')
            ->join('pembelian', 'pembelian.id_transaksi', '=', 'transaksi.id_transaksi')
            ->join('detail_transaksi_pembelian', 'detail_transaksi_pembelian.id_pembelian', '=', 'pembelian.id_pembelian')
            ->where('no_pembelian', '=', $no_pembelian)
            ->get();
    }

    public function no_penerimaan($tgl_penerimaan, $unit)
    {
        $bulan_tgl = explode("-", $tgl_penerimaan);

        if ($unit) {
            $arr_nopenerimaan = [];
            for ($i = 0; $i < count($unit); $i++) {

                $no_penerimaan = DB::select("
                    select * from penerimaan_barang where id_penerimaan_barang =(select max(id_penerimaan_barang) from penerimaan_barang 
                    where month(tgl_penerimaan)='$bulan_tgl[1]' and YEAR(tgl_penerimaan)='$bulan_tgl[0]'  )");
                if ($no_penerimaan != null) {

                    $no_penerimaan = no_transaksi($no_penerimaan[0]->no_penerimaan);
                } else {
                    $no_penerimaan = 1;
                }
                $no_penerimaan += $i;
                array_push($arr_nopenerimaan, $no_penerimaan);
            }
            return $arr_nopenerimaan;
        } else {

            $no_penerimaan = DB::select("
                    select * from penerimaan_barang where id_penerimaan_barang =(select max(id_penerimaan_barang) from penerimaan_barang 
                    where month(tgl_penerimaan)='$bulan_tgl[1]' and YEAR(tgl_penerimaan)='$bulan_tgl[0]')");
            if ($no_penerimaan != null) {

                $no_penerimaan = no_transaksi($no_penerimaan[0]->no_penerimaan);
            } else {
                $no_penerimaan = 1;
            }

            return $no_penerimaan;
        }
    }



    public function insert_penerimaan($id_transaksi, $data_penerimaan, $data_detail_penerimaan, $unit)
    {

        // mengubah data transaksi

        for ($i = 0; $i <= count($id_transaksi) - 1; $i++) {
            DB::table('transaksi')
                ->where("id_transaksi", "=", $id_transaksi[$i])
                ->update(['status_transaksi' => "goods"]);
        }

        // insert data transaksi

        DB::table('penerimaan_barang')->insert($data_penerimaan);

        // perispan data detail transaksi penjualan
        if ($unit) {
            for ($i = 0; $i < count($data_detail_penerimaan); $i++) {

                $id_penerimaan_barang = DB::table('penerimaan_barang')->select('id_penerimaan_barang')->where('no_penerimaan', "=", $data_penerimaan[$i]['no_penerimaan'])->first();

                $data_detail_penerimaan[$i]['id_penerimaan_barang'] = $id_penerimaan_barang->id_penerimaan_barang;
            }
            // dd($data_detail_penerimaan);
            //     insert data detail penjualan transaksi
            DB::table('detail_penerimaan_barang')->insert($data_detail_penerimaan);
        } else {
            for ($i = 0; $i < count($data_detail_penerimaan); $i++) {
                $id_penerimaan_barang = DB::table('penerimaan_barang')->select('id_penerimaan_barang')->where('no_penerimaan', "=", $data_penerimaan[$i]['no_penerimaan'])->get();

                $data_detail_penerimaan[$i]['id_penerimaan_barang'] = $id_penerimaan_barang[$i]->id_penerimaan_barang;
            }
            //     insert data detail penjualan transaksi
            DB::table('detail_penerimaan_barang')->insert($data_detail_penerimaan);
        }
    }






    public function detail($no_pembelian, $no_penerimaan)
    {

 

        return DB::select("SELECT *
            FROM pembelian
            JOIN detail_transaksi_pembelian ON pembelian.id_pembelian = detail_transaksi_pembelian.id_pembelian
            JOIN transaksi ON transaksi.id_transaksi = pembelian.id_transaksi
            JOIN produk ON detail_transaksi_pembelian.id_produk = produk.id_produk
            JOIN penawaran ON penawaran.id_transaksi = transaksi.id_transaksi
            JOIN detail_transaksi_penawaran ON detail_transaksi_penawaran.id_penawaran = penawaran.id_penawaran
            JOIN pelanggan ON pelanggan.id_pelanggan = transaksi.id_pelanggan
            JOIN pengguna ON pengguna.id = transaksi.id
            JOIN pemasok ON pembelian.id_pemasok = pemasok.id_pemasok
            JOIN penerimaan_barang ON penerimaan_barang.id_transaksi = pembelian.id_transaksi
            JOIN detail_penerimaan_barang ON detail_penerimaan_barang.id_penerimaan_barang = penerimaan_barang.id_penerimaan_barang
            WHERE pembelian.no_pembelian = '$no_pembelian' AND penerimaan_barang.no_penerimaan = '$no_penerimaan'
            GROUP BY penerimaan_barang.id_penerimaan_barang
            ");
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GoodsModel extends Model
{
    use HasFactory;
    public function index($id = null)
    {
        if ($id) {
            return DB::table('transaksi')

                ->join('penawaran', 'penawaran.id_transaksi', '=', 'transaksi.id_transaksi')
                ->join('detail_transaksi_penawaran', 'detail_transaksi_penawaran.id_penawaran', '=', 'penawaran.id_penawaran')
                ->join("produk", 'detail_transaksi_penawaran.id_produk', '=', 'produk.id_produk')
                ->join("pelanggan", 'transaksi.id_pelanggan', '=', 'pelanggan.id_pelanggan')
                ->join("pengguna", 'transaksi.id', '=', 'pengguna.id')
                ->join('penjualan', "penjualan.id_transaksi", "transaksi.id_transaksi")
                ->join('pembelian', "pembelian.id_transaksi", "transaksi.id_transaksi")
                ->join('penerimaan_barang', "penerimaan_barang.id_transaksi", "transaksi.id_transaksi")
                ->where('no_penerimaan', "=", $id)

                ->paginate(5);
        } else {

            return DB::table('transaksi')

                ->join('penawaran', 'penawaran.id_transaksi', '=', 'transaksi.id_transaksi')
                ->join('detail_transaksi_penawaran', 'detail_transaksi_penawaran.id_penawaran', '=', 'penawaran.id_penawaran')
                ->join("produk", 'detail_transaksi_penawaran.id_produk', '=', 'produk.id_produk')
                ->join("pelanggan", 'transaksi.id_pelanggan', '=', 'pelanggan.id_pelanggan')
                ->join("pengguna", 'transaksi.id', '=', 'pengguna.id')
                ->join('penjualan', "penjualan.id_transaksi", "transaksi.id_transaksi")
                ->join('pembelian', "pembelian.id_transaksi", "transaksi.id_transaksi")
                ->join('penerimaan_barang', "penerimaan_barang.id_transaksi", "transaksi.id_transaksi")
                ->where('status_transaksi', "=", 'goods')
                ->groupBy('no_penerimaan')
                ->orderBy('tgl_penerimaan', 'asc')
                ->orderBy('no_penerimaan', 'asc')
                ->paginate(5);
        }
    }
    public function show($no_pembelian)
    {
        // return DB::table('transaksi')

        //     ->join('penawaran', 'penawaran.id_transaksi', '=', 'transaksi.id_transaksi')
        //     ->join('detail_transaksi_penawaran', 'detail_transaksi_penawaran.id_penawaran', '=', 'penawaran.id_penawaran')
        //     ->join('penjualan', 'penjualan.id_transaksi', '=', 'transaksi.id_transaksi')
        //     ->join('detail_transaksi_penjualan', 'detail_transaksi_penjualan.id_penjualan', '=', 'penjualan.id_penjualan')
        //     ->join('pembelian', 'pembelian.id_transaksi', '=', 'transaksi.id_transaksi')
        //     ->join('detail_transaksi_pembelian', 'detail_transaksi_pembelian.id_pembelian', '=', 'pembelian.id_pembelian')
        //     ->join("produk", 'detail_transaksi_penawaran.id_produk', '=', 'produk.id_produk')
        //     ->join("pelanggan", 'transaksi.id_pelanggan', '=', 'pelanggan.id_pelanggan')
        //     ->join("pemasok", 'transaksi.id_pemasok', '=', 'pemasok.id_pemasok')
        //     ->join("pengguna", 'transaksi.id', '=', 'pengguna.id')
        //     ->where('pembelian.no_pembelian', '=', $no_pembelian)
        //     ->get();

     return    DB::select("SELECT *
            FROM penjualan join detail_transaksi_penjualan 
            on penjualan.id_penjualan=detail_transaksi_penjualan.id_penjualan
            join transaksi on transaksi.id_transaksi=penjualan.id_transaksi
            left outer join pembelian on pembelian.id_transaksi=transaksi.id_transaksi
            LEFT OUTER join detail_transaksi_pembelian 
            on pembelian.id_pembelian=detail_transaksi_pembelian.id_pembelian
            LEFT OUTER JOIN penerimaan_barang on penerimaan_barang.id_transaksi=transaksi.id_transaksi
            left outer join detail_penerimaan_barang on detail_penerimaan_barang.id_penerimaan_barang=penerimaan_barang.id_penerimaan_barang
            join produk on detail_transaksi_pembelian.id_produk=produk.id_produk
			join penawaran on penawaran.id_transaksi=transaksi.id_transaksi
            join detail_transaksi_penawaran on detail_transaksi_penawaran.id_penawaran=penawaran.id_penawaran
            join pelanggan on pelanggan.id_pelanggan=transaksi.id_pelanggan
            join pengguna on pengguna.id=transaksi.id
            join pemasok on transaksi.id_pemasok = pemasok.id_pemasok
            group by no_pembelian,detail_transaksi_pembelian.id_produk
            having  jumlah_detail_pembelian >sum(ifnull(jumlah_detail_penerimaan,0)) and no_pembelian='$no_pembelian'");


    }

    public function edit($no_pembelian, $kode_transaksi = null)
    {
        if ($kode_transaksi) {
            return DB::table('transaksi')
                ->selectRaw('transaksi.id_transaksi,pembelian.id_pembelian,pembelian.tgl_pembelian,pembelian.no_pembelian,detail_transaksi_pembelian.id_produk,jumlah_detail_pembelian')
                ->join('pembelian', 'pembelian.id_transaksi', '=', 'transaksi.id_transaksi')
                ->join('detail_transaksi_pembelian', 'detail_transaksi_pembelian.id_pembelian', '=', 'pembelian.id_pembelian')
                ->where('kode_transaksi', '=', $kode_transaksi)
                ->get();
        } else {
            return DB::table('transaksi')
                ->selectRaw('transaksi.id_transaksi,pembelian.id_pembelian,pembelian.tgl_pembelian,pembelian.no_pembelian,detail_transaksi_pembelian.id_produk,jumlah_detail_pembelian')
                ->join('pembelian', 'pembelian.id_transaksi', '=', 'transaksi.id_transaksi')
                ->join('detail_transaksi_pembelian', 'detail_transaksi_pembelian.id_pembelian', '=', 'pembelian.id_pembelian')
                ->where('no_pembelian', '=', $no_pembelian)
                ->get();
        }
    }

    public function no_penerimaan($tgl_penerimaan, $unit)
    {
        if ($unit) {
            $arr_nopenerimaan = [];
            for ($i = 0; $i < count($unit); $i++) {
                $no_penerimaan =
                    DB::table('penerimaan_barang')
                    ->selectRaw("DISTINCT ifnull(max(substring(no_penerimaan,4,1)),0)+1 as no_penerimaan")
                    ->where("tgl_penerimaan", "=", $tgl_penerimaan)
                    ->first();
                $no_penerimaan = (int)$no_penerimaan->no_penerimaan;
                $no_penerimaan += $i;
                array_push($arr_nopenerimaan, $no_penerimaan);
            }
            return $arr_nopenerimaan;
        } else {
            $no_penerimaan =
                DB::table('penerimaan_barang')
                ->selectRaw("DISTINCT ifnull(max(substring(no_penerimaan,4,1)),0)+1 as no_penerimaan")
                ->where("tgl_penerimaan", "=", $tgl_penerimaan)
                ->first();
            $no_penerimaan = (int)$no_penerimaan->no_penerimaan;

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
        } 
        
        
        else {
            for ($i = 0; $i < count($data_detail_penerimaan); $i++) {
                $id_penerimaan_barang = DB::table('penerimaan_barang')->select('id_penerimaan_barang')->where('no_penerimaan', "=", $data_penerimaan[$i]['no_penerimaan'])->get();

                $data_detail_penerimaan[$i]['id_penerimaan_barang'] = $id_penerimaan_barang[$i]->id_penerimaan_barang;
            }
            //     insert data detail penjualan transaksi
            DB::table('detail_penerimaan_barang')->insert($data_detail_penerimaan);
        }





    }






    public function detail($no_penerimaan)
    {
        return DB::table('transaksi')

            ->join('penawaran', 'penawaran.id_transaksi', '=', 'transaksi.id_transaksi')
            ->join('detail_transaksi_penawaran', 'detail_transaksi_penawaran.id_penawaran', '=', 'penawaran.id_penawaran')
            ->join("produk", 'detail_transaksi_penawaran.id_produk', '=', 'produk.id_produk')
            ->join("pelanggan", 'transaksi.id_pelanggan', '=', 'pelanggan.id_pelanggan')
            ->join("pemasok", 'transaksi.id_pemasok', '=', 'pemasok.id_pemasok')
            ->join("pengguna", 'transaksi.id', '=', 'pengguna.id')
            ->join('penjualan', "penjualan.id_transaksi", "transaksi.id_transaksi")
            ->join('penerimaan_barang', "penerimaan_barang.id_transaksi", "transaksi.id_transaksi")
            ->where('no_penerimaan', "=", $no_penerimaan)
            ->get();
    }



    
}

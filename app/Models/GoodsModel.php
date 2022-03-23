<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GoodsModel extends Model
{
    use HasFactory;
    public function index($id=null)
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
        }else{

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
                ->paginate(5);
        }
    }
    public function show($no_pembelian)
    {
        return DB::table('transaksi')

            ->join('penawaran', 'penawaran.id_transaksi', '=', 'transaksi.id_transaksi')
            ->join('detail_transaksi_penawaran', 'detail_transaksi_penawaran.id_penawaran', '=', 'penawaran.id_penawaran')
            ->join('penjualan', 'penjualan.id_transaksi', '=', 'transaksi.id_transaksi')
            ->join('detail_transaksi_penjualan', 'detail_transaksi_penjualan.id_penjualan', '=', 'penjualan.id_penjualan')
            ->join('pembelian', 'pembelian.id_transaksi', '=', 'transaksi.id_transaksi')
            ->join('detail_transaksi_pembelian', 'detail_transaksi_pembelian.id_pembelian', '=', 'pembelian.id_pembelian')
            ->join("produk", 'detail_transaksi_penawaran.id_produk', '=', 'produk.id_produk')
            ->join("pelanggan", 'transaksi.id_pelanggan', '=', 'pelanggan.id_pelanggan')
            ->join("pemasok", 'transaksi.id_pemasok', '=', 'pemasok.id_pemasok')
            ->join("pengguna", 'transaksi.id', '=', 'pengguna.id')
            ->where('pembelian.no_pembelian', '=', $no_pembelian)
            ->get();
    }

    public function edit($kode_transaksi)
    {
        return DB::table('transaksi')
            ->selectRaw('transaksi.id_transaksi,pembelian.id_pembelian,pembelian.tgl_pembelian,pembelian.no_pembelian,detail_transaksi_pembelian.id_produk')
            ->join('pembelian', 'pembelian.id_transaksi', '=', 'transaksi.id_transaksi')
            ->join('detail_transaksi_pembelian', 'detail_transaksi_pembelian.id_pembelian', '=', 'pembelian.id_pembelian')
            ->where('kode_transaksi', '=', $kode_transaksi)
            ->get();
    }

    public function no_penerimaan($tgl_penerimaan)
    {
        $no_penerimaan =
            DB::table('penerimaan_barang')
            ->selectRaw("DISTINCT ifnull(max(substring(no_penerimaan,4,1)),0)+1 as no_penerimaan")
            ->where("tgl_penerimaan", "=", $tgl_penerimaan)
            ->first();
        $no_penerimaan = (int)$no_penerimaan->no_penerimaan;


        return $no_penerimaan;
    }



    public function insert_penerimaan($id_transaksi, $data_penerimaan, $data_detail_penerimaan)
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



        for ($i = 0; $i < count($id_transaksi); $i++) {
            $id_penerimaan_barang = DB::table('penerimaan_barang')->select('id_penerimaan_barang')->where('id_transaksi', "=", $id_transaksi[$i])->get();
            $data_detail_penerimaan[$i]['id_penerimaan_barang'] = $id_penerimaan_barang[0]->id_penerimaan_barang;
        }


        //     insert data detail penjualan transaksi
        DB::table('detail_penerimaan_barang')->insert($data_detail_penerimaan);



        return  $data_penerimaan[0]['no_penerimaan'];
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

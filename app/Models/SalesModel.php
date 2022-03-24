<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SalesModel extends Model
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
                    ->where('no_penjualan', "=", $id)
                    ->paginate(1);
          } else {

               return DB::table('transaksi')

                    ->join('penawaran', 'penawaran.id_transaksi', '=', 'transaksi.id_transaksi')
                    ->join('detail_transaksi_penawaran', 'detail_transaksi_penawaran.id_penawaran', '=', 'penawaran.id_penawaran')
                    ->join("produk", 'detail_transaksi_penawaran.id_produk', '=', 'produk.id_produk')
                    ->join("pelanggan", 'transaksi.id_pelanggan', '=', 'pelanggan.id_pelanggan')
                    ->join("pengguna", 'transaksi.id', '=', 'pengguna.id')
                    ->join('penjualan', "penjualan.id_transaksi", "transaksi.id_transaksi")
                    ->where('status_transaksi', "=", 'sales')
                    ->groupBy('no_penjualan')
                    ->paginate(5);
          }
     }
     public function show($kode_transaksi)
     {
          return DB::table('transaksi')

               ->join('penawaran', 'penawaran.id_transaksi', '=', 'transaksi.id_transaksi')
               ->join('detail_transaksi_penawaran', 'detail_transaksi_penawaran.id_penawaran', '=', 'penawaran.id_penawaran')
               ->join("produk", 'detail_transaksi_penawaran.id_produk', '=', 'produk.id_produk')
               ->join("pelanggan", 'transaksi.id_pelanggan', '=', 'pelanggan.id_pelanggan')
               ->join("pengguna", 'transaksi.id', '=', 'pengguna.id')
               ->where('kode_transaksi', '=', $kode_transaksi)
               ->get();
     }

     public function edit($kode_transaksi)
     {
          return DB::table('transaksi')
               ->selectRaw('transaksi.id_transaksi,penawaran.id_penawaran,penawaran.tgl_penawaran,penawaran.no_penawaran,detail_transaksi_penawaran.id_produk,transaksi.jumlah')
               ->join('penawaran', 'penawaran.id_transaksi', '=', 'transaksi.id_transaksi')
               ->join('detail_transaksi_penawaran', 'detail_transaksi_penawaran.id_penawaran', '=', 'penawaran.id_penawaran')
               ->where('kode_transaksi', '=', $kode_transaksi)
               ->get();
     }

     public function no_penjualan($tgl_penjualan)
     {
          $no_penjualan =
               DB::table('penjualan')
               ->selectRaw("DISTINCT ifnull(max(substring(no_penjualan,4,1)),0)+1 as no_penjualan")
               ->where("tgl_penjualan", "=", $tgl_penjualan)
               ->first();
          $no_penjualan = (int)$no_penjualan->no_penjualan;


          return $no_penjualan;
     }



     public function insert_penjualan($id_transaksi, $data_penjualan, $data_detail_penjualan)
     {

          // mengubah data transaksi

          for ($i = 0; $i <= count($id_transaksi) - 1; $i++) {
               DB::table('transaksi')
                    ->where("id_transaksi", "=", $id_transaksi[$i])
                    ->update(['status_transaksi' => "sales"]);
          }

          // insert data transaksi

          DB::table('penjualan')->insert($data_penjualan);

          // perispan data detail transaksi penjualan



          for ($i = 0; $i < count($id_transaksi); $i++) {
               $id_penjualan = DB::table('penjualan')->select('id_penjualan')->where('id_transaksi', "=", $id_transaksi[$i])->get();
               $data_detail_penjualan[$i]['id_penjualan'] = $id_penjualan[0]->id_penjualan;
          }


          //     insert data detail penjualan transaksi
          DB::table('detail_transaksi_penjualan')->insert($data_detail_penjualan);



          return  $data_penjualan[0]['no_penjualan'];
     }

     public function detail($no_penjualan)
     {
          return DB::table('transaksi')

               ->join('penawaran', 'penawaran.id_transaksi', '=', 'transaksi.id_transaksi')
               ->join('detail_transaksi_penawaran', 'detail_transaksi_penawaran.id_penawaran', '=', 'penawaran.id_penawaran')
               ->join("produk", 'detail_transaksi_penawaran.id_produk', '=', 'produk.id_produk')
               ->join("pelanggan", 'transaksi.id_pelanggan', '=', 'pelanggan.id_pelanggan')
               ->join("pengguna", 'transaksi.id', '=', 'pengguna.id')
               ->join('penjualan', "penjualan.id_transaksi", "transaksi.id_transaksi")
               ->where('no_penjualan', "=", $no_penjualan)
               ->get();
     }
}

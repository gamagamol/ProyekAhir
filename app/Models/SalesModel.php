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
               if ($id == 'ALL') {
                    $query = "";
               } else {
                    $query = "where no_penjualan='$id'";
               }
          }else{
               $query = "";
          }



          return DB::select(
               "SELECT distinct tgl_penjualan,no_penjualan,nomor_pekerjaan,nama_pelanggan,nama_pengguna,kode_transaksi,jumlah_detail_penjualan,jumlah_detail_pembelian from transaksi 
               join penawaran on penawaran.id_transaksi=transaksi.id_transaksi
               join detail_transaksi_penawaran on detail_transaksi_penawaran.id_penawaran=penawaran.id_penawaran
               join penjualan on penjualan.id_transaksi=transaksi.id_transaksi
               join detail_transaksi_penjualan on penjualan.id_penjualan=detail_transaksi_penjualan.id_penjualan
               left join pembelian on pembelian.id_penjualan=penjualan.id_penjualan
               left join detail_transaksi_pembelian on detail_transaksi_pembelian.id_pembelian=pembelian.id_pembelian
               join pelanggan on pelanggan.id_pelanggan=transaksi.id_pelanggan
               join pengguna on pengguna.id=transaksi.id
               join produk on detail_transaksi_penjualan.id_produk=produk.id_produk
                    $query
               group by no_penjualan,penjualan.id_penjualan
               -- having jumlah_detail_penjualan > sum(ifnull(jumlah_detail_pembelian,0))
               order by tgl_penjualan desc,no_penjualan desc
               "

          );
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

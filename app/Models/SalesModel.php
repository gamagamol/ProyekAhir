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

               // return DB::table('transaksi')

               //      ->join('penawaran', 'penawaran.id_transaksi', '=', 'transaksi.id_transaksi')
               //      ->join('detail_transaksi_penawaran', 'detail_transaksi_penawaran.id_penawaran', '=', 'penawaran.id_penawaran')
               //      ->join("pelanggan", 'transaksi.id_pelanggan', '=', 'pelanggan.id_pelanggan')
               //      ->join("pengguna", 'transaksi.id', '=', 'pengguna.id')
               //      ->join("produk", 'detail_transaksi_penawaran.id_produk', '=', 'produk.id_produk')
               //      ->join('penjualan', "penjualan.id_transaksi", "transaksi.id_transaksi")
               //      ->where('status_transaksi', "=", 'sales')
               //      ->groupBy('no_penjualan')
               //      ->paginate(5);

               // return DB::table('transaksi')
               //      ->selectRaw('tgl_penjualan,no_penjualan,nomor_pekerjaan,berat,total,layanan,nama_pelanggan,nama_pengguna,kode_transaksi,jumlah_detail_penjualan,jumlah_detail_pembelian')
               //      ->join('penawaran', 'penawaran.id_transaksi', '=', 'transaksi.id_transaksi')
               //      ->join('detail_transaksi_penawaran', 'detail_transaksi_penawaran.id_penawaran', '=', 'penawaran.id_penawaran')
               //      ->join("pelanggan", 'transaksi.id_pelanggan', '=', 'pelanggan.id_pelanggan')
               //      ->join("pengguna", 'transaksi.id', '=', 'pengguna.id')
               //      ->join('penjualan', "penjualan.id_transaksi", "transaksi.id_transaksi")
               //      ->join('detail_transaksi_penjualan', 'detail_transaksi_penjualan.id_penjualan', '=', 'penjualan.id_penjualan')
               //      ->join("produk", 'detail_transaksi_penjualan.id_produk', '=', 'produk.id_produk')
               //      ->join('pembelian', "pembelian.id_transaksi", "transaksi.id_transaksi")
               //      ->join('detail_transaksi_pembelian', 'detail_transaksi_pembelian.id_pembelian', '=', 'pembelian.id_pembelian')
               //      ->groupBy('kode_transaksi')
               //      ->havingRaw("jumlah_detail_penjualan > sum(jumlah_detail_pembelian)")
               //      ->paginate(5);


               // dd( DB::table('penjualan')
               // ->join('detail_transaksi_penjualan','penjualan.id_penjualan','=','detail_transaksi_penjualan.id_penjualan')
               // ->join('transaksi','penjualan.id_transaksi','=','transaksi.id_transaksi')
               // ->leftJoin('pembelian','pembelian.id_transaksi','=','transaksi.id_transaksi')
               // ->leftJoin('detail_transaksi_pembelian','detail_transaksi_pembelian.id_pembelian','=','pembelian.id_pembelian')
               // ->groupBy("kode_transaksi, detail_transaksi_penjualan . id_produk")
               // ->havingRaw("having jumlah_detail_penjualan > sum(ifnull(jumlah_detail_pembelian,0))")
               // ->get());

               // return DB::select("SELECT *,
               //           sum(ifnull(jumlah_detail_pembelian,0)) as total_unit_pembelian 
               //           FROM penjualan join detail_transaksi_penjualan 
               //           on penjualan.id_penjualan=detail_transaksi_penjualan.id_penjualan
               //           join transaksi on transaksi.id_transaksi=penjualan.id_transaksi
               //           left outer join pembelian on pembelian.id_transaksi=transaksi.id_transaksi
               //           LEFT OUTER join detail_transaksi_pembelian 
               //           on pembelian.id_pembelian=detail_transaksi_pembelian.id_pembelian
               //           join penawaran on penawaran.id_transaksi=transaksi.id_transaksi
               //           join detail_transaksi_penawaran on detail_transaksi_penawaran.id_penawaran=penawaran.id_penawaran
               //           join pelanggan on transaksi.id_pelanggan = pelanggan.id_pelanggan
               //           join pengguna on transaksi.id = pengguna.id
               //           group by kode_transaksi,no_penjualan 
               //           having jumlah_detail_penjualan > sum(ifnull(jumlah_detail_pembelian,0))");

               return DB::select("SELECT *, jumlah_detail_penjualan,jumlah_detail_pembelian from transaksi 
        join penawaran on penawaran.id_transaksi=transaksi.id_transaksi
        join detail_transaksi_penawaran on detail_transaksi_penawaran.id_penawaran=penawaran.id_penawaran
        join penjualan on penjualan.id_transaksi=transaksi.id_transaksi
        join detail_transaksi_penjualan on penjualan.id_penjualan=detail_transaksi_penjualan.id_penjualan
        left join pembelian on pembelian.id_penjualan=penjualan.id_penjualan
        left join detail_transaksi_pembelian on detail_transaksi_pembelian.id_pembelian=pembelian.id_pembelian
        join pelanggan on pelanggan.id_pelanggan=transaksi.id_pelanggan
        join pengguna on pengguna.id=transaksi.id
        join produk on detail_transaksi_penjualan.id_produk=produk.id_produk
		group by penjualan.id_penjualan,no_penjualan
        having   jumlah_detail_penjualan>sum( ifnull(jumlah_detail_pembelian,0))");




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

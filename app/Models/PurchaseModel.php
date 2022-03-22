<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PurchaseModel extends Model
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
                ->join("pemasok", 'transaksi.id_pemasok', '=', 'pemasok.id_pemasok')
                ->join("pengguna", 'transaksi.id', '=', 'pengguna.id')
                ->join('penjualan', "penjualan.id_transaksi", "transaksi.id_transaksi")
                ->join('pembelian', "pembelian.id_transaksi", "transaksi.id_transaksi")
                ->where('no_pembelian', "=", $id)
                ->paginate(1);
        } else {

            return DB::table('transaksi')
                
                ->join('penawaran', 'penawaran.id_transaksi', '=', 'transaksi.id_transaksi')
                ->join('detail_transaksi_penawaran', 'detail_transaksi_penawaran.id_penawaran', '=', 'penawaran.id_penawaran')
                ->join("produk", 'detail_transaksi_penawaran.id_produk', '=', 'produk.id_produk')
                ->join("pelanggan", 'transaksi.id_pelanggan', '=', 'pelanggan.id_pelanggan')
                ->join("pemasok", 'transaksi.id_pemasok', '=', 'pemasok.id_pemasok')
                ->join("pengguna", 'transaksi.id', '=', 'pengguna.id')
                ->join('penjualan', "penjualan.id_transaksi", "transaksi.id_transaksi")
                ->join('pembelian', "pembelian.id_transaksi", "transaksi.id_transaksi")
                ->where('status_transaksi', "=", 'purchase')
                ->groupBy('no_pembelian')
                ->orderBy('tgl_pembelian', 'asc')
                ->paginate(5);
        }
    }
    public function show($kode_transaksi)
    {
        return DB::table('transaksi')

            ->join('penawaran', 'penawaran.id_transaksi', '=', 'transaksi.id_transaksi')
            ->join('detail_transaksi_penawaran', 'detail_transaksi_penawaran.id_penawaran', '=', 'penawaran.id_penawaran')
            ->join('penjualan', 'penjualan.id_transaksi', '=', 'transaksi.id_transaksi')
            ->join('detail_transaksi_penjualan', 'detail_transaksi_penjualan.id_penjualan', '=', 'penjualan.id_penjualan')
            ->join("produk", 'detail_transaksi_penawaran.id_produk', '=', 'produk.id_produk')
            ->join("pelanggan", 'transaksi.id_pelanggan', '=', 'pelanggan.id_pelanggan')
            ->join("pengguna", 'transaksi.id', '=', 'pengguna.id')

            ->where('kode_transaksi', '=', $kode_transaksi)
            ->get();
    }

    public function edit($kode_transaksi)
    {
        return DB::table('transaksi')
            ->selectRaw('transaksi.id_transaksi,penjualan.id_penjualan,penjualan.tgl_penjualan,penjualan.no_penjualan,detail_transaksi_penjualan.id_produk,detail_transaksi_penjualan.jumlah_detail_penjualan')
            ->join('penjualan', 'penjualan.id_transaksi', '=', 'transaksi.id_transaksi')
            ->join('detail_transaksi_penjualan', 'detail_transaksi_penjualan.id_penjualan', '=', 'penjualan.id_penjualan')
            ->where('kode_transaksi', '=', $kode_transaksi)
            ->get();
    }

    public function no_pembelian($tgl_pembelian, $id_pemasok)
    {
        $arr_pembelian = [];
        if (count($id_pemasok) > 1) {
            for ($i = 0; $i < count($id_pemasok); $i++) {
                $no_pembelian =
                    DB::table('pembelian')
                    ->selectRaw("ifnull(max(substring(no_pembelian,4,1)),0)+1 as no_pembelian")
                    ->where("tgl_pembelian", "=", $tgl_pembelian)
                    ->first();
                $no_pembelian = (int)$no_pembelian->no_pembelian;
                $no_pembelian+=$i;
                array_push($arr_pembelian, $no_pembelian);
            }
        } 
        else {
            $no_pembelian =
                DB::table('pembelian')
                ->selectRaw("ifnull(max(substring(no_pembelian,4,1)),0)+1 as no_pembelian")
                ->where("tgl_pembelian", "=", $tgl_pembelian)
                ->first();
            $no_pembelian = (int)$no_pembelian->no_pembelian;
            array_push($arr_pembelian, $no_pembelian);
        }


        return $arr_pembelian;
    }



    public function insert_penjualan($id_transaksi, $data_pembelian, $data_detail_pembelian, $id_pemasok, $kode_transaksi)
    {

        // mengubah data transaksi
        $update = [
            'status_transaksi' => "purchase",
            'id_pemasok' => $id_pemasok,
        ];
        for ($i = 0; $i <= count($id_transaksi) - 1; $i++) {
            DB::table('transaksi')
                ->where("id_transaksi", "=", $id_transaksi[$i])
                ->update($update);
        }

        // insert data transaksi


        DB::table('pembelian')->insert($data_pembelian);

        // perispan data detail transaksi penjualan

        // dump($data_pembelian);
        // dd($data_pembelian[0]['no_pembelian']);

        for ($i = 0; $i < count($id_transaksi); $i++) {
            $id_pembelian = DB::table('pembelian')->select('id_pembelian')->where('no_pembelian', "=",$data_pembelian[$i]['no_pembelian'])->get();
            $data_detail_pembelian[$i]['id_pembelian'] = $id_pembelian[0]->id_pembelian;
        }
        //     insert data detail penjualan transaksi

        DB::table('detail_transaksi_pembelian')->insert($data_detail_pembelian);
    
        // kodingan jurnal pembelian utang

        // foreach($data_pembelian as $dp){
        //     $nominal = DB::table('pembelian')
        //         ->selectRaw("harga*jumlah_detail_pembelian")
        //         ->join('transaksi', 'pembelian.id_transaksi', '=', 'transaksi.id_transaksi')
        //         ->join('detail_transaksi', 'pembelian.id_transaksi', '=', 'transaksi.id_transaksi')
        //         ->where('pembelian.id_pembelian', '=', $data_pembelian)
        //         ->first();
        // }

     

        // $jurnal = [
        //     [
        //         "id_transaksi" => $id_transaksi[0],
        //         'kode_akun' => 500,
        //         'tgl_jurnal' => $data_pembelian[0]['tgl_pembelian'],
        //         'nominal' => $nominal->subtotal,
        //         'posisi_db_cr' => "debit"
        //     ],
        //     [
        //         "id_transaksi" => $id_transaksi[0],
        //         'kode_akun' => 200,
        //         'tgl_jurnal' => $data_pembelian[0]['tgl_pembelian'],
        //         'nominal' => $nominal->subtotal,
        //         'posisi_db_cr' => "kredit"
        //     ],
        // ];
        // DB::table('jurnal')->insert($jurnal);

        // return  $data_pembelian[0]['no_pembelian'];
    }

    public function detail($no_pembelian)
    {
        return DB::table('transaksi')

            ->join('penawaran', 'penawaran.id_transaksi', '=', 'transaksi.id_transaksi')
            ->join('detail_transaksi_penawaran', 'detail_transaksi_penawaran.id_penawaran', '=', 'penawaran.id_penawaran')
            ->join("produk", 'detail_transaksi_penawaran.id_produk', '=', 'produk.id_produk')
            ->join("pelanggan", 'transaksi.id_pelanggan', '=', 'pelanggan.id_pelanggan')
            ->join("pemasok", 'transaksi.id_pemasok', '=', 'pemasok.id_pemasok')
            ->join("pengguna", 'transaksi.id', '=', 'pengguna.id')
            ->join('penjualan', "penjualan.id_transaksi", "transaksi.id_transaksi")
            ->join('pembelian', "pembelian.id_transaksi", "transaksi.id_transaksi")
            ->where('no_pembelian', "=", $no_pembelian)
            ->get();
    }
}

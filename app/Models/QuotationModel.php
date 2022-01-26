<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class QuotationModel extends Model
{
    use HasFactory;
    public function index($id = null)
    {
        if ($id){

            return DB::table('penawaran')
    
                ->join("transaksi", "penawaran.id_transaksi", "=", "transaksi.id_transaksi")
                ->join("detail_transaksi_penawaran", "penawaran.id_penawaran", "=", "detail_transaksi_penawaran.id_penawaran")
                ->join('pelanggan','transaksi.id_pelanggan','=', 'pelanggan.id_pelanggan')
                ->join('pengguna','transaksi.id','=', 'pengguna.id')
                ->groupBy("kode_transaksi")
                ->where([
                    ['status_transaksi', '=', 'quotation'],
                    ['no_penawaran','=',$id]
                ])
                ->orderBy("tgl_penawaran", "asc")
                ->paginate(5);
        }
        return DB::table('penawaran')

            ->join("transaksi", "penawaran.id_transaksi", "=", "transaksi.id_transaksi")
            ->join("detail_transaksi_penawaran", "penawaran.id_penawaran", "=", "detail_transaksi_penawaran.id_penawaran")
            ->join('pelanggan','transaksi.id_pelanggan','=', 'pelanggan.id_pelanggan')
            ->join('pengguna','transaksi.id','=', 'pengguna.id')
            ->groupBy("kode_transaksi")
            ->where('status_transaksi', '=', 'quotation')
            ->orderBy("tgl_penawaran", "asc")
            ->paginate(5);
    }
    public function id()
    {
        $id_produk = DB::table('produk')->get();
        $id_pelanggan = DB::table('pelanggan')->get();
        $data = [
            'produk' => $id_produk,
            'pelanggan' => $id_pelanggan,
        ];
        return $data;
    }

    public function insert_pembantu($data)
    {

        DB::table('pembantu_penawaran')->insert($data);
    }
    public function insert($data_transaksi, $data_penawaran, $data_detail_penawaran, $tgl_penawaran)
    {
        // insert into table transaksi

        DB::table('transaksi')->insert($data_transaksi);

        // menentukan id_transaksi
        $id_transaksi = DB::table('transaksi')
            ->select("id_transaksi")
            ->where("kode_transaksi", "=", $data_transaksi[0]["kode_transaksi"])->get();

        //  menentukan no penawaran
        $no_penawaran =
            DB::table('penawaran')
            ->selectRaw("DISTINCT ifnull(max(substring(no_penawaran,5,1)),0)+1  as no_penawaran")
            ->where("tgl_penawaran", "=", $tgl_penawaran)
            ->first();
        $no_penawaran = (int)$no_penawaran->no_penawaran;



        $tgl_penawaran = explode("-", $data_penawaran[0]["tgl_penawaran"]);
        $tahun = $tgl_penawaran[0];
        $bulan = $tgl_penawaran[1];
        $hari = $tgl_penawaran[2];
        $no_penawaran = "QTH/$no_penawaran/$tahun/$bulan/$hari";

        // nyiapin data penawarang 
        for ($i = 0; $i <= count($id_transaksi) - 1; $i++) {
            foreach ($id_transaksi[$i] as $it) {
                $data_penawaran[$i]["id_transaksi"] = $it;
                $data_penawaran[$i]["no_penawaran"] = $no_penawaran;
            }
        }

        // insert data penawaran
        DB::table('penawaran')->insert($data_penawaran);


        // persiapan Detail Penawaran
        $id_penawaran = [];
        for ($i = 0; $i <= count($id_transaksi) - 1; $i++) {
            foreach ($id_transaksi[$i] as $ip) {
                ${"id_penawaran$i"} = DB::table('penawaran')->select("id_penawaran")->where("id_transaksi", "=", $ip)->get();
                ${"id_penawaran$i"} =  ${"id_penawaran$i"}[0]->id_penawaran;
                $data_detail_penawaran[$i]["id_penawaran"] = ${"id_penawaran$i"};
            }
        }
        //    insert data detail Penawaran
        DB::table('detail_transaksi_penawaran')->insert($data_detail_penawaran);

        // hapus isi tabel Pembantu
        DB::table('pembantu_penawaran')->where("kode_transaksi", "=", $data_transaksi[0]["kode_transaksi"])->delete();


        return $no_penawaran;
    }

    public function history($id_pelanggan)
    {
        return DB::table('penawaran')
            ->join("transaksi", "penawaran.id_transaksi", "=", "transaksi.id_transaksi")
            ->join("detail_transaksi_penawaran", "penawaran.id_penawaran", "=", "detail_transaksi_penawaran.id_penawaran")
            ->join('produk', "detail_transaksi_penawaran.id_produk", "=", "produk.id_produk")
            ->where("id_pelanggan", "=", $id_pelanggan)
            ->orderBy("tgl_penawaran", "desc")
            ->limit(3)
            ->get();
    }

    public function deta()
    {
        return DB::table('penawaran')

            ->join("transaksi", "penawaran.id_transaksi", "=", "transaksi.id_transaksi")
            ->join("detail_transaksi_penawaran", "penawaran.id_penawaran", "=", "detail_transaksi_penawaran.id_penawaran")
            ->groupBy("kode_transaksi")
            ->orderBy("tgl_penawaran", "desc")
            ->get();
    }
    public function show_data()
    {
        $id_transaksi = DB::table('transaksi')->max('id_transaksi');
        $kode_transaksi = DB::table('transaksi')->select('kode_transaksi')->where('id_transaksi', '=', $id_transaksi)->first();
        $kode_transaksi = $kode_transaksi->kode_transaksi;

        return DB::table('transaksi')
            ->join('penawaran', 'penawaran.id_transaksi', '=', 'transaksi.id_transaksi')
            ->join('detail_transaksi_penawaran', 'detail_transaksi_penawaran.id_penawaran', '=', 'penawaran.id_penawaran')
            ->join('produk', 'detail_transaksi_penawaran.id_produk', '=', 'produk.id_produk')
            ->join('pelanggan', 'transaksi.id_pelanggan', '=', 'pelanggan.id_pelanggan')
            ->join('pengguna', 'transaksi.id', '=', 'pengguna.id')
            ->where([['kode_transaksi', '=', $kode_transaksi], ['status_transaksi', '=', "quotation"]])
            ->get();
    }

    public function show($kode_transaksi)
    {
        return DB::table('transaksi')
            ->join('penawaran', 'penawaran.id_transaksi', '=', 'transaksi.id_transaksi')
            ->join('detail_transaksi_penawaran', 'detail_transaksi_penawaran.id_penawaran', '=', 'penawaran.id_penawaran')
            ->join('produk', 'detail_transaksi_penawaran.id_produk', '=', 'produk.id_produk')
            ->join('pelanggan', 'transaksi.id_pelanggan', '=', 'pelanggan.id_pelanggan')
            ->join('pengguna', 'transaksi.id', '=', 'pengguna.id')
            ->where('kode_transaksi', '=', $kode_transaksi)
            ->get();
    }
}

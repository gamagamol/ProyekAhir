<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DeliveryModel extends Model
{
    use HasFactory;

    public function index($id=null)
    {
        if ($id) {
            return DB::table('transaksi')
                ->join("pelanggan", "transaksi.id_pelanggan", "=", "pelanggan.id_pelanggan")
                ->join("pengguna", "transaksi.id", "=", "pengguna.id")
                ->join('penawaran', "penawaran.id_transaksi", "=", "transaksi.id_transaksi")
                ->join('pengiriman', "pengiriman.id_transaksi", "=", "transaksi.id_transaksi")
                ->join('detail_transaksi_pengiriman', "detail_transaksi_pengiriman.id_pengiriman", "=", "pengiriman.id_pengiriman")
                ->join('detail_transaksi_penawaran', "detail_transaksi_penawaran.id_penawaran", "=", "penawaran.id_penawaran")
                ->join("produk", "detail_transaksi_pengiriman.id_produk", "=", "produk.id_produk")
               ->where('no_pengiriman','=',$id)
                ->paginate(5);
        }
        else{

            return DB::table('transaksi')
              
                ->orderBy('tgl_pengiriman','asc')
                ->paginate(5);
        }

    }

    public function show($no_penerimaan)
    {
        return DB::table('transaksi')

            ->join("pelanggan", "transaksi.id_pelanggan", "=", "pelanggan.id_pelanggan")
            ->join("pemasok", "transaksi.id_pemasok", "=", "pemasok.id_pemasok")
            ->join("pengguna", "transaksi.id", "=", "pengguna.id")
            ->join('penawaran', "penawaran.id_transaksi", "=", "transaksi.id_transaksi")
            ->join('detail_transaksi_penawaran', "detail_transaksi_penawaran.id_penawaran", "=", "penawaran.id_penawaran")
            ->join('penerimaan_barang', "penerimaan_barang.id_transaksi", "=", "transaksi.id_transaksi")
            ->join('detail_penerimaan_barang','detail_penerimaan_barang.id_penerimaan_barang','=','penerimaan_barang.id_penerimaan_barang')
            ->join("produk", "detail_penerimaan_barang.id_produk", "=", "produk.id_produk")
            ->where([
                ['no_penerimaan',"=",$no_penerimaan],
                ['status_transaksi',"=",'goods'],
            ])           
            ->get();
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

    public function no_delivery($tgl_pengiriman,$unit)
    {
        //  menentukan no delivery
        if ($unit) {
       $arr_no_pengiriman=[];
            for ($i = 0; $i < count($unit); $i++) {

                $no_pengiriman =
                    DB::table('pengiriman')
                    ->selectRaw("DISTINCT ifnull(max(substring(no_pengiriman,4,1)),0) +1 as no_pengiriman")
                    ->where("tgl_pengiriman", "=", $tgl_pengiriman)
                    ->first();
                $no_pengiriman = (int)$no_pengiriman->no_pengiriman;
                $no_pengiriman+=$i;
                array_push($arr_no_pengiriman,$no_pengiriman);
            }
         
            return $arr_no_pengiriman;
        }else{
            $no_pengiriman =
                DB::table('pengiriman')
                ->selectRaw("DISTINCT ifnull(max(substring(no_pengiriman,4,1)),0) +1 as no_pengiriman")
                ->where("tgl_pengiriman", "=", $tgl_pengiriman)
                ->first();
            $no_pengiriman = (int)$no_pengiriman->no_pengiriman;
            return $no_pengiriman;

        }
    }

    public function insert_delivery($id_transaksi, $data_pengirimian, $data_detail_pengiriman,$unit)
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



        for ($i = 0; $i < count($id_transaksi); $i++) {
            $id_pengiriman = DB::table('pengiriman')->select('id_pengiriman')->where('id_transaksi', "=", $id_transaksi[$i])->get();
            $data_detail_pengiriman[$i]['id_pengiriman'] = $id_pengiriman[0]->id_pengiriman;
        }


        //     insert data detail penjualan transaksi
        for ($i = 0; $i < count($id_transaksi); $i++) {
            # code...
            DB::table('detail_transaksi_pengiriman')->insert($data_detail_pengiriman[$i]);
        }

        if ($unit) {
            for ($i = 0; $i < count($data_detail_pengiriman); $i++) {

                $id_pengiriman = DB::table('pengiriman')->select('id_pengiriman')->where('no_pengiriman', "=", $data_pengirimian[$i]['no_pengiriman'])->first();

                $data_detail_pengiriman[$i]['id_pengiriman'] = $id_pengiriman->id_pengiriman;
            }
            // dd($data_detail_pengiriman);
            //     insert data detail penjualan transaksi
            DB::table('detail_transaksi_pengiriman')->insert($data_detail_pengiriman);
        } else {
            for ($i = 0; $i < count($data_detail_pengiriman); $i++) {
                $id_pengiriman = DB::table('pengiriman')->select('id_pengiriman')->where('no_pengiriman', "=", $data_pengirimian[$i]['no_pengiriman'])->get();

                $data_detail_pengiriman[$i]['id_pengiriman'] = $id_pengiriman[$i]->id_pengiriman;
            }
            //     insert data detail penjualan transaksi
            DB::table('detail_transaksi_pengiriman')->insert($data_detail_pengiriman);
        }







    }

    public function detail($no_pengiriman)
    {
        return DB::table('transaksi')
            ->join("pelanggan", "transaksi.id_pelanggan", "=", "pelanggan.id_pelanggan")
            ->join("pemasok", "transaksi.id_pemasok", "=", "pemasok.id_pemasok")
            ->join("pengguna", "transaksi.id", "=", "pengguna.id")
            ->join('penawaran', "penawaran.id_transaksi", "=", "transaksi.id_transaksi")
            ->join('pengiriman', "pengiriman.id_transaksi", "=", "transaksi.id_transaksi")
            ->join('detail_transaksi_pengiriman', "detail_transaksi_pengiriman.id_pengiriman", "=", "pengiriman.id_pengiriman")
            ->join('detail_transaksi_penawaran', "detail_transaksi_penawaran.id_penawaran", "=", "penawaran.id_penawaran")
            ->join("produk", "detail_transaksi_pengiriman.id_produk", "=", "produk.id_produk")
            ->where('no_pengiriman', '=', $no_pengiriman)
            ->get();
    }


    public function edit($no_penerimaan){
        // return DB::table('transaksi')
        //     ->selectRaw('transaksi.id_transaksi,pembelian.id_pembelian,penerimaan_barang.tgl_penerimaan,penerimaan_barang.no_penerimaan,detail_penerimaan_barang.id_produk,jumlah_detail_penerimaan,id_penjualan')
            
        //     ->join('penerimaan_barang','penerimaan_barang.id_transaksi','=','transaksi.id_transaksi')
        //     ->join('detail_penerimaan_barang','detail_penerimaan_barang.id_penerimaan_barang','=', 'penerimaan_barang.id_penerimaan_barang')
        //     ->join('penjualan','penjualan.id_transaksi','=','transaksi.id_transaksi')
        //     ->where('no_penerimaan', '=', $no_penerimaan)
        //     ->get();


        return DB::select("SELECT transaksi.id_transaksi , id_penjualan ,penerimaan_barang.id_penerimaan_barang ,jumlah_detail_penerimaan,tgl_penerimaan,detail_penerimaan_barang.id_produk FROM ibaraki_db.transaksi 
        join penjualan on  penjualan.id_transaksi=transaksi.id_transaksi
        join penerimaan_barang on penerimaan_barang.id_transaksi=transaksi.id_transaksi
        join detail_penerimaan_barang on detail_penerimaan_barang.id_penerimaan_barang = penerimaan_barang.id_penerimaan_barang
        where no_penerimaan='$no_penerimaan'");
    }


    
}

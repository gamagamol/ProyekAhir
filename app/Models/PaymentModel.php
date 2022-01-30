<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PaymentModel extends Model
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
                ->join('pembayaran', "pembayaran.id_transaksi", "=", "transaksi.id_transaksi")
                ->where("no_pembayaran","=",$id)
                ->paginate(1);
        }
        else{

            return DB::table('transaksi')
                ->join("pelanggan", "transaksi.id_pelanggan", "=", "pelanggan.id_pelanggan")
                ->join("pengguna", "transaksi.id", "=", "pengguna.id")
                ->join('penawaran', "penawaran.id_transaksi", "=", "transaksi.id_transaksi")
                ->join('pengiriman', "pengiriman.id_transaksi", "=", "transaksi.id_transaksi")
                ->join('detail_transaksi_pengiriman', "detail_transaksi_pengiriman.id_pengiriman", "=", "pengiriman.id_pengiriman")
                ->join('detail_transaksi_penawaran', "detail_transaksi_penawaran.id_penawaran", "=", "penawaran.id_penawaran")
                ->join("produk", "detail_transaksi_pengiriman.id_produk", "=", "produk.id_produk")
                ->join('pembayaran', "pembayaran.id_transaksi", "=", "transaksi.id_transaksi")
                ->groupBy("no_pembayaran", "tgl_pembayaran")
                ->orderBy("tgl_pembayaran", "asc")
    
    
                ->paginate(5);
        }
    }

    public function detail($no_transaksi)
    {
        return DB::table('transaksi')
            ->join("pelanggan", "transaksi.id_pelanggan", "=", "pelanggan.id_pelanggan")
            ->join("pengguna", "transaksi.id", "=", "pengguna.id")
            ->join('penawaran', "penawaran.id_transaksi", "=", "transaksi.id_transaksi")
            ->join('pengiriman', "pengiriman.id_transaksi", "=", "transaksi.id_transaksi")
            ->join('detail_transaksi_pengiriman', "detail_transaksi_pengiriman.id_pengiriman", "=", "pengiriman.id_pengiriman")
            ->join('detail_transaksi_penawaran', "detail_transaksi_penawaran.id_penawaran", "=", "penawaran.id_penawaran")
            ->join("produk", "detail_transaksi_pengiriman.id_produk", "=", "produk.id_produk")
            ->join('pembayaran', "pembayaran.id_transaksi", "=", "transaksi.id_transaksi")
            ->where('no_pembayaran', "=", $no_transaksi)
            ->get();
    }

    public function create($id, $tgl_tagihan)
    {

        return DB::table('transaksi')
            ->join("pelanggan", "transaksi.id_pelanggan", "=", "pelanggan.id_pelanggan")
            ->join("pengguna", "transaksi.id", "=", "pengguna.id")
            ->join('penawaran', "penawaran.id_transaksi", "=", "transaksi.id_transaksi")
            ->join('pengiriman', "pengiriman.id_transaksi", "=", "transaksi.id_transaksi")
            ->join('detail_transaksi_pengiriman', "detail_transaksi_pengiriman.id_pengiriman", "=", "pengiriman.id_pengiriman")
            ->join('detail_transaksi_penawaran', "detail_transaksi_penawaran.id_penawaran", "=", "penawaran.id_penawaran")
            ->join("produk", "detail_transaksi_pengiriman.id_produk", "=", "produk.id_produk")
            ->join('tagihan', "tagihan.id_transaksi", "=", "transaksi.id_transaksi")
            ->where('no_tagihan', "=", $id)
            ->where('kode_transaksi', "=", $tgl_tagihan)
            ->get();
    }

    public function insert($id_transaksi, $data_pembayaran, $data_detail_pembayaran)
    {
        // mengubah data transaksi

        for ($i = 0; $i < count($id_transaksi); $i++) {
            DB::table('transaksi')
                ->where("id_transaksi", "=", $id_transaksi[$i])
                ->update(['status_transaksi' => "payment"]);
        }

        // insert payment table
        DB::table('pembayaran')->insert($data_pembayaran);

        // insert payment detail table
        for ($i = 0; $i < count($id_transaksi); $i++) {
            $id_pembayaran = DB::table('pembayaran')->select('id_pembayaran')->where('id_transaksi', "=", $id_transaksi[$i])->get();

            $data_detail_pembayaran[$i]['id_pembayaran'] = $id_pembayaran[0]->id_pembayaran;
        }



        for ($i = 0; $i < count($id_transaksi); $i++) {

            DB::table('detail_transaksi_pembayaran')->insert($data_detail_pembayaran[$i]);
        }

        // perubahan jurnal
        $nominal = DB::table('jurnal')
            ->select('nominal')
            ->where('id_transaksi', "=", $id_transaksi[0])
            ->where('kode_akun', "=", 112)
            ->first();
        $nominal = $nominal->nominal;

        $jurnal = [
            [
                "id_transaksi" => $id_transaksi[0],
                'kode_akun' => 111,
                'tgl_jurnal' => $data_pembayaran[0]['tgl_pembayaran'],
                'nominal' => $nominal,
                'posisi_db_cr' => "debit"
            ],
            [
                "id_transaksi" => $id_transaksi[0],
                'kode_akun' => 112,
                'tgl_jurnal' => $data_pembayaran[0]['tgl_pembayaran'],
                'nominal' => $nominal,
                'posisi_db_cr' => "kredit"
            ],
        ];

        DB::table('jurnal')->insert($jurnal);
    }
}

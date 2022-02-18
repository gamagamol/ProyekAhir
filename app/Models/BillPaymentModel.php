<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BillPaymentModel extends Model
{
    use HasFactory;

    public function index($id=null)
    {
        if ($id) {
            return DB::table('transaksi')
                ->selectRaw("tgl_tagihan,nama_pelanggan,no_tagihan,berat,total,layanan,nama_pengguna, DATE_ADD(tgl_tagihan, INTERVAL 31 DAY) AS DUE_DATE,no_pengiriman,kode_transaksi")
                ->join("pelanggan", "transaksi.id_pelanggan", "=", "pelanggan.id_pelanggan")
                ->join("pengguna", "transaksi.id", "=", "pengguna.id")
                ->join('penawaran', "penawaran.id_transaksi", "=", "transaksi.id_transaksi")
                ->join('pengiriman', "pengiriman.id_transaksi", "=", "transaksi.id_transaksi")
                ->join('detail_transaksi_pengiriman', "detail_transaksi_pengiriman.id_pengiriman", "=", "pengiriman.id_pengiriman")
                ->join('detail_transaksi_penawaran', "detail_transaksi_penawaran.id_penawaran", "=", "penawaran.id_penawaran")
                ->join("produk", "detail_transaksi_pengiriman.id_produk", "=", "produk.id_produk")
                ->join("tagihan", "tagihan.id_transaksi", "=", "transaksi.id_transaksi")
                ->where('no_tagihan', "=", $id)
                ->paginate(1);  
        }else{

            return DB::table('transaksi')
                ->selectRaw("tgl_tagihan,nama_pelanggan,no_tagihan,berat,total,layanan,nama_pengguna, DATE_ADD(tgl_tagihan, INTERVAL 31 DAY) AS DUE_DATE,no_pengiriman,kode_transaksi")
                ->join("pelanggan", "transaksi.id_pelanggan", "=", "pelanggan.id_pelanggan")
                ->join("pengguna", "transaksi.id", "=", "pengguna.id")
                ->join('penawaran', "penawaran.id_transaksi", "=", "transaksi.id_transaksi")
                ->join('pengiriman', "pengiriman.id_transaksi", "=", "transaksi.id_transaksi")
                ->join('detail_transaksi_pengiriman', "detail_transaksi_pengiriman.id_pengiriman", "=", "pengiriman.id_pengiriman")
                ->join('detail_transaksi_penawaran', "detail_transaksi_penawaran.id_penawaran", "=", "penawaran.id_penawaran")
                ->join("produk", "detail_transaksi_pengiriman.id_produk", "=", "produk.id_produk")
                ->join("tagihan", "tagihan.id_transaksi", "=", "transaksi.id_transaksi")
                ->where('status_transaksi', "=", "bill")
                ->groupBy('tgl_tagihan', "no_tagihan")
                ->orderBy('tgl_tagihan','asc')
                ->paginate(5);
        }
    }
    public function create($id_transaksi, $tgl_pegiriman)
    {
        // mencari nomor pembayaran 
        $no_pengiriman = DB::table("pengiriman")
            ->select('no_pengiriman')
            ->join("transaksi", "pengiriman.id_transaksi", "=", "transaksi.id_transaksi")
            ->where('transaksi.id_transaksi', "=", $id_transaksi)
            ->where('tgl_pengiriman', "=", $tgl_pegiriman)
            ->first();
        $no_pengiriman = $no_pengiriman->no_pengiriman;
        // mencari id_transaksi
        $id_transaksi = DB::table('pengiriman')
            ->select('id_transaksi')
            ->where('no_pengiriman', "=", $no_pengiriman)
            ->where('tgl_pengiriman', "=", $tgl_pegiriman)
            ->get();
        $id_transaksi1 = [];
        for ($i = 0; $i < count($id_transaksi); $i++) {
            $id_transaksi1[] = $id_transaksi[$i]->id_transaksi;
        }




        $data = [];
        for ($i = 0; $i < count($id_transaksi); $i++) {
            # code...
            $dataa = DB::table('transaksi')
                ->join("pelanggan", "transaksi.id_pelanggan", "=", "pelanggan.id_pelanggan")
                ->join("pengguna", "transaksi.id", "=", "pengguna.id")
                ->join('penawaran', "penawaran.id_transaksi", "=", "transaksi.id_transaksi")
                ->join('pengiriman', "pengiriman.id_transaksi", "=", "transaksi.id_transaksi")
                ->join('detail_transaksi_pengiriman', "detail_transaksi_pengiriman.id_pengiriman", "=", "pengiriman.id_pengiriman")
                ->join('detail_transaksi_penawaran', "detail_transaksi_penawaran.id_penawaran", "=", "penawaran.id_penawaran")
                ->join("produk", "detail_transaksi_pengiriman.id_produk", "=", "produk.id_produk")
                ->where('transaksi.id_transaksi', '=', $id_transaksi[$i]->id_transaksi)
                ->get();

            $data[] = $dataa[0];
        }
        $array = [
            'data' => $data,
            'id_transaksi' => $id_transaksi1
        ];
        return $array;
    }
    public function no_tagihan($tgl_tagihan)
    {
        $no_tagihan =
            DB::table('tagihan')
            ->selectRaw("DISTINCT ifnull(max(substring(no_tagihan,5,1)),0)+1 as no_tagihan")
            ->where("tgl_tagihan", "=", $tgl_tagihan)
            ->first();
        $no_tagihan = (int)$no_tagihan->no_tagihan;
      

        return $no_tagihan;
    }

    public function insert($data_transaksi, $data_tagihan, $id_transaksi)
    {

        // insert table data transaksi
        for ($i = 0; $i < count($id_transaksi); $i++) {

            DB::table('transaksi')
                ->where('id_transaksi', "=", $id_transaksi[$i])->update($data_transaksi);
        }

        // // insert table data tagihan
        DB::table('tagihan')->insert($data_tagihan);



        // DB::table('detail_transaksi_pembayaran')->insert($data_detail_pembayaran);

        //   prepare insert to jurnal
        $total_nominal = 0;
        $total_ppn = 0;
        $total_ongkir = 0;
        $total = 0;
        for ($i = 0; $i < count($id_transaksi); $i++) {

            $nominal = DB::table('transaksi')
                ->selectRaw('total,ppn,ongkir,subtotal')
                ->where('id_transaksi', "=", $id_transaksi[$i])
                ->first();
            $total_nominal += $nominal->subtotal;
            $total_ppn += $nominal->ppn;
            $total_ongkir = $nominal->ongkir;
            $total += $nominal->total;
        }



        // jurnal tagihan
        $jurnal = [
            [
                "id_transaksi" => $id_transaksi[0],
                'kode_akun' => 112,
                'tgl_jurnal' => $data_tagihan[0]['tgl_tagihan'],
                'nominal' => $total,
                'posisi_db_cr' => "debit"
            ],
            [
                "id_transaksi" => $id_transaksi[0],
                'kode_akun' => 211,
                'tgl_jurnal' => $data_tagihan[0]['tgl_tagihan'],
                'nominal' => $total_ppn,
                'posisi_db_cr' => "kredit"
            ],
            [
                "id_transaksi" => $id_transaksi[0],
                'kode_akun' => 212,
                'tgl_jurnal' => $data_tagihan[0]['tgl_tagihan'],
                'nominal' => $total_ongkir,
                'posisi_db_cr' => "kredit"
            ],
            [
                "id_transaksi" => $id_transaksi[0],
                'kode_akun' => 411,
                'tgl_jurnal' => $data_tagihan[0]['tgl_tagihan'],
                'nominal' => $total_nominal,
                'posisi_db_cr' => "kredit"
            ],
        ];
        DB::table('jurnal')->insert($jurnal);
    }

    public function detail($no_tagihan)
    {
        return DB::table('transaksi')
            ->join("pelanggan", "transaksi.id_pelanggan", "=", "pelanggan.id_pelanggan")
            ->join("pengguna", "transaksi.id", "=", "pengguna.id")
            ->join('penawaran', "penawaran.id_transaksi", "=", "transaksi.id_transaksi")
            ->join('pembelian', "pembelian.id_transaksi", "=", "transaksi.id_transaksi")
            ->join('pengiriman', "pengiriman.id_transaksi", "=", "transaksi.id_transaksi")
            ->join('detail_transaksi_pengiriman', "detail_transaksi_pengiriman.id_pengiriman", "=", "pengiriman.id_pengiriman")
            ->join('detail_transaksi_penawaran', "detail_transaksi_penawaran.id_penawaran", "=", "penawaran.id_penawaran")
            ->join("produk", "detail_transaksi_pengiriman.id_produk", "=", "produk.id_produk")
            ->join("tagihan", "tagihan.id_transaksi", "=", "transaksi.id_transaksi")
            ->where('no_tagihan', "=", $no_tagihan)

            ->get();
    }
}

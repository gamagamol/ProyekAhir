<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PaymentModel extends Model
{
    use HasFactory;

    public function index($id = null)
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
              
                ->where("no_pembayaran", "=", $id)
                ->paginate(1);
        } else {

            return DB::table('transaksi')
                ->join("pelanggan", "transaksi.id_pelanggan", "=", "pelanggan.id_pelanggan")
                ->join("pengguna", "transaksi.id", "=", "pengguna.id")
                ->join('penawaran', "penawaran.id_transaksi", "=", "transaksi.id_transaksi")
                ->join('pengiriman', "pengiriman.id_transaksi", "=", "transaksi.id_transaksi")
                ->join('detail_transaksi_pengiriman', "detail_transaksi_pengiriman.id_pengiriman", "=", "pengiriman.id_pengiriman")
                ->join('detail_transaksi_penawaran', "detail_transaksi_penawaran.id_penawaran", "=", "penawaran.id_penawaran")
                ->join("produk", "detail_transaksi_pengiriman.id_produk", "=", "produk.id_produk")
                ->join('pembayaran', "pembayaran.id_transaksi", "=", "transaksi.id_transaksi")
                ->join('penerimaan_barang', 'pengiriman.id_penerimaan_barang','=','penerimaan_barang.id_penerimaan_barang')
                ->groupBy("no_pembayaran", "tgl_pembayaran")
                ->orderBy("tgl_pembayaran", "asc")

           
                ->paginate(5);
        }
    }

    public function detail($no_transaksi)
    {
        return  DB::select("SELECT *,penerimaan_barang.no_penerimaan,no_pengiriman, transaksi.id_transaksi , penjualan.id_penjualan , penerimaan_barang.id_penerimaan_barang ,penawaran.id_penawaran,jumlah_detail_penerimaan,
            case 
            when jumlah_detail_pengiriman > 0 then sum(jumlah_detail_pengiriman)
            end as
            sudah_terkirim,
            jumlah_detail_pengiriman,
            sisa_detail_pengiriman ,detail_penerimaan_barang.id_produk FROM ibaraki_db.transaksi 
            join penjualan on  penjualan.id_transaksi=transaksi.id_transaksi
            join penerimaan_barang on penerimaan_barang.id_transaksi=transaksi.id_transaksi
            join detail_penerimaan_barang on detail_penerimaan_barang.id_penerimaan_barang = penerimaan_barang.id_penerimaan_barang
            join penawaran on penawaran.id_transaksi = transaksi.id_transaksi
            left join pengiriman on pengiriman.id_transaksi = transaksi.id_transaksi
            left join detail_transaksi_pengiriman on detail_transaksi_pengiriman.id_pengiriman=pengiriman.id_pengiriman 
            join pelanggan on pelanggan.id_pelanggan=transaksi.id_pelanggan
            join pengguna on pengguna.id=transaksi.id
            join produk on detail_penerimaan_barang.id_produk=produk.id_produk
           
            join tagihan on tagihan.id_pengiriman=pengiriman.id_pengiriman
			where no_penerimaan='$no_transaksi'  and jumlah_detail_penerimaan > jumlah_detail_pengiriman
            group by transaksi.id_transaksi");
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

    public function insert($id_transaksi, $data_pembayaran, $data_detail_pembayaran,$nominal=null)
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
        if ($nominal==null) {
            $nominal = DB::table('jurnal')
                ->select('nominal')
                ->where('id_transaksi', "=", $id_transaksi[0])
                ->where('kode_akun', "=", 112)
                ->first();
            $nominal = $nominal->nominal;  
        }
  

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

    public function show($no_penerimaan)
    {
        return  DB::select("SELECT *,penerimaan_barang.no_penerimaan,no_pengiriman, transaksi.id_transaksi , penjualan.id_penjualan , penerimaan_barang.id_penerimaan_barang ,penawaran.id_penawaran,jumlah_detail_penerimaan,
            case 
            when jumlah_detail_pengiriman > 0 then sum(jumlah_detail_pengiriman)
            end as
            sudah_terkirim,
            jumlah_detail_pengiriman,
            sisa_detail_pengiriman ,detail_penerimaan_barang.id_produk FROM ibaraki_db.transaksi 
            join penjualan on  penjualan.id_transaksi=transaksi.id_transaksi
            join penerimaan_barang on penerimaan_barang.id_transaksi=transaksi.id_transaksi
            join detail_penerimaan_barang on detail_penerimaan_barang.id_penerimaan_barang = penerimaan_barang.id_penerimaan_barang
            join penawaran on penawaran.id_transaksi = transaksi.id_transaksi
            left join pengiriman on pengiriman.id_transaksi = transaksi.id_transaksi
            left join detail_transaksi_pengiriman on detail_transaksi_pengiriman.id_pengiriman=pengiriman.id_pengiriman 
            join pelanggan on pelanggan.id_pelanggan=transaksi.id_pelanggan
            join pengguna on pengguna.id=transaksi.id
            join produk on detail_penerimaan_barang.id_produk=produk.id_produk
           
            join tagihan on tagihan.id_pengiriman=pengiriman.id_pengiriman
			where no_penerimaan='$no_penerimaan'  and jumlah_detail_penerimaan > jumlah_detail_pengiriman
            group by transaksi.id_transaksi");
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use function app\helper\no_transaksi;


class PaymentVendorModel extends Model
{
    use HasFactory;


    public function index($serch = null)
    {

        if ($serch) {
            return DB::table('transaksi')
                ->selectRaw('nama_pemasok,no_pembelian,tgl_pembelian, DATE_ADD(tgl_pembelian, INTERVAL 45 DAY) AS DUE_DATE,subtotal')
                ->join('penawaran', 'penawaran.id_transaksi', '=', 'transaksi.id_transaksi')
                ->join('detail_transaksi_penawaran', 'detail_transaksi_penawaran.id_penawaran', '=', 'penawaran.id_penawaran')
                ->join("produk", 'detail_transaksi_penawaran.id_produk', '=', 'produk.id_produk')
                ->join("pelanggan", 'transaksi.id_pelanggan', '=', 'pelanggan.id_pelanggan')
                ->join("pemasok", 'transaksi.id_pemasok', '=', 'pemasok.id_pemasok')
                ->join("pengguna", 'transaksi.id', '=', 'pengguna.id')
                ->join('penjualan', "penjualan.id_transaksi", "transaksi.id_transaksi")
                ->join('pembelian', "pembelian.id_transaksi", "transaksi.id_transaksi")
                ->where('no_pembelian', '=', $serch)
                ->get();
        }
        return DB::table('transaksi')

            ->join('penawaran', 'penawaran.id_transaksi', '=', 'transaksi.id_transaksi')
            ->join('detail_transaksi_penawaran', 'detail_transaksi_penawaran.id_penawaran', '=', 'penawaran.id_penawaran')
            ->join("produk", 'detail_transaksi_penawaran.id_produk', '=', 'produk.id_produk')
            ->join("pelanggan", 'transaksi.id_pelanggan', '=', 'pelanggan.id_pelanggan')
            ->join("pengguna", 'transaksi.id', '=', 'pengguna.id')
            ->join('penjualan', "penjualan.id_transaksi", "transaksi.id_transaksi")
            ->join('pembelian', "pembelian.id_penjualan", "penjualan.id_penjualan")
            ->join("pemasok", 'pembelian.id_pemasok', '=', 'pemasok.id_pemasok')
            ->whereNotIn('id_pembelian', function ($query) {
                $query->select('id_pembelian')
                    ->from('pembayaranvendor');
            })
            ->groupBy('no_pembelian')
            ->orderByDesc('tgl_pembelian')
            // ->toSql();
            ->get();
    }

    public function show($no_pembelian)
    {
        return DB::table('transaksi')

            ->join('penawaran', 'penawaran.id_transaksi', '=', 'transaksi.id_transaksi')
            ->join('detail_transaksi_penawaran', 'detail_transaksi_penawaran.id_penawaran', 'penawaran.id_penawaran')
            ->join("pelanggan", 'transaksi.id_pelanggan', '=', 'pelanggan.id_pelanggan')
            ->join("pengguna", 'transaksi.id', '=', 'pengguna.id')
            ->join('penjualan', "penjualan.id_transaksi", "transaksi.id_transaksi")
            ->join('pembelian', "pembelian.id_transaksi", "transaksi.id_transaksi")
            ->join("pemasok", 'pembelian.id_pemasok', '=', 'pemasok.id_pemasok')
            ->join('detail_transaksi_pembelian', 'detail_transaksi_pembelian.id_pembelian', 'pembelian.id_pembelian')
            ->join("produk", 'detail_transaksi_pembelian.id_produk', '=', 'produk.id_produk')
            ->where('no_pembelian', "=", $no_pembelian)
            ->get();
    }

    public function edit($kode_transaksi)
    {
        return DB::table('transaksi')
            ->selectRaw('transaksi.id_transaksi,pembelian.id_pembelian,pembelian.tgl_pembelian,pembelian.no_pembelian,detail_transaksi_pembelian.id_produk')
            ->join('pembelian', 'pembelian.id_transaksi', '=', 'transaksi.id_transaksi')
            ->join('detail_transaksi_pembelian', 'detail_transaksi_pembelian.id_pembelian', '=', 'pembelian.id_pembelian')
            ->where('no_pembelian', '=', $kode_transaksi)
            ->get();
    }

    public function no_penjualan($tgl_penjualan)
    {

        $no_pembayaran_vendor = DB::select("
           select * from pembayaranvendor where idpembayaranvendor =(select max(idpembayaranvendor) from pembayaranvendor 
           where month(tgl_pembayaran_vendor)='$tgl_penjualan' and YEAR(tgl_pembayaran_vendor)='$tgl_penjualan' )");
        if ($no_pembayaran_vendor != null) {

            $no_pembayaran_vendor = no_transaksi($no_pembayaran_vendor[0]->no_pembayaran_vendor);
        } else {
            $no_pembayaran_vendor = 1;
        }

        return $no_pembayaran_vendor;
    }



    public function insert($data_pembayaran_vendor)
    {
        DB::table('pembayaranvendor')->insert($data_pembayaran_vendor);



        $total = DB::table('pembelian')
            ->selectRaw('sum(subtotal_detail_pembelian) as total')
            ->join('transaksi', 'transaksi.id_transaksi', 'pembelian.id_transaksi')
            ->join('detail_transaksi_pembelian', 'pembelian.id_pembelian', 'detail_transaksi_pembelian.id_pembelian')
            ->where('no_pembelian', '=', $data_pembayaran_vendor[0]['no_pembayaran_vendor'])
            ->first();

        // dd($total);



        $jurnal = [
            [
                'id_transaksi' => $data_pembayaran_vendor[0]['id_transaksi'],
                'kode_akun' => 200,
                'tgl_jurnal' => $data_pembayaran_vendor[0]['tgl_pembayaran_vendor'],
                'nominal' => $total->total,
                'posisi_db_cr' => 'debit',

            ],
            [
                'id_transaksi' => $data_pembayaran_vendor[0]['id_transaksi'],
                'kode_akun' => 111,
                'tgl_jurnal' => $data_pembayaran_vendor[0]['tgl_pembayaran_vendor'],
                'nominal' => $total->total,
                'posisi_db_cr' => 'kredit',

            ],
        ];


        DB::table('jurnal')->insert($jurnal);
    }

    public function detail($no_pembelian)
    {
        return DB::table('transaksi')

            ->join('penawaran', 'penawaran.id_transaksi', '=', 'transaksi.id_transaksi')
            ->join('detail_transaksi_penawaran', 'detail_transaksi_penawaran.id_penawaran', '=', 'penawaran.id_penawaran')
            ->join("produk", 'detail_transaksi_penawaran.id_produk', '=', 'produk.id_produk')
            ->join("pelanggan", 'transaksi.id_pelanggan', '=', 'pelanggan.id_pelanggan')
            ->join("pengguna", 'transaksi.id', '=', 'pengguna.id')
            ->join('penjualan', "penjualan.id_transaksi", "transaksi.id_transaksi")
            ->join('pembelian', "pembelian.id_transaksi", "transaksi.id_transaksi")
            ->join("pemasok", 'pembelian.id_pemasok', '=', 'pemasok.id_pemasok')
            ->join('detail_transaksi_pembelian', 'pembelian.id_pembelian', 'detail_transaksi_pembelian.id_pembelian')
            ->where('no_pembelian', "=", $no_pembelian)
            ->get();
    }

    public function report($id = null)
    {
        if ($id) {
            return DB::table('transaksi')

                ->join('penawaran', 'penawaran.id_transaksi', '=', 'transaksi.id_transaksi')
                ->join('detail_transaksi_penawaran', 'detail_transaksi_penawaran.id_penawaran', '=', 'penawaran.id_penawaran')
                ->join("produk", 'detail_transaksi_penawaran.id_produk', '=', 'produk.id_produk')
                ->join('pembelian', "pembelian.id_transaksi", "transaksi.id_transaksi")
                ->join("pemasok", 'pembelian.id_pemasok', '=', 'pemasok.id_pemasok')
                ->join('pembayaranvendor', "pembayaranvendor.id_transaksi", "transaksi.id_transaksi")
                ->where('no_pembayaran_vendor', '=', $id)
                ->get();
        } else {
            return DB::table('transaksi')
                ->selectRaw('nama_pemasok,no_pembelian,tgl_pembelian,no_pembayaran_vendor,tgl_pembayaran_vendor,sum(subtotal_detail_pembelian) as subtotal_detail_pembelian')
                ->join('penjualan', 'penjualan.id_transaksi', 'transaksi.id_transaksi')
                ->join('pembelian', 'pembelian.id_penjualan', 'penjualan.id_penjualan')
                ->join('pemasok', 'pemasok.id_pemasok', 'pembelian.id_pemasok')
                ->join('pembayaranvendor', 'pembelian.id_pembelian', 'pembayaranvendor.id_pembelian')
                ->join('detail_transaksi_pembelian', 'pembelian.id_pembelian', 'detail_transaksi_pembelian.id_pembelian')
                ->groupBy('pembelian.no_pembelian')
                ->get();
        }
    }
}

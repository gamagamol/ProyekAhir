<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
                ->paginate(1);
        }
        return DB::table('transaksi')

            ->join('penawaran', 'penawaran.id_transaksi', '=', 'transaksi.id_transaksi')
            ->join('detail_transaksi_penawaran', 'detail_transaksi_penawaran.id_penawaran', '=', 'penawaran.id_penawaran')
            ->join("produk", 'detail_transaksi_penawaran.id_produk', '=', 'produk.id_produk')
            ->join("pelanggan", 'transaksi.id_pelanggan', '=', 'pelanggan.id_pelanggan')
            ->join("pemasok", 'transaksi.id_pemasok', '=', 'pemasok.id_pemasok')
            ->join("pengguna", 'transaksi.id', '=', 'pengguna.id')
            ->join('penjualan', "penjualan.id_transaksi", "transaksi.id_transaksi")
            ->join('pembelian', "pembelian.id_transaksi", "transaksi.id_transaksi")
            ->whereNotIn('id_pembelian', function ($query) {
                $query->select('id_pembelian')
                    ->from('pembayaranvendor');
            })
            ->groupBy('no_pembelian')
            ->paginate(5);
    }

    public function show($no_pembelian)
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

    public function edit($kode_transaksi)
    {
        return DB::table('transaksi')
            ->selectRaw('transaksi.id_transaksi,pembelian.id_pembelian,pembelian.tgl_pembelian,pembelian.no_pembelian,detail_transaksi_pembelian.id_produk')
            ->join('pembelian', 'pembelian.id_transaksi', '=', 'transaksi.id_transaksi')
            ->join('detail_transaksi_pembelian', 'detail_transaksi_pembelian.id_pembelian', '=', 'pembelian.id_pembelian')
            ->where('kode_transaksi', '=', $kode_transaksi)
            ->get();
    }

    public function insert($data_pembayaran_vendor)
    {
        DB::table('pembayaranvendor')->insert($data_pembayaran_vendor);

        // data transaksi
        $kode_transaksi = DB::table('transaksi')
            ->select('kode_transaksi')
            ->where('id_transaksi', '=', $data_pembayaran_vendor[0]['id_transaksi'])
            ->first();

        $total = DB::table('transaksi')
            ->selectRaw('sum(subtotal) as total')
            ->where('kode_transaksi', '=', $kode_transaksi->kode_transaksi)
            ->first();



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
            ->join("pemasok", 'transaksi.id_pemasok', '=', 'pemasok.id_pemasok')
            ->join("pengguna", 'transaksi.id', '=', 'pengguna.id')
            ->join('penjualan', "penjualan.id_transaksi", "transaksi.id_transaksi")
            ->join('pembelian', "pembelian.id_transaksi", "transaksi.id_transaksi")
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
                ->join("pemasok", 'transaksi.id_pemasok', '=', 'pemasok.id_pemasok')
                ->join('pembelian', "pembelian.id_transaksi", "transaksi.id_transaksi")
                ->join('pembayaranvendor', "pembayaranvendor.id_transaksi", "transaksi.id_transaksi")
                ->where('no_pembayaran_vendor', '=', $id)
                ->paginate(1);
        } else {
            return DB::table('transaksi')

                ->join('penawaran', 'penawaran.id_transaksi', '=', 'transaksi.id_transaksi')
                ->join('detail_transaksi_penawaran', 'detail_transaksi_penawaran.id_penawaran', '=', 'penawaran.id_penawaran')
                ->join("produk", 'detail_transaksi_penawaran.id_produk', '=', 'produk.id_produk')
                ->join("pemasok", 'transaksi.id_pemasok', '=', 'pemasok.id_pemasok')
                ->join('pembelian', "pembelian.id_transaksi", "transaksi.id_transaksi")
                ->join('pembayaranvendor', "pembayaranvendor.id_transaksi", "transaksi.id_transaksi")
                ->groupBy('no_pembelian')
                ->paginate(5);
        }
    }
}

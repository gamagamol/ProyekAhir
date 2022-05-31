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
            ->join('detail_transaksi_penawaran','detail_transaksi_penawaran.id_penawaran','penawaran.id_penawaran')
            ->join("pelanggan", 'transaksi.id_pelanggan', '=', 'pelanggan.id_pelanggan')
            ->join("pemasok", 'transaksi.id_pemasok', '=', 'pemasok.id_pemasok')
            ->join("pengguna", 'transaksi.id', '=', 'pengguna.id')
            ->join('penjualan', "penjualan.id_transaksi", "transaksi.id_transaksi")
            ->join('pembelian', "pembelian.id_transaksi", "transaksi.id_transaksi")
            ->join('detail_transaksi_pembelian','detail_transaksi_pembelian.id_pembelian','pembelian.id_pembelian')
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
        $no_penjualan =
            DB::table('pembayaranvendor')
            ->selectRaw("DISTINCT ifnull(max(substring(no_penjualan,4,1)),0)+1 as no_penjualan")
            ->where("tgl_penjualan", "=", $tgl_penjualan)
            ->first();
        $no_penjualan = (int)$no_penjualan->no_penjualan;


        return $no_penjualan;
    }



    public function insert($data_pembayaran_vendor)
    {
        DB::table('pembayaranvendor')->insert($data_pembayaran_vendor);

        

        $total = DB::table('pembelian')
            ->selectRaw('sum(subtotal_detail_pembelian) as total')
            ->join('transaksi','transaksi.id_transaksi','pembelian.id_transaksi')
            ->join('detail_transaksi_pembelian','pembelian.id_pembelian','detail_transaksi_pembelian.id_pembelian')
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
            ->join("pemasok", 'transaksi.id_pemasok', '=', 'pemasok.id_pemasok')
            ->join("pengguna", 'transaksi.id', '=', 'pengguna.id')
            ->join('penjualan', "penjualan.id_transaksi", "transaksi.id_transaksi")
            ->join('pembelian', "pembelian.id_transaksi", "transaksi.id_transaksi")
            ->join('detail_transaksi_pembelian','pembelian.id_pembelian','detail_transaksi_pembelian.id_pembelian')
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
                ->selectRaw('nama_pemasok,no_pembelian,tgl_pembelian,no_pembayaran_vendor,tgl_pembayaran_vendor,sum(subtotal_detail_pembelian) as subtotal_detail_pembelian')
                ->join('pemasok','pemasok.id_pemasok','transaksi.id_pemasok')
                ->join('penjualan','penjualan.id_transaksi','transaksi.id_transaksi')
                ->join('pembelian','pembelian.id_penjualan','penjualan.id_penjualan')
                ->join('pembayaranvendor','pembelian.id_pembelian','pembayaranvendor.id_pembelian')
                ->join('detail_transaksi_pembelian','pembelian.id_pembelian','detail_transaksi_pembelian.id_pembelian')
                ->groupBy('no_pembelian')
                ->get();
        }
    }
}

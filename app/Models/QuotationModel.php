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
        if ($id == 'ALL') {
            $id = '';
        }

        if ($id) {

            return DB::table('penawaran')

                ->join("transaksi", "penawaran.id_transaksi", "=", "transaksi.id_transaksi")
                ->join("detail_transaksi_penawaran", "penawaran.id_penawaran", "=", "detail_transaksi_penawaran.id_penawaran")
                ->join('pelanggan', 'transaksi.id_pelanggan', '=', 'pelanggan.id_pelanggan')
                ->join('pengguna', 'transaksi.id', '=', 'pengguna.id')
                ->join('pegawai', 'transaksi.id_pegawai', '=', 'pegawai.id_pegawai')
                ->groupBy("kode_transaksi")
                ->where('no_penawaran', '=', $id)
                ->orderBy("tgl_penawaran", "asc")
                ->paginate(5);
        }
        return DB::table('penawaran')

            ->join("transaksi", "penawaran.id_transaksi", "=", "transaksi.id_transaksi")
            ->join("detail_transaksi_penawaran", "penawaran.id_penawaran", "=", "detail_transaksi_penawaran.id_penawaran")
            ->join('pelanggan', 'transaksi.id_pelanggan', '=', 'pelanggan.id_pelanggan')
            ->join('pengguna', 'transaksi.id', '=', 'pengguna.id')
            ->join('pegawai', 'transaksi.id_pegawai', '=', 'pegawai.id_pegawai')
            ->groupBy("kode_transaksi")
            // ->where('status_transaksi', '=', 'quotation')
            ->orderByRaw("tgl_penawaran desc,no_penawaran desc")
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
            ->join('pegawai', 'transaksi.id_pegawai', '=', 'pegawai.id_pegawai')
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
            ->join('pegawai', 'transaksi.id_pegawai', '=', 'pegawai.id_pegawai')
            ->where('kode_transaksi', '=', $kode_transaksi)
            ->get();
    }

    public function updateIdTidakTerpakai($id_transaksi, $data)
    {

        DB::table('transaksi')->whereIn('id_transaksi', $id_transaksi)->update($data);
        DB::table('penawaran')->whereIn('id_transaksi', $id_transaksi)->update($data);
    }

    public function quotationDetailReport($month = null, $date = null)
    {

        $query = '';
        if ($month && $date == null) {
            $query = "WHERE MONTH(p.tgl_penawaran)=$month";
        } elseif ($date && $month == null) {
            $query = "WHERE DAY(p.tgl_penawaran)=$date";
        } elseif ($month && $date) {
            $query = "WHERE MONTH(p.tgl_penawaran)=$month and DAY(p.tgl_penawaran)=$date";
        }




        return DB::select("select b.*, (select sum(jumlah) from transaksi 
				join penawaran on transaksi.id_transaksi = penawaran.id_transaksi
				where no_penawaran=b.no_penawaran and penawaran.tidak_terpakai=0) jumlah_penjualan,
				(SELECT sum(jumlah_detail_pembelian) FROM transaksi 
				join penawaran on penawaran.id_transaksi = transaksi.id_transaksi
                 join penjualan on penjualan.id_transaksi = penawaran.id_transaksi
                 join pembelian on pembelian.id_penjualan = penjualan.id_penjualan
                 LEFT join detail_transaksi_pembelian on detail_transaksi_pembelian.id_pembelian = pembelian.id_pembelian
                where no_penawaran=b.no_penawaran) jumlah_pembelian,
				(SELECT sum(jumlah_detail_penerimaan) FROM transaksi 
				join penawaran on penawaran.id_transaksi = transaksi.id_transaksi
                 join penjualan on penjualan.id_transaksi = penawaran.id_transaksi
                 join pembelian on pembelian.id_penjualan = penjualan.id_penjualan
				join penerimaan_barang on penerimaan_barang.id_pembelian = pembelian.id_pembelian
                join detail_penerimaan_barang on penerimaan_barang.id_penerimaan_barang = detail_penerimaan_barang.id_penerimaan_barang
                where no_penawaran=b.no_penawaran
                ) jumlah_detail_penerimaan,
                (SELECT sum( jumlah_detail_pengiriman) from penerimaan_barang 
                join detail_penerimaan_barang on detail_penerimaan_barang.id_penerimaan_barang = penerimaan_barang.id_penerimaan_barang
                join transaksi on penerimaan_barang.id_transaksi = transaksi.id_transaksi
                join penawaran on transaksi.id_transaksi = penawaran.id_transaksi
                left join pengiriman on pengiriman.id_penerimaan_barang = penerimaan_barang.id_penerimaan_barang
                left join detail_transaksi_pengiriman on pengiriman.id_pengiriman = detail_transaksi_pengiriman.id_pengiriman
                join penjualan on penjualan.id_transaksi=penawaran.id_transaksi
				where penawaran.no_penawaran=b.no_penawaran
                )jumlah_detail_pengiriman
                from (
						SELECT 
						 t.id_transaksi,p.tgl_penawaran,p.no_penawaran,
						tgl_penjualan,pj.id_penjualan,pj.no_penjualan,
						t.tebal_transaksi,t.panjang_transaksi,lebar_transaksi,t.berat,
						t.jumlah,t.harga,t.total,t.layanan,pemasok.nama_pemasok FROM transaksi t
						join penawaran p on t.id_transaksi = p.id_transaksi
						join penjualan pj on p.id_transaksi = pj.id_transaksi
						left join pembelian pm on pm.id_penjualan = pj.id_penjualan
						left join penerimaan_barang pb on pb.id_pembelian=pm.id_pembelian
						left join pengiriman pg on pg.id_penerimaan_barang = pb.id_penerimaan_barang
                        left join pemasok on pemasok.id_pemasok=pm.id_pemasok
                        $query
					
                        
						) b");
    }



    public function getDateQuotation()
    {
        return DB::select("SELECT tgl_penawaran as tanggal,month(tgl_penawaran) as bulan_penawaran,day(tgl_penawaran) as tgl_penawaran FROM transaksi t
						join penawaran p on t.id_transaksi = p.id_transaksi
						join penjualan pj on p.id_transaksi = pj.id_transaksi
						left join pembelian pm on pm.id_penjualan = pj.id_penjualan
						left join penerimaan_barang pb on pb.id_pembelian=pm.id_pembelian
						left join pengiriman pg on pg.id_penerimaan_barang = pb.id_penerimaan_barang
                        left join pemasok on pemasok.id_pemasok=pm.id_pemasok
						group by p.no_penawaran");
    }


    public function customerOmzetReport($month = null, $date = null)
    {

        $query = '';
        if ($month && $date == null) {
            $query = "and MONTH(p.tgl_penawaran)=$month";
        } elseif ($date && $month == null) {
            $query = "and DAY(p.tgl_penawaran)=$date";
        } elseif ($month && $date) {
            $query = "and MONTH(p.tgl_penawaran)=$month and DAY(p.tgl_penawaran)=$date";
        }




        return  DB::select("
        select b.*,(
            select sum(subtotal) from transaksi 
            join penawaran on transaksi.id_transaksi = penawaran.id_transaksi
            where id_pelanggan = b.id_pelanggan 
            ) as total_penawaran, (
            select sum(subtotal) from transaksi 
            join penawaran on transaksi.id_transaksi = penawaran.id_transaksi
            where id_pelanggan = b.id_pelanggan and penawaran.tidak_terpakai=1
            ) as total_penawaran_loss,(
            select sum(subtotal)  from transaksi 
            join penawaran on transaksi.id_transaksi = penawaran.id_transaksi
            join penjualan on transaksi.id_transaksi=penjualan.id_transaksi
            where transaksi.tidak_terpakai=0 and id_pelanggan = b.id_pelanggan
            ) as total_penjualan from (
            SELECT  pg.id_pelanggan,nama_pegawai ,no_penawaran,no_penjualan,pg.nama_pelanggan FROM transaksi t
            left join penawaran p on t.id_transaksi=p.id_transaksi
            left join penjualan pj on t.id_transaksi = pj.id_transaksi
            left join pelanggan pg on pg.id_pelanggan = t.id_pelanggan
            left join pegawai pw on pw.id_pegawai = t.id_pegawai
            where pw.jabatan_pegawai='SALES' $query
            group by  pg.id_pelanggan
            ) b ");
    }



    public function outStandingReport($month = null, $date = null)
    {

        $query = '';
        if ($month && $date == null) {
            $query = "and MONTH(pj.tgl_penjualan)=$month";
        } elseif ($date && $month == null) {
            $query = "and DAY(pj.tgl_penjualan)=$date";
        } elseif ($month && $date) {
            $query = "and MONTH(pj.tgl_penjualan)=$month and DAY(pj.tgl_penjualan)=$date";
        }


        return DB::select("
        SELECT t.id_transaksi,
	   tgl_penjualan,pj.id_penjualan,pj.no_penjualan,
	   t.tebal_transaksi,t.panjang_transaksi,lebar_transaksi,
       t.berat,t.jumlah,t.harga,t.total,t.layanan,
       pemasok.nama_pemasok,nama_produk,no_pengiriman,
       tgl_pengiriman,nama_pegawai,nama_pelanggan FROM transaksi t
	   join penawaran p on t.id_transaksi = p.id_transaksi
	   join penjualan pj on p.id_transaksi = pj.id_transaksi
	   left join pembelian pm on pm.id_penjualan = pj.id_penjualan
	   left join penerimaan_barang pb on pb.id_pembelian=pm.id_pembelian
	   left join pengiriman pg on pg.id_penerimaan_barang = pb.id_penerimaan_barang
	   left join pemasok on pemasok.id_pemasok=pm.id_pemasok
	   left join detail_transaksi_penjualan on detail_transaksi_penjualan.id_penjualan = pj.id_penjualan
	   left join produk on produk.id_produk = detail_transaksi_penjualan.id_produk
	   left join pegawai on pegawai.id_pegawai = t.id_pegawai
       left join pelanggan on pelanggan.id_pelanggan = t.id_pelanggan
       where t.tidak_terpakai=0 and jabatan_pegawai='SALES' $query
        ");
    }


    public function quotationReport($month = null, $date = null)
    {

        // echo $month;
        // echo $date;die;

        $query = '';
        if ($month && $date == null) {
            $query = "AND MONTH(p.tgl_penawaran)=$month";
        } elseif ($date && $month == null) {
            $query = "AND DAY(p.tgl_penawaran)=$date";
        } elseif ($month && $date) {
            $query = "AND MONTH(p.tgl_penawaran)=$month and DAY(p.tgl_penawaran)=$date";
        }

       

        return DB::select("select b.*,(select sum(total) from transaksi 
                            join penawaran on transaksi.id_transaksi = penawaran.id_transaksi
                            where transaksi.tidak_terpakai=0 and no_penawaran=b.no_penawaran
                            ) as total_penjualan
                from (
						SELECT 
						 t.id_transaksi,p.tgl_penawaran,p.no_penawaran,
						tgl_penjualan,pj.id_penjualan,pj.no_penjualan,
						t.tebal_transaksi,t.panjang_transaksi,lebar_transaksi,t.berat,
						t.jumlah,t.harga,t.total,t.layanan,pemasok.nama_pemasok,subtotal,ppn,ongkir,
                        t.total as total_transaksi,nama_pelanggan,nama_pegawai
                        FROM transaksi t
						join penawaran p on t.id_transaksi = p.id_transaksi
						join penjualan pj on p.id_transaksi = pj.id_transaksi
						left join pembelian pm on pm.id_penjualan = pj.id_penjualan
						left join penerimaan_barang pb on pb.id_pembelian=pm.id_pembelian
						left join pengiriman pg on pg.id_penerimaan_barang = pb.id_penerimaan_barang
                        left join pemasok on pemasok.id_pemasok=pm.id_pemasok
                        left join pelanggan on pelanggan.id_pelanggan = t.id_pelanggan
                        left join pegawai on pegawai.id_pegawai=t.id_pegawai
                        where jabatan_pegawai='SALES'$query
						group by p.no_penawaran
						) b ");
    }
}

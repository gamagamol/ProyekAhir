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

            // return DB::table('transaksi')

            //     ->join('penawaran', 'penawaran.id_transaksi', '=', 'transaksi.id_transaksi')
            //     ->join('detail_transaksi_penawaran', 'detail_transaksi_penawaran.id_penawaran', '=', 'penawaran.id_penawaran')
            //     ->join('penjualan', 'penjualan.id_transaksi', '=', 'transaksi.id_transaksi')
            //     ->join('detail_transaksi_penjualan', 'detail_transaksi_penjualan.id_penjualan', '=', 'penjualan.id_penjualan')
            //     ->join('pembelian', 'pembelian.id_transaksi', '=', 'transaksi.id_transaksi')
            //     ->join('detail_transaksi_pembelian', 'detail_transaksi_pembelian.id_pembelian', '=', 'pembelian.id_pembelian')
            //     ->join("produk", 'detail_transaksi_penawaran.id_produk', '=', 'produk.id_produk')
            //     ->join("pelanggan", 'transaksi.id_pelanggan', '=', 'pelanggan.id_pelanggan')
            //     ->join("pemasok", 'transaksi.id_pemasok', '=', 'pemasok.id_pemasok')
            //     ->join("pengguna", 'transaksi.id', '=', 'pengguna.id')
            //     ->groupBy('pembelian.no_pembelian')
            //     ->orderBy('tgl_pembelian', 'DESC')
            //     ->paginate(5);

            return DB::select(
                "SELECT * FROM pembelian join detail_transaksi_pembelian 
            on pembelian.id_pembelian=detail_transaksi_pembelian.id_pembelian
            join transaksi on transaksi.id_transaksi=pembelian.id_transaksi
            join produk on detail_transaksi_pembelian.id_produk=produk.id_produk
			join penawaran on penawaran.id_transaksi=transaksi.id_transaksi
            join detail_transaksi_penawaran on detail_transaksi_penawaran.id_penawaran=penawaran.id_penawaran
            join pelanggan on pelanggan.id_pelanggan=transaksi.id_pelanggan
            join pengguna on pengguna.id=transaksi.id
            left join penerimaan_barang on penerimaan_barang.id_pembelian=pembelian.id_pembelian
            left join detail_penerimaan_barang on penerimaan_barang.id_penerimaan_barang=detail_penerimaan_barang.id_penerimaan_barang
          left   join pemasok on transaksi.id_pemasok  = pemasok.id_pemasok
            group by no_pembelian
            -- having jumlah_detail_penerimaan is null
             order by tgl_pembelian desc
         "
            );
        }
    }
    public function show($kode_transaksi)
    {


        // return DB::select("SELECT *,
        //     sum(ifnull(jumlah_detail_pembelian,0)) as total_unit_pembelian ,sum(total_detail_pembelian)
        //     FROM penjualan join detail_transaksi_penjualan 
        //     on penjualan.id_penjualan=detail_transaksi_penjualan.id_penjualan
        //     join transaksi on transaksi.id_transaksi=penjualan.id_transaksi
        //     left outer join pembelian on pembelian.id_transaksi=transaksi.id_transaksi
        //     LEFT OUTER join detail_transaksi_pembelian 
        //     on pembelian.id_pembelian=detail_transaksi_pembelian.id_pembelian
        //     join penawaran on penawaran.id_transaksi=transaksi.id_transaksi
        //     join detail_transaksi_penawaran on detail_transaksi_penawaran.id_penawaran=penawaran.id_penawaran
        //     join pelanggan on pelanggan.id_pelanggan=transaksi.id_pelanggan
        //     join pengguna on pengguna.id=transaksi.id
        //     join produk on detail_transaksi_penjualan.id_produk=produk.id_produk

        //     group by kode_transaksi,detail_transaksi_penjualan.id_produk 
        //     having jumlah_detail_penjualan > sum(ifnull(jumlah_detail_pembelian,0)) and kode_transaksi='$kode_transaksi'");

        return DB::select("SELECT *,transaksi.id_transaksi,jumlah_detail_penjualan,jumlah_detail_pembelian  from transaksi 
        join penawaran on penawaran.id_transaksi=transaksi.id_transaksi
        join detail_transaksi_penawaran on detail_transaksi_penawaran.id_penawaran=penawaran.id_penawaran
        join penjualan on penjualan.id_transaksi=transaksi.id_transaksi
        join detail_transaksi_penjualan on penjualan.id_penjualan=detail_transaksi_penjualan.id_penjualan
        left join pembelian on pembelian.id_penjualan=penjualan.id_penjualan
        left join detail_transaksi_pembelian on detail_transaksi_pembelian.id_pembelian=pembelian.id_pembelian
        join pelanggan on pelanggan.id_pelanggan=transaksi.id_pelanggan
        join pengguna on pengguna.id=transaksi.id
        join produk on detail_transaksi_penjualan.id_produk=produk.id_produk
        where kode_transaksi='$kode_transaksi'  
        group by penjualan.id_penjualan
        having   jumlah_detail_penjualan> sum(ifnull(jumlah_detail_pembelian,0))");
    }

    public function edit($kode_transaksi)
    {
        // return DB::table('transaksi')
        //     ->selectRaw('transaksi.id_transaksi,penjualan.id_penjualan,penjualan.tgl_penjualan,penjualan.no_penjualan,detail_transaksi_penjualan.id_produk,detail_transaksi_penjualan.jumlah_detail_penjualan,transaksi.harga,transaksi.berat')
        //     ->join('penjualan', 'penjualan.id_transaksi', '=', 'transaksi.id_transaksi')
        //     ->join('detail_transaksi_penjualan', 'detail_transaksi_penjualan.id_penjualan', '=', 'penjualan.id_penjualan')
        //     ->where('kode_transaksi', '=', $kode_transaksi)
        //     ->get();


        return DB::select("SELECT penawaran.id_penawaran,transaksi.id_transaksi,penjualan.id_penjualan,penjualan.tgl_penjualan,penjualan.no_penjualan
        ,detail_transaksi_penjualan.id_produk,detail_transaksi_penjualan.jumlah_detail_penjualan
        ,transaksi.harga,transaksi.berat,jumlah_detail_pembelian,
        case 
        when jumlah_detail_pembelian is not null then jumlah_detail_penjualan - jumlah_detail_pembelian
        else jumlah_detail_penjualan
        end as jumlah_unit
        from transaksi 
        join  penjualan on penjualan.id_transaksi=transaksi.id_transaksi
        join detail_transaksi_penjualan on detail_transaksi_penjualan.id_penjualan = penjualan.id_penjualan
        left join pembelian on pembelian.id_penjualan=penjualan.id_penjualan
        left join detail_transaksi_pembelian on detail_transaksi_pembelian.id_pembelian=pembelian.id_pembelian
        join penawaran on penawaran.id_transaksi=transaksi.id_transaksi
         where kode_transaksi='$kode_transaksi'
        group by id_penjualan
        having jumlah_detail_penjualan>sum(ifnull(jumlah_detail_pembelian,0))
        
        ");
    }

    public function no_pembelian($tgl_pembelian, $id_pemasok)
    {
        $arr_pembelian = [];
        if (is_array($id_pemasok)) {
            for ($i = 0; $i < count($id_pemasok); $i++) {
                $no_pembelian =
                    DB::table('pembelian')
                    ->selectRaw("ifnull(max(substring(no_pembelian,4,1)),0)+1 as no_pembelian")
                    ->where("tgl_pembelian", "=", $tgl_pembelian)
                    ->first();
                $no_pembelian = (int)$no_pembelian->no_pembelian;
                $no_pembelian += $i;
                array_push($arr_pembelian, $no_pembelian);
            }
        } else {
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
        if (is_array($id_pemasok)) {

            for ($i = 0; $i <= count($id_transaksi) - 1; $i++) {
                DB::table('transaksi')
                    ->where("id_transaksi", "=", $id_transaksi[$i])
                    ->update([
                        'status_transaksi' => 'purchase',
                        'id_pemasok' => $id_pemasok[$i]
                    ]);
            }
        } else {
            DB::table('transaksi')
                ->where('kode_transaksi', '=', $kode_transaksi)
                ->update([
                    'status_transaksi' => 'purchase',
                    'id_pemasok' => $id_pemasok
                ]);
        }
        // insert data transaksi

        DB::table('pembelian')->insert($data_pembelian);

        // perispan data detail transaksi penjualan

        // dd($data_pembelian[0]['no_pembelian']);
        if (is_array($id_pemasok)) {

            for ($i = 0; $i < count($id_transaksi); $i++) {
                $id_pembelian = DB::table('pembelian')->select('id_pembelian')->where('no_pembelian', "=", $data_pembelian[$i]['no_pembelian'])->get();
                $data_detail_pembelian[$i]['id_pembelian'] = $id_pembelian[0]->id_pembelian;
            }
            //     insert data detail penjualan transaksi

            DB::table('detail_transaksi_pembelian')->insert($data_detail_pembelian);
        } else {
            if (count($data_pembelian) > 1) {
                for ($z = 0; $z < count($data_detail_pembelian); $z++) {

                    $id_pembelian = DB::table('pembelian')->select('id_pembelian')->where('no_pembelian', "=", $data_pembelian[$z]['no_pembelian'])->get();

                    $data_detail_pembelian[$z]['id_pembelian'] = $id_pembelian[$z]->id_pembelian;
                }
                DB::table('detail_transaksi_pembelian')->insert($data_detail_pembelian);
            } else {

                $id_pembelian = DB::table('pembelian')
                    ->select('id_pembelian')
                    ->where('no_pembelian', '=', $data_pembelian[0]['no_pembelian'])->first();
                $data_detail_pembelian[0]['id_pembelian'] = $id_pembelian->id_pembelian;

                DB::table('detail_transaksi_pembelian')->insert($data_detail_pembelian);
            }
        }


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
            ->join('pembelian', 'pembelian.id_transaksi', '=', 'transaksi.id_transaksi')
            ->join('detail_transaksi_pembelian', 'detail_transaksi_pembelian.id_pembelian', '=', 'pembelian.id_pembelian')
            ->join('produk', 'detail_transaksi_pembelian.id_produk', '=', 'produk.id_produk')
            ->join('pelanggan', 'transaksi.id_pelanggan', '=', 'pelanggan.id_pelanggan')
            ->join('pengguna', 'transaksi.id', '=', 'pengguna.id')
            ->join('pemasok', 'transaksi.id_pemasok', '=', 'pemasok.id_pemasok')
            ->join('penawaran', 'penawaran.id_transaksi', '=', 'transaksi.id_transaksi')

            ->where('pembelian.no_pembelian', "=", $no_pembelian)
            ->get();
    }
}

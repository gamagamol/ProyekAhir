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
            $query = "where no_pembelian = '$id'";
        } else {
            $query = "";
        }


        // return DB::select(
        //     "SELECT * FROM pembelian join detail_transaksi_pembelian 
        //     on pembelian.id_pembelian=detail_transaksi_pembelian.id_pembelian
        //     join transaksi on transaksi.id_transaksi=pembelian.id_transaksi
        //     join produk on detail_transaksi_pembelian.id_produk=produk.id_produk
        // 	join penawaran on penawaran.id_transaksi=transaksi.id_transaksi
        //     join detail_transaksi_penawaran on detail_transaksi_penawaran.id_penawaran=penawaran.id_penawaran
        //     join pelanggan on pelanggan.id_pelanggan=transaksi.id_pelanggan
        //     join pengguna on pengguna.id=transaksi.id
        //     left join penerimaan_barang on penerimaan_barang.id_pembelian=pembelian.id_pembelian
        //     left join detail_penerimaan_barang on penerimaan_barang.id_penerimaan_barang=detail_penerimaan_barang.id_penerimaan_barang
        //   left   join pemasok on transaksi.id_pemasok  = pemasok.id_pemasok
        //    $query
        //     group by no_pembelian
        //     -- having jumlah_detail_penerimaan is null
        //      order by tgl_pembelian desc,no_pembelian desc
        //  "
        // );
        return DB::select(
            "SELECT p.*,t.nomor_pekerjaan,pe.nama_pelanggan,pm.nama_pemasok,pg.nama_pengguna,dtp.jumlah_detail_penerimaan from pembelian p
            join transaksi t on p.id_transaksi = t.id_transaksi
            join pelanggan pe on pe.id_pelanggan=t.id_pelanggan
            join pemasok pm on pm.id_pemasok = p.id_pemasok
            join pengguna pg on pg.id=t.id
             left join penerimaan_barang pmb on pmb.id_pembelian = p.id_pembelian
            left join detail_penerimaan_barang dtp on dtp.id_penerimaan_barang=pmb.id_penerimaan_barang
            $query
            group by no_pembelian
            order by p.id_pembelian desc
         "
        );
    }
    public function show($kode_transaksi)
    {

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

        return DB::select("SELECT penawaran.id_penawaran,transaksi.id_transaksi,penjualan.id_penjualan,penjualan.tgl_penjualan,penjualan.no_penjualan
        ,detail_transaksi_penjualan.id_produk,detail_transaksi_penjualan.jumlah_detail_penjualan
        ,transaksi.harga,transaksi.harga,berat-ifnull( berat_detail_pembelian,0) as berat,
        jumlah_detail_pembelian,
        case 
        when jumlah_detail_pembelian is not null then jumlah_detail_penjualan - jumlah_detail_pembelian
        else jumlah_detail_penjualan
        end as jumlah_unit,subtotal,
         total,no_pembelian
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

        $bulan_tgl = explode("-", $tgl_pembelian)[1];

        if (gettype($id_pemasok) != 'string') {
            $arr_pembelian = [];
            for ($i = 0; $i < count($id_pemasok); $i++) {
                $no_pembelian = DB::select("SELECT ifnull(max(substring(no_pembelian,4,1)),0)+1 as no_pembelian from transaksi 
                 join pembelian on transaksi.id_transaksi = pembelian.id_transaksi
                where month(tgl_pembelian) = '$bulan_tgl' ");

                $no_pembelian = $no_pembelian[0]->no_pembelian;
                array_push($arr_pembelian, (int)$no_pembelian);
            }

            return $arr_pembelian;
        } else {


            $no_pembelian =
                DB::table('pembelian')
                ->selectRaw("ifnull(max(substring(no_pembelian,4,1)),0)+1 as no_pembelian")
                ->where("tgl_pembelian", "=", $tgl_pembelian)
                ->first();
            $no_pembelian = (int)$no_pembelian->no_pembelian;
            return $no_pembelian;
        }
    }



    public function insert_penjualan($id_transaksi, $data_pembelian, $data_detail_pembelian, $id_pemasok, $kemungkinan, $nominal = null)
    {


        if (gettype($id_pemasok) == 'array') {
            $update_data_transaksi = [];
            for ($i = 0; $i < count($id_pemasok); $i++) {
                $update_data_transaksi[$i] = [
                    'status_transaksi' => 'purchase',
                    // 'id_pemasok' => $id_pemasok[$i]
                ];
            }
        } else {
            $update_data_transaksi = [
                'status_transaksi' => 'purchase',
                // 'id_pemasok' => $id_pemasok
            ];
        }



        if (count($id_transaksi) >= 1 && gettype($id_pemasok) == 'string') {

            for ($i = 0; $i < count($id_transaksi); $i++) {

                DB::table('transaksi')
                    ->where('id_transaksi', $id_transaksi[$i])
                    ->update($update_data_transaksi);
            }
        } elseif (count($id_transaksi) > 1 && gettype($id_pemasok) == 'array') {

            for ($i = 0; $i < count($id_transaksi); $i++) {

                DB::table('transaksi')
                    ->where('id_transaksi', $id_transaksi[$i])
                    ->update($update_data_transaksi[$i]);
            }
        } else if (count($id_transaksi) < 1 && gettype($id_pemasok) == 'string') {

            DB::table('transaksi')->where('id_transaksi', $id_transaksi[0])->update($update_data_transaksi);
        }


        DB::table('pembelian')->insert($data_pembelian);


        if ($kemungkinan == 'A') {


            // ambil id pembelian
            if (count($data_pembelian) > 1) {


                for ($i = 0; $i < count($data_pembelian); $i++) {
                    $id_pembelian = DB::table('pembelian')
                        ->where('id_penjualan', $data_pembelian[$i]['id_penjualan'])
                        ->max('id_pembelian');

                    $data_detail_pembelian[$i]['id_pembelian'] = $id_pembelian;
                }
            } else {

                $id_pembelian = DB::table('pembelian')->max('id_pembelian');
                for ($i = 0; $i < count($data_detail_pembelian); $i++) {
                    $data_detail_pembelian[$i]['id_pembelian'] = $id_pembelian;
                }
            }
        } elseif ($kemungkinan == 'B' || $kemungkinan == 'C') {



            for ($i = 0; $i < count($data_pembelian); $i++) {
                $id_pembelian = DB::table('pembelian')
                    ->where('no_pembelian', $data_pembelian[$i]['no_pembelian'])
                    ->first();
                // dd($id_pembelian);

                $data_detail_pembelian[$i]['id_pembelian'] = $id_pembelian->id_pembelian;
            }
        }

        DB::table('detail_transaksi_pembelian')->insert($data_detail_pembelian);




        // kodingan jurnal pembelian utang


        // dd($data_detail_pembelian);

        $total_pembelian = 0;
        foreach ($data_detail_pembelian as $ddp) {
            // dump($ddp['total_detail_pembelian']);
            $total_pembelian += $ddp['subtotal_detail_pembelian'];
        }




        $jurnal = [
            [
                "id_transaksi" => $id_transaksi[0],
                'kode_akun' => 500,
                'tgl_jurnal' => $data_pembelian[0]['tgl_pembelian'],
                'nominal' => (int)$total_pembelian,
                'posisi_db_cr' => "debit"
            ],
            [
                "id_transaksi" => $id_transaksi[0],
                'kode_akun' => 200,
                'tgl_jurnal' => $data_pembelian[0]['tgl_pembelian'],
                'nominal' => (int)$total_pembelian,
                'posisi_db_cr' => "kredit"
            ],
        ];
        // dump($data_detail_pembelian);
        // dd($jurnal);

        DB::table('jurnal')->insert($jurnal);
    }

    public function detail($no_pembelian)
    {

        return DB::select("SELECT * FROM pembelian 
        join detail_transaksi_pembelian on pembelian.id_pembelian=detail_transaksi_pembelian.id_pembelian
        join penjualan on penjualan.id_penjualan=pembelian.id_penjualan
        join detail_transaksi_penjualan on penjualan.id_penjualan = detail_transaksi_penjualan.id_penjualan
        join transaksi on transaksi.id_transaksi=pembelian.id_transaksi
        join penawaran on penawaran.id_transaksi=transaksi.id_transaksi
        join pelanggan on pelanggan.id_pelanggan = transaksi.id_pelanggan
        join pemasok on pemasok.id_pemasok = pembelian.id_pemasok
        join produk on detail_transaksi_pembelian.id_produk=produk.id_produk
        join pegawai on pegawai.id_pegawai = transaksi.id_pegawai
        join pengguna on pengguna.id = transaksi.id
        where no_pembelian='$no_pembelian' 
        ");
    }

    public function getNoPurchase()
    {
        return DB::table('pembelian')
        ->select('no_pembelian')
        ->join('transaksi','transaksi.id_transaksi','=','pembelian.id_transaksi')
        ->join('penjualan','penjualan.id_transaksi','=','transaksi.id_transaksi')
        ->join('penerimaan_barang','penerimaan_barang.id_pembelian','=','pembelian.id_pembelian')
        ->join('pengiriman','pengiriman.id_penerimaan_barang','=','penerimaan_barang.id_penerimaan_barang')
        ->leftJoin('detail_transaksi_pengiriman', 'detail_transaksi_pengiriman.id_pengiriman','=', 'pengiriman.id_pengiriman')
        ->where('sisa_detail_pengiriman','=','0')
        ->whereNotIn('status_transaksi',['bill','payment'])
        ->get();
    }
}

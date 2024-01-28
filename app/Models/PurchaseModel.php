<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use function app\helper\no_transaksi;
use Exception;

class PurchaseModel extends Model
{
    use HasFactory;

    public  $p_no_pembelian;

    public function index($id = null)
    {
        if ($id) {
            $query = "where no_pembelian = '$id'";
        } else {
            $query = "";
        }



        return DB::select(
            "SELECT p.*,t.nomor_pekerjaan,pe.nama_pelanggan,pm.nama_pemasok,pg.nama_pengguna,dtp.jumlah_detail_penerimaan,no_penjualan,t.nomor_transaksi from pembelian p
            join transaksi t on p.id_transaksi = t.id_transaksi
            join pelanggan pe on pe.id_pelanggan=t.id_pelanggan
            join pemasok pm on pm.id_pemasok = p.id_pemasok
            join pengguna pg on pg.id=t.id
            left join penjualan pj on pj.id_transaksi=t.id_transaksi
            left join penerimaan_barang pmb on pmb.id_pembelian = p.id_pembelian
            left join detail_penerimaan_barang dtp on dtp.id_penerimaan_barang=pmb.id_penerimaan_barang
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


    // public function no_pembelian($tgl_pembelian, $id_pemasok)
    // {



    //     $bulan_tgl = explode("-", $tgl_pembelian);

    //     $arr_pembelian = [];

    //     if (count(array_unique($id_pemasok)) > 1) {
    //         // vendor nya bisa jadi ada yang beda
    //         foreach (array_unique($id_pemasok) as $i => $ip) {
    //             if ($i == 0) {
    //                 $no_pembelian = DB::select(" SELECT max(substr(no_pembelian,4,1)) as no_pembelian from pembelian where month(tgl_pembelian)='$bulan_tgl[1]'
    //                 and YEAR(tgl_pembelian)='$bulan_tgl[0]'");
    //                 if ($no_pembelian != null) {

    //                     $no_pembelian = (int)$no_pembelian[0]->no_pembelian + 1;
    //                 } else {
    //                     $no_pembelian = 1;
    //                 }
    //                 array_push($arr_pembelian, [
    //                     'id_pemasok' => $ip,
    //                     'no_pembelian' => $no_pembelian
    //                 ]);
    //             } else {

    //                 // Cek apakah id_pemasok sudah ada dalam $arr_pembelian
    //                 $ip_sudah_ada = false;

    //                 foreach ($arr_pembelian as $key => $arp) {
    //                     if ((int)$ip == (int)$arp['id_pemasok']) {
    //                         $arr_pembelian[$key]['no_pembelian']++; // Tambahkan nomor pembelian jika pemasok sudah ada
    //                         $ip_sudah_ada = true;
    //                         break;
    //                     }
    //                 }

    //                 if (!$ip_sudah_ada) {
    //                     $nomor_pembelian_terakhir = end($arr_pembelian)['no_pembelian'];
    //                     array_push($arr_pembelian, [
    //                         'id_pemasok' => $ip,
    //                         'no_pembelian' => $nomor_pembelian_terakhir + 1
    //                     ]);
    //                 }
    //             }
    //         }


    //         return $arr_pembelian;
    //     } else {
    //         $no_pembelian = DB::select("select * from pembelian where id_pembelian =(select max(id_pembelian) from pembelian 
    //                 where month(tgl_pembelian)=$bulan_tgl[1] and YEAR(tgl_pembelian)=$bulan_tgl[0]) ");

    //         if ($no_pembelian != null) {

    //             $no_pembelian = no_transaksi($no_pembelian[0]->no_pembelian);
    //         } else {
    //             $no_pembelian = 1;
    //         }
    //         array_push($arr_pembelian, (int)$no_pembelian);
    //     }


    //     return $arr_pembelian;
    // }
    public function no_pembelian($tgl_pembelian, $id_pemasok)
    {
        DB::beginTransaction();

        try {
            $bulan_tgl = explode("-", $tgl_pembelian);
            $arr_pembelian = [];
            $count_table_tmp = (DB::table('pembelian_temp')->count('id') > 0) ? "pembelian_temp" : "pembelian";
            if (count(array_unique($id_pemasok)) > 1) {
                foreach (array_unique($id_pemasok) as $i => $ip) {
                    if ($i == 0) {
                        // Menggunakan locking di level database
                        $no_pembelian = DB::table($count_table_tmp)
                            ->lockForUpdate()
                            ->whereMonth('tgl_pembelian', $bulan_tgl[1])
                            ->whereYear('tgl_pembelian', $bulan_tgl[0])
                            ->max(DB::raw('CAST(SUBSTRING(no_pembelian, 4, 1) AS SIGNED)'));

                        $no_pembelian = $no_pembelian !== null ? (int)$no_pembelian + 1 : 1;


                        $no_purchase = "PO/$no_pembelian/$bulan_tgl[0]/$bulan_tgl[1]/$bulan_tgl[2]";

                        DB::table('pembelian_temp')->insert([
                            'no_pembelian' => $no_purchase,
                            'tgl_pembelian' => date('Y-m-d')
                        ]);

                        array_push($arr_pembelian, [
                            'id_pemasok' => $ip,
                            'no_pembelian' => $no_pembelian
                        ]);
                    } else {
                        $ip_sudah_ada = false;

                        foreach ($arr_pembelian as $key => $arp) {
                            if ((int)$ip == (int)$arp['id_pemasok']) {
                                $arr_pembelian[$key]['no_pembelian']++;
                                $ip_sudah_ada = true;
                                break;
                            }
                        }

                        if (!$ip_sudah_ada) {
                            $nomor_pembelian_terakhir = end($arr_pembelian)['no_pembelian'];
                            $nomor_pembelian_terakhir += 1;

                            $no_purchase = "PO/$nomor_pembelian_terakhir/$bulan_tgl[0]/$bulan_tgl[1]/$bulan_tgl[2]";

                            DB::table('pembelian_temp')->insert([
                                'no_pembelian' => $no_purchase,
                                'tgl_pembelian' => date('Y-m-d')
                            ]);
                            array_push($arr_pembelian, [
                                'id_pemasok' => $ip,
                                'no_pembelian' => $nomor_pembelian_terakhir
                            ]);
                        }
                    }
                }
            } else {
                // Menggunakan locking di level database
                $no_pembelian = DB::table($count_table_tmp)
                    ->lockForUpdate()
                    ->whereMonth('tgl_pembelian', $bulan_tgl[1])
                    ->whereYear('tgl_pembelian', $bulan_tgl[0])
                    ->max('no_pembelian');

                $no_pembelian = $no_pembelian !== null ? no_transaksi($no_pembelian) : 1;
                $no_purchase = "PO/$no_pembelian/$bulan_tgl[0]/$bulan_tgl[1]/$bulan_tgl[2]";

                DB::table('pembelian_temp')->insert([
                    'no_pembelian' => $no_purchase,
                    'tgl_pembelian' => date('Y-m-d')
                ]);

                array_push($arr_pembelian, (int)$no_pembelian);
            }

            DB::commit();

            return $arr_pembelian;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }












    // public function insert_penjualan1($id_transaksi, $data_pembelian, $data_detail_pembelian, $id_pemasok, $kemungkinan, $nominal = null)
    // {

    //     DB::commit();

    //     try {

    //         if (gettype($id_pemasok) == 'array') {
    //             $update_data_transaksi = [];
    //             for ($i = 0; $i < count($id_pemasok); $i++) {
    //                 $update_data_transaksi[$i] = [
    //                     'status_transaksi' => 'purchase',
    //                     // 'id_pemasok' => $id_pemasok[$i]
    //                 ];
    //             }
    //         } else {
    //             $update_data_transaksi = [
    //                 'status_transaksi' => 'purchase',
    //                 // 'id_pemasok' => $id_pemasok
    //             ];
    //         }



    //         if (count($id_transaksi) >= 1 && gettype($id_pemasok) == 'string') {

    //             for ($i = 0; $i < count($id_transaksi); $i++) {

    //                 DB::table('transaksi')
    //                     ->where('id_transaksi', $id_transaksi[$i])
    //                     ->update($update_data_transaksi);
    //             }
    //         } elseif (count($id_transaksi) > 1 && gettype($id_pemasok) == 'array') {

    //             for ($i = 0; $i < count($id_transaksi); $i++) {

    //                 DB::table('transaksi')
    //                     ->where('id_transaksi', $id_transaksi[$i])
    //                     ->update($update_data_transaksi[$i]);
    //             }
    //         } else if (count($id_transaksi) < 1 && gettype($id_pemasok) == 'string') {

    //             DB::table('transaksi')->where('id_transaksi', $id_transaksi[0])->update($update_data_transaksi);
    //         }







    //         // insert pembelian dan detail pembelian

    //         for ($i = 0; $i < count($data_pembelian); $i++) {
    //             $id_pembelian = DB::table('pembelian')->insertGetId($data_pembelian[$i]);
    //             $data_detail_pembelian[$i]['id_pembelian'] = $id_pembelian;
    //             DB::table('detail_transaksi_pembelian')->insert($data_detail_pembelian[$i]);
    //         }



    //         // kodingan jurnal pembelian utang


    //         // dd($data_detail_pembelian);

    //         $total_pembelian = 0;
    //         foreach ($data_detail_pembelian as $ddp) {
    //             // dump($ddp['total_detail_pembelian']);
    //             $total_pembelian += $ddp['subtotal_detail_pembelian'];
    //         }




    //         $jurnal = [
    //             [
    //                 "id_transaksi" => $id_transaksi[0],
    //                 'kode_akun' => 500,
    //                 'tgl_jurnal' => $data_pembelian[0]['tgl_pembelian'],
    //                 'nominal' => (int)$total_pembelian,
    //                 'posisi_db_cr' => "debit"
    //             ],
    //             [
    //                 "id_transaksi" => $id_transaksi[0],
    //                 'kode_akun' => 200,
    //                 'tgl_jurnal' => $data_pembelian[0]['tgl_pembelian'],
    //                 'nominal' => (int)$total_pembelian,
    //                 'posisi_db_cr' => "kredit"
    //             ],
    //         ];

    //         DB::table('pembelian_temp')->where([
    //             'tgl_pembelian' => date('Y-m-d')
    //         ])->delete();

    //         DB::table('jurnal')->insert($jurnal);
    //         DB::commit();
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         throw $e;
    //     }
    // }



    public function insert_penjualan($id_transaksi, $data_pembelian, $data_detail_pembelian, $id_pemasok, $kemungkinan, $nominal = null)
    {
        DB::beginTransaction();

        try {
            // Update status transaksi
            $update_data_transaksi = [
                'status_transaksi' => 'purchase',
            ];

            if (is_array($id_pemasok)) {
                foreach ($id_transaksi as $i => $transaksiId) {
                    DB::table('transaksi')
                        ->where('id_transaksi', $transaksiId)
                        ->update($update_data_transaksi);
                }
            } else {
                DB::table('transaksi')
                    ->whereIn('id_transaksi', $id_transaksi)
                    ->update($update_data_transaksi);
            }

            // Insert pembelian dan detail pembelian
            foreach ($data_pembelian as $i => $pembelianData) {
                $id_pembelian = DB::table('pembelian')->insertGetId($pembelianData);
                $data_detail_pembelian[$i]['id_pembelian'] = $id_pembelian;
                DB::table('detail_transaksi_pembelian')->insert($data_detail_pembelian[$i]);
            }

            // Hitung total pembelian
            $total_pembelian = array_sum(array_column($data_detail_pembelian, 'subtotal_detail_pembelian'));

            // Jurnal pembelian utang
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
            DB::table('jurnal')->insert($jurnal);

            // Hapus data pembelian_temp
            DB::table('pembelian_temp')->where('tgl_pembelian', date('Y-m-d'))->delete();

            // Commit transaksi
            DB::commit();
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();
            throw $e;
        }
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

    public function print($no_pembelian)
    {

        $goods = DB::select("SELECT * FROM pembelian 
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
        where no_pembelian='$no_pembelian' and transaksi.type=1
        ");
        $service = DB::select("SELECT * FROM pembelian 
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
        where no_pembelian='$no_pembelian' and transaksi.type=2
        ");

        return [
            "goods" => $goods,
            "service" => $service,
            "namaFile" => str_replace("/", "_", $no_pembelian)
        ];
    }

    public function getNoPurchase()
    {
        return DB::table('pembelian')
            ->select('no_pembelian')
            ->join('transaksi', 'transaksi.id_transaksi', '=', 'pembelian.id_transaksi')
            ->join('penjualan', 'penjualan.id_transaksi', '=', 'transaksi.id_transaksi')
            ->join('penerimaan_barang', 'penerimaan_barang.id_pembelian', '=', 'pembelian.id_pembelian')
            ->join('pengiriman', 'pengiriman.id_penerimaan_barang', '=', 'penerimaan_barang.id_penerimaan_barang')
            ->leftJoin('detail_transaksi_pengiriman', 'detail_transaksi_pengiriman.id_pengiriman', '=', 'pengiriman.id_pengiriman')
            ->where('sisa_detail_pengiriman', '=', '0')
            ->whereNotIn('status_transaksi', ['bill', 'payment'])
            ->get();
    }

    public function delete_detail($id_pembelian)
    {
        // hapus detail_pembelian
        // dd($id_pembelian);
        DB::table('detail_transaksi_pembelian')->where('id_pembelian', '=', $id_pembelian)->delete();
        return  DB::table('pembelian')->where('id_pembelian', '=', $id_pembelian)->delete();
    }

    public function itemCanDelete($no_pembelian)
    {
        $id_pembelian = DB::table('pembelian')
            ->select('pembelian.id_pembelian')
            ->where('no_pembelian', $no_pembelian)
            ->get()
            ->pluck('id_pembelian')
            ->toArray();

        $result = DB::table('penerimaan_barang')
            ->select('id_penerimaan_barang', 'id_pembelian')
            ->whereIn('id_pembelian', $id_pembelian)
            ->get();

        //    return $reslt
    }
}

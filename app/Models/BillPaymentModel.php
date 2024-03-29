<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use function app\helper\no_transaksi;


class BillPaymentModel extends Model
{
    use HasFactory;

    public function index($id = null)
    {
        if ($id) {
            return DB::table('transaksi')
                ->selectRaw("tgl_tagihan,nama_pelanggan,no_tagihan,berat,total,layanan,nama_pengguna, DATE_ADD(tgl_tagihan, INTERVAL 31 DAY) AS DUE_DATE,no_pengiriman,kode_transaksi,no_penerimaan,status_transaksi,nomor_transaksi")
                ->join("pelanggan", "transaksi.id_pelanggan", "=", "pelanggan.id_pelanggan")
                ->join("pengguna", "transaksi.id", "=", "pengguna.id")
                ->join('penawaran', "penawaran.id_transaksi", "=", "transaksi.id_transaksi")
                ->join('penerimaan_barang', 'penerimaan_barang.id_transaksi', '=', 'transaksi.id_transaksi')
                ->join('pengiriman', "pengiriman.id_transaksi", "=", "transaksi.id_transaksi")
                ->join('detail_transaksi_pengiriman', "detail_transaksi_pengiriman.id_pengiriman", "=", "pengiriman.id_pengiriman")
                ->join('detail_transaksi_penawaran', "detail_transaksi_penawaran.id_penawaran", "=", "penawaran.id_penawaran")
                ->join("produk", "detail_transaksi_pengiriman.id_produk", "=", "produk.id_produk")
                ->join("tagihan", "tagihan.id_transaksi", "=", "transaksi.id_transaksi")
                ->where('no_tagihan', "=", "$id")
                ->groupBy('tgl_tagihan', "no_tagihan")
                ->orderByRaw('tgl_tagihan desc,no_tagihan desc')
                ->get();
        } else {


            return DB::select("SELECT b.no_penjualan,b.nomor_transaksi,b.no_tagihan,b.nama_pelanggan,b.tgl_tagihan,b.nama_pengguna,DATE_ADD(b.tgl_tagihan, INTERVAL 31 DAY) AS DUE_DATE,b.status_transaksi,
                (select sum( jumlah_detail_pengiriman) from penerimaan_barang 
                join detail_penerimaan_barang on detail_penerimaan_barang.id_penerimaan_barang = penerimaan_barang.id_penerimaan_barang
                join transaksi on penerimaan_barang.id_transaksi = transaksi.id_transaksi
                left join pengiriman on pengiriman.id_penerimaan_barang = penerimaan_barang.id_penerimaan_barang
                left join detail_transaksi_pengiriman on pengiriman.id_pengiriman = detail_transaksi_pengiriman.id_pengiriman
                join penjualan on penjualan.id_transaksi=transaksi.id_transaksi
                  where no_penjualan=b.no_penjualan

                )jumlah_detail_pengiriman ,
                (SELECT sum(jumlah_detail_penerimaan) FROM transaksi 
                 join penjualan on penjualan.id_transaksi = transaksi.id_transaksi
                 join pembelian on pembelian.id_penjualan = penjualan.id_penjualan
				join penerimaan_barang on penerimaan_barang.id_pembelian = pembelian.id_pembelian
                join detail_penerimaan_barang on penerimaan_barang.id_penerimaan_barang = detail_penerimaan_barang.id_penerimaan_barang
                where no_penjualan=b.no_penjualan
                ) jumlah_detail_penerimaan,
                (
					SELECT sum(jumlah_detail_pembelian) FROM transaksi 
					left join penjualan on penjualan.id_transaksi = transaksi.id_transaksi
					left join pembelian on pembelian.id_transaksi = transaksi.id_transaksi
					left join detail_transaksi_pembelian on detail_transaksi_pembelian.id_pembelian=pembelian.id_pembelian
					where transaksi.tidak_terpakai = 0  and no_penjualan=b.no_penjualan
                ) jumlah_detail_pembelian,
                (
                	SELECT sum(jumlah) FROM transaksi 
					join penjualan on penjualan.id_transaksi = transaksi.id_transaksi
					where transaksi.tidak_terpakai = 0  and no_penjualan=b.no_penjualan
                ) total_jumlah 
                from(
            SELECT distinct transaksi.id_transaksi,nomor_pekerjaan, no_penerimaan,no_pengiriman, 
            pengiriman.id_penerimaan_barang, jumlah_detail_penerimaan,
            sum(jumlah_detail_pengiriman) as jumlah_detail_pengiriman,sisa_detail_pengiriman,
            nama_pelanggan,nama_pengguna,tgl_penerimaan,no_pembelian,no_penjualan,no_tagihan,tgl_tagihan ,status_transaksi,nomor_transaksi FROM transaksi
            join penerimaan_barang on penerimaan_barang.id_transaksi = transaksi.id_transaksi
            join detail_penerimaan_barang on detail_penerimaan_barang.id_penerimaan_barang=penerimaan_barang.id_penerimaan_barang
            left join pengiriman on pengiriman.id_transaksi = transaksi.id_transaksi
            left join detail_transaksi_pengiriman on detail_transaksi_pengiriman.id_pengiriman=pengiriman.id_pengiriman 
             join pelanggan on pelanggan.id_pelanggan=transaksi.id_pelanggan
            join pengguna on pengguna.id=transaksi.id
            join pembelian on pembelian.id_transaksi = transaksi.id_transaksi
            join pemasok on pembelian.id_pemasok =  pemasok.id_pemasok
            join penjualan on penjualan.id_transaksi=transaksi.id_transaksi
           left join tagihan on tagihan.id_transaksi = transaksi.id_transaksi
           group by no_penjualan
           ) b
          group by b.no_penjualan
		  having jumlah_detail_pembelian = jumlah_detail_pengiriman
           ORDER BY CASE WHEN b.tgl_tagihan IS NULL THEN 0 ELSE 1 END, b.tgl_tagihan DESC, b.no_penjualan DESC
          ");
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

            // $data[] = $dataa[0];
            // dd($daa)
            // array_push($data,$dataa[0]);
        }
        // print_r($data);
        $array = [
            'data' => $data,
            'id_transaksi' => $id_transaksi1
        ];
        return $array;
    }
    public function no_tagihan($tgl_tagihan)
    {
        $bulan_tgl = explode("-", $tgl_tagihan);


        $no_tagihan = DB::select("
           select * from tagihan where id_tagihan =(select max(id_tagihan) from tagihan 
           where month(tgl_tagihan)='$bulan_tgl[1]' and YEAR(tgl_tagihan)='$bulan_tgl[0]' )");

        if ($no_tagihan != null) {

            $no_tagihan = no_transaksi($no_tagihan[0]->no_tagihan);
        } else {
            $no_tagihan = 1;
        }


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

        // cari nominal jurnal


        $id_transaksi_akir = count($id_transaksi) - 1;

        $subtotal = 0;
        $total_ppn = 0;
        $total_ongkir = 0;
        $total = 0;
        $nominal = DB::select("SELECT  sum( ppn_detail_pengiriman) as ppn,sum( subtotal_detail_pengiriman) as subtotal,sum(total_detail_pengiriman)+ongkir as total,ongkir
            from transaksi 
            join pengiriman on pengiriman.id_transaksi=transaksi.id_transaksi
            join detail_transaksi_pengiriman
            on pengiriman.id_pengiriman = detail_transaksi_pengiriman.id_pengiriman
            where pengiriman.id_transaksi between $id_transaksi[0] and $id_transaksi[$id_transaksi_akir]");

        $total_ppn = $nominal[0]->ppn;
        $subtotal = $nominal[0]->subtotal;
        $total_ongkir = $nominal[0]->ongkir;
        $total = $nominal[0]->total;

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
                'nominal' => $subtotal,
                'posisi_db_cr' => "kredit"
            ],
        ];

        DB::table('jurnal')->insert($jurnal);
    }

    public function detail($no_tagihan)
    {


        return  DB::select(
            "SELECT *,penerimaan_barang.no_penerimaan,no_pengiriman, transaksi.id_transaksi , penjualan.id_penjualan , penerimaan_barang.id_penerimaan_barang ,penawaran.id_penawaran,jumlah_detail_penerimaan,
            case 
            when jumlah_detail_pengiriman > 0 then sum(jumlah_detail_pengiriman)
            end as
            sudah_terkirim,
            jumlah_detail_pengiriman,
            sisa_detail_pengiriman ,detail_penerimaan_barang.id_produk FROM transaksi 
            join penjualan on  penjualan.id_transaksi=transaksi.id_transaksi
            join penerimaan_barang on penerimaan_barang.id_transaksi=transaksi.id_transaksi
            join detail_penerimaan_barang on detail_penerimaan_barang.id_penerimaan_barang = penerimaan_barang.id_penerimaan_barang
            join penawaran on penawaran.id_transaksi = transaksi.id_transaksi
            left join pengiriman on pengiriman.id_transaksi = transaksi.id_transaksi
            left join detail_transaksi_pengiriman on detail_transaksi_pengiriman.id_pengiriman=pengiriman.id_pengiriman 
            join pelanggan on pelanggan.id_pelanggan=transaksi.id_pelanggan
            join pengguna on pengguna.id=transaksi.id
            join produk on detail_penerimaan_barang.id_produk=produk.id_produk
            join tagihan on tagihan.id_transaksi=transaksi.id_transaksi
            join pembelian on pembelian.id_transaksi=transaksi.id_transaksi
            join pegawai on pegawai.id_pegawai = transaksi.id_pegawai
			where no_tagihan='$no_tagihan' and transaksi.tidak_terpakai=0 
            group by transaksi.id_transaksi"
        );
    }
    public function print($no_tagihan)
    {


        $goods =  DB::select(
            "SELECT *,penerimaan_barang.no_penerimaan,no_pengiriman, transaksi.id_transaksi , penjualan.id_penjualan , penerimaan_barang.id_penerimaan_barang ,penawaran.id_penawaran,jumlah_detail_penerimaan,
            case 
            when jumlah_detail_pengiriman > 0 then sum(jumlah_detail_pengiriman)
            end as
            sudah_terkirim,
            jumlah_detail_pengiriman,
            sisa_detail_pengiriman ,detail_penerimaan_barang.id_produk FROM transaksi 
            join penjualan on  penjualan.id_transaksi=transaksi.id_transaksi
            join penerimaan_barang on penerimaan_barang.id_transaksi=transaksi.id_transaksi
            join detail_penerimaan_barang on detail_penerimaan_barang.id_penerimaan_barang = penerimaan_barang.id_penerimaan_barang
            join penawaran on penawaran.id_transaksi = transaksi.id_transaksi
            left join pengiriman on pengiriman.id_transaksi = transaksi.id_transaksi
            left join detail_transaksi_pengiriman on detail_transaksi_pengiriman.id_pengiriman=pengiriman.id_pengiriman 
            join pelanggan on pelanggan.id_pelanggan=transaksi.id_pelanggan
            join pengguna on pengguna.id=transaksi.id
            join produk on detail_penerimaan_barang.id_produk=produk.id_produk
            join tagihan on tagihan.id_transaksi=transaksi.id_transaksi
            join pembelian on pembelian.id_transaksi=transaksi.id_transaksi
            join pegawai on pegawai.id_pegawai = transaksi.id_pegawai
			where no_tagihan='$no_tagihan' and transaksi.tidak_terpakai=0 and transaksi.type=1
            group by transaksi.id_transaksi"
        );
        $service =  DB::select(
            "SELECT *,penerimaan_barang.no_penerimaan,no_pengiriman, transaksi.id_transaksi , penjualan.id_penjualan , penerimaan_barang.id_penerimaan_barang ,penawaran.id_penawaran,jumlah_detail_penerimaan,
            case 
            when jumlah_detail_pengiriman > 0 then sum(jumlah_detail_pengiriman)
            end as
            sudah_terkirim,
            jumlah_detail_pengiriman,
            sisa_detail_pengiriman ,detail_penerimaan_barang.id_produk FROM transaksi 
            join penjualan on  penjualan.id_transaksi=transaksi.id_transaksi
            join penerimaan_barang on penerimaan_barang.id_transaksi=transaksi.id_transaksi
            join detail_penerimaan_barang on detail_penerimaan_barang.id_penerimaan_barang = penerimaan_barang.id_penerimaan_barang
            join penawaran on penawaran.id_transaksi = transaksi.id_transaksi
            left join pengiriman on pengiriman.id_transaksi = transaksi.id_transaksi
            left join detail_transaksi_pengiriman on detail_transaksi_pengiriman.id_pengiriman=pengiriman.id_pengiriman 
            join pelanggan on pelanggan.id_pelanggan=transaksi.id_pelanggan
            join pengguna on pengguna.id=transaksi.id
            join produk on detail_penerimaan_barang.id_produk=produk.id_produk
            join tagihan on tagihan.id_transaksi=transaksi.id_transaksi
            join pembelian on pembelian.id_transaksi=transaksi.id_transaksi
            join pegawai on pegawai.id_pegawai = transaksi.id_pegawai
			where no_tagihan='$no_tagihan' and transaksi.tidak_terpakai=0 and transaksi.type=2
            group by transaksi.id_transaksi"
        );


        return [
            "goods" => $goods,
            "service" => $service,
            "namaFile" => str_replace("/", "_", $no_tagihan),

        ];
    }


    public function show($no_penjualan)
    {
        // echo 'masuk sini';


        return DB::select("SELECT *,penerimaan_barang.no_penerimaan,no_pengiriman, transaksi.id_transaksi , penjualan.id_penjualan , penerimaan_barang.id_penerimaan_barang ,penawaran.id_penawaran,jumlah_detail_penerimaan,
            case 
            when jumlah_detail_pengiriman > 0 then sum(jumlah_detail_pengiriman)
            end as
            sudah_terkirim,
            jumlah_detail_pengiriman,
            sisa_detail_pengiriman ,detail_penerimaan_barang.id_produk,
               (select max(tgl_pengiriman) from pengiriman join penerimaan_barang 
            on penerimaan_barang.id_penerimaan_barang=pengiriman.id_penerimaan_barang
            join pembelian on pembelian.id_pembelian =penerimaan_barang.id_pembelian
            join penjualan on penjualan.id_penjualan = pembelian.id_penjualan
            where no_penjualan='$no_penjualan'
            ) as tgl_pengiriman_max
            
             FROM transaksi 
			join penawaran on penawaran.id_transaksi = transaksi.id_transaksi
			join penjualan on  penjualan.id_transaksi=transaksi.id_transaksi
            join detail_transaksi_penjualan on penjualan.id_penjualan = detail_transaksi_penjualan.id_penjualan
            join pembelian on pembelian.id_penjualan = penjualan.id_penjualan
            join detail_transaksi_pembelian on pembelian.id_pembelian = detail_transaksi_pembelian.id_pembelian
            join penerimaan_barang on penerimaan_barang.id_pembelian=pembelian.id_pembelian
            join detail_penerimaan_barang on detail_penerimaan_barang.id_penerimaan_barang = penerimaan_barang.id_penerimaan_barang
            left join pengiriman on pengiriman.id_penerimaan_barang = penerimaan_barang.id_penerimaan_barang
            left join detail_transaksi_pengiriman on detail_transaksi_pengiriman.id_pengiriman=pengiriman.id_pengiriman 
            join pelanggan on pelanggan.id_pelanggan=transaksi.id_pelanggan
            join pengguna on pengguna.id=transaksi.id
            join produk on detail_penerimaan_barang.id_produk=produk.id_produk
			where no_penjualan='$no_penjualan'  and jumlah_detail_penerimaan >= jumlah_detail_pengiriman
            group by transaksi.id_transaksi
            order by transaksi.id_transaksi asc");
    }


    public function getNoSalesForBill()
    {
        return DB::select("SELECT b.no_penjualan,b.no_tagihan,
                (select sum( jumlah_detail_pengiriman) from penerimaan_barang 
                join detail_penerimaan_barang on detail_penerimaan_barang.id_penerimaan_barang = penerimaan_barang.id_penerimaan_barang
                join transaksi on penerimaan_barang.id_transaksi = transaksi.id_transaksi
                left join pengiriman on pengiriman.id_penerimaan_barang = penerimaan_barang.id_penerimaan_barang
                left join detail_transaksi_pengiriman on pengiriman.id_pengiriman = detail_transaksi_pengiriman.id_pengiriman
                join penjualan on penjualan.id_transaksi=transaksi.id_transaksi
                  where no_penjualan=b.no_penjualan

                )jumlah_detail_pengiriman ,
                (SELECT sum(jumlah_detail_penerimaan) FROM transaksi 
                 join penjualan on penjualan.id_transaksi = transaksi.id_transaksi
                 join pembelian on pembelian.id_penjualan = penjualan.id_penjualan
				join penerimaan_barang on penerimaan_barang.id_pembelian = pembelian.id_pembelian
                join detail_penerimaan_barang on penerimaan_barang.id_penerimaan_barang = detail_penerimaan_barang.id_penerimaan_barang
                where no_penjualan=b.no_penjualan
                ) jumlah_detail_penerimaan,
                (
					SELECT sum(jumlah_detail_pembelian) FROM transaksi 
					left join penjualan on penjualan.id_transaksi = transaksi.id_transaksi
					left join pembelian on pembelian.id_transaksi = transaksi.id_transaksi
					left join detail_transaksi_pembelian on detail_transaksi_pembelian.id_pembelian=pembelian.id_pembelian
					where transaksi.tidak_terpakai = 0  and no_penjualan=b.no_penjualan
                ) jumlah_detail_pembelian,
                (
                	SELECT sum(jumlah) FROM transaksi 
					join penjualan on penjualan.id_transaksi = transaksi.id_transaksi
					where transaksi.tidak_terpakai = 0  and no_penjualan=b.no_penjualan
                ) total_jumlah 
                from(
            SELECT distinct transaksi.id_transaksi,nomor_pekerjaan, no_penerimaan,no_pengiriman, 
            pengiriman.id_penerimaan_barang, jumlah_detail_penerimaan,
            sum(jumlah_detail_pengiriman) as jumlah_detail_pengiriman,sisa_detail_pengiriman,
            nama_pelanggan,nama_pengguna,tgl_penerimaan,no_pembelian,no_penjualan,no_tagihan FROM transaksi
            join penerimaan_barang on penerimaan_barang.id_transaksi = transaksi.id_transaksi
            join detail_penerimaan_barang on detail_penerimaan_barang.id_penerimaan_barang=penerimaan_barang.id_penerimaan_barang
            left join pengiriman on pengiriman.id_transaksi = transaksi.id_transaksi
            left join detail_transaksi_pengiriman on detail_transaksi_pengiriman.id_pengiriman=pengiriman.id_pengiriman 
             join pelanggan on pelanggan.id_pelanggan=transaksi.id_pelanggan
            join pengguna on pengguna.id=transaksi.id
            join pembelian on pembelian.id_transaksi = transaksi.id_transaksi
            join pemasok on pembelian.id_pemasok =  pemasok.id_pemasok
            join penjualan on penjualan.id_transaksi=transaksi.id_transaksi
           left join tagihan on tagihan.id_transaksi = transaksi.id_transaksi
           where status_transaksi not in ('bill','payment')

            group by no_penjualan
           ) b
          group by b.no_penjualan
		  having total_jumlah = jumlah_detail_pengiriman
		
		 
         
       
      ");
        //     return DB::select("
        //          SELECT b.no_penjualan,
        //             (select sum( jumlah_detail_pengiriman) from penerimaan_barang 
        //             join detail_penerimaan_barang on detail_penerimaan_barang.id_penerimaan_barang = penerimaan_barang.id_penerimaan_barang
        //             join transaksi on penerimaan_barang.id_transaksi = transaksi.id_transaksi
        //             left join pengiriman on pengiriman.id_penerimaan_barang = penerimaan_barang.id_penerimaan_barang
        //             left join detail_transaksi_pengiriman on pengiriman.id_pengiriman = detail_transaksi_pengiriman.id_pengiriman
        //             join penjualan on penjualan.id_transaksi=transaksi.id_transaksi
        //               where no_penjualan=b.no_penjualan

        //             )jumlah_detail_pengiriman ,
        //             (SELECT sum(jumlah_detail_penerimaan) FROM transaksi 
        //              join penjualan on penjualan.id_transaksi = transaksi.id_transaksi
        //              join pembelian on pembelian.id_penjualan = penjualan.id_penjualan
        // 			join penerimaan_barang on penerimaan_barang.id_pembelian = pembelian.id_pembelian
        //             join detail_penerimaan_barang on penerimaan_barang.id_penerimaan_barang = detail_penerimaan_barang.id_penerimaan_barang
        //             where no_penjualan=b.no_penjualan
        //             ) jumlah_detail_penerimaan,
        //             (
        // 				SELECT sum(jumlah_detail_pembelian) FROM transaksi 
        // 				left join penjualan on penjualan.id_transaksi = transaksi.id_transaksi
        // 				left join pembelian on pembelian.id_transaksi = transaksi.id_transaksi
        // 				left join detail_transaksi_pembelian on detail_transaksi_pembelian.id_pembelian=pembelian.id_pembelian
        // 				where transaksi.tidak_terpakai = 0  and no_penjualan=b.no_penjualan
        //             ) jumlah_detail_pembelian,
        //             (
        //             	SELECT sum(jumlah) FROM transaksi 
        // 				join penjualan on penjualan.id_transaksi = transaksi.id_transaksi
        // 				where transaksi.tidak_terpakai = 0  and no_penjualan=b.no_penjualan
        //             ) total_jumlah 
        //             from(
        //         SELECT distinct transaksi.id_transaksi,nomor_pekerjaan, no_penerimaan,no_pengiriman, 
        //         pengiriman.id_penerimaan_barang, jumlah_detail_penerimaan,
        //         sum(jumlah_detail_pengiriman) as jumlah_detail_pengiriman,sisa_detail_pengiriman,
        //         nama_pelanggan,nama_pengguna,tgl_penerimaan,no_pembelian,no_penjualan FROM transaksi
        //         join penerimaan_barang on penerimaan_barang.id_transaksi = transaksi.id_transaksi
        //         join detail_penerimaan_barang on detail_penerimaan_barang.id_penerimaan_barang=penerimaan_barang.id_penerimaan_barang
        //         left join pengiriman on pengiriman.id_transaksi = transaksi.id_transaksi
        //         left join detail_transaksi_pengiriman on detail_transaksi_pengiriman.id_pengiriman=pengiriman.id_pengiriman 
        //          join pelanggan on pelanggan.id_pelanggan=transaksi.id_pelanggan
        //         join pengguna on pengguna.id=transaksi.id
        //         join pembelian on pembelian.id_transaksi = transaksi.id_transaksi
        //         join pemasok on pembelian.id_pemasok =  pemasok.id_pemasok
        //         join penjualan on penjualan.id_transaksi=transaksi.id_transaksi
        //        where status_transaksi not in ('bill','payment')
        //         group by no_penjualan
        //        ) b
        //       group by b.no_penjualan
        // 	  having total_jumlah = jumlah_detail_pengiriman







        //   ");
    }
}

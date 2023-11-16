<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Exception;



class QuotationExport implements FromView, ShouldAutoSize, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */


    public $month, $date, $type, $date_to;

    public function __construct($month = null, $date = null, $type = null, $date_to = null)
    {
        $this->month = $month;
        $this->date = $date;
        $this->type = $type;
        $this->date_to = $date_to;
    }
    public function view($month = null, $date = null): View
    {


        if ($this->type == 'detail') {
            $view = 'quotation.export_detail_report';
        }

        if ($this->type == 'customer_omzet') {
            $view = 'quotation.export_customer_omzet';
        }

        if ($this->type == 'out_standing') {
            $view = 'quotation.export_out_standing_report';
        }

        if ($this->type == 'quotation') {
            $view = 'quotation.quotation_report_export';
        }

        if ($this->type == 'omzet') {
            $view = 'GeneralReport.omzet_report_export';
        }


        // dd($this->data($this->month, $this->date));
        // dd($this->data($this->month, $this->date));

        //   dd($this->data($this->month, $this->date, $this->date_to));

        return view($view, [
            'data' => $this->data($this->month, $this->date, $this->date_to),
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $data = $this->data($this->month, $this->date);

        $jumlah_baris = count($data) + 2;

        $panjang_kolom = '';
        if ($this->type == 'detail') {
            $panjang_kolom = 'V';
        } else if ($this->type == 'customer_omzet') {

            $panjang_kolom = 'F';
        } else if ($this->type == 'out_standing') {

            $panjang_kolom = 'O';
        } else if ($this->type == 'quotation') {
            $panjang_kolom = 'M';
        } elseif ($this->type == 'omzet') {
            $panjang_kolom = 'X';
        }

        $sheet->getStyle("A1:$panjang_kolom$jumlah_baris")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);
    }

    public function data($year_month = null, $date = null, $date_to = null)
    {

        $where = '';

        $month = '';
        $year = '';


        if ($year_month != 0) {
            $year_month = explode('-', $year_month);

            $year = $year_month[0];
            $month = $year_month[1];
        }




        if ($this->type == 'detail') {


            if ($year_month != 0 && $date == 0) {

                $where = "AND YEAR(p.tgl_penawaran)='$year'AND MONTH(p.tgl_penawaran)=$month";
            } elseif ($date != 0 && $year_month == 0 && $date_to == 0) {
                // echo "masuk sini2";
                // die;

                $where = "AND p.tgl_penawaran='$date'";
            } elseif ($year_month != 0 && $date && $date_to == 0) {


                $where = "AND YEAR(p.tgl_penawaran)='$year'AND MONTH(p.tgl_penawaran)=$month and p.tgl_penawaran=$date";
            } else if ($date != 0 && $date_to != 0) {


                $where = "AND tgl_penawaran between '$date' and '$date_to'";
            } else if ($date != 0 && $date_to != 0 && $year_month != 0) {


                $where = "AND YEAR(p.tgl_penawaran)='$year'AND MONTH(p.tgl_penawaran)=$month AND p.tgl_penawaran between '$date' and '$date_to'";
            }


            $query = "select b.*, (select sum(jumlah) from transaksi 
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
						t.jumlah,t.harga,t.total,t.layanan,pemasok.nama_pemasok ,tebal_penawaran,
                        lebar_penawaran,
                        panjang_penawaran,nama_pelanggan,nomor_pekerjaan,nama_produk
                         FROM transaksi t
						join penawaran p on t.id_transaksi = p.id_transaksi
						left join penjualan pj on p.id_transaksi = pj.id_transaksi
						left join pembelian pm on pm.id_penjualan = pj.id_penjualan
						left join penerimaan_barang pb on pb.id_pembelian=pm.id_pembelian
						left join pengiriman pg on pg.id_penerimaan_barang = pb.id_penerimaan_barang
                        left join pemasok on pemasok.id_pemasok=pm.id_pemasok
                        left join detail_transaksi_penawaran dtp on dtp.id_penawaran=p.id_penawaran
                        left join produk pd on pd.id_produk=dtp.id_produk
						join pelanggan on pelanggan.id_pelanggan=t.id_pelanggan
                        $where
						) b GROUP by b.no_penawaran order by b.tgl_penawaran,b.no_penawaran asc";
        } else if ($this->type == 'customer_omzet') {



            if ($year_month != 0 && $date == 0) {

                $where = "AND YEAR(p.tgl_penawaran)='$year'AND MONTH(p.tgl_penawaran)=$month";
            } elseif ($date != 0 && $year_month == 0 && $date_to == 0) {
                // echo "masuk sini2";
                // die;

                $where = "AND p.tgl_penawaran='$date'";
            } elseif ($year_month != 0 && $date && $date_to == 0) {


                $where = "AND YEAR(p.tgl_penawaran)='$year'AND MONTH(p.tgl_penawaran)=$month and p.tgl_penawaran=$date";
            } else if ($date != 0 && $date_to != 0) {


                $where = "AND tgl_penawaran between '$date' and '$date_to'";
            } else if ($date != 0 && $date_to != 0 && $year_month != 0) {


                $where = "AND YEAR(p.tgl_penawaran)='$year'AND MONTH(p.tgl_penawaran)=$month AND p.tgl_penawaran between '$date' and '$date_to'";
            }

            $query = "  select b.*,(
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
            where pw.jabatan_pegawai='SALES' $where
            group by  pg.id_pelanggan
            ) b ";
        } else if ($this->type == 'out_standing') {



            if ($year_month != 0 && $date == 0) {

                $where = "AND YEAR(pj.tgl_penjualan)='$year'AND MONTH(pj.tgl_penjualan)=$month";
            } elseif ($date != 0 && $year_month == 0 && $date_to == 0) {
                // echo "masuk sini2";
                // die;

                $where = "AND pj.tgl_penjualan='$date'";
            } elseif ($year_month != 0 && $date && $date_to == 0) {


                $where = "AND YEAR(pj.tgl_penjualan)='$year'AND MONTH(pj.tgl_penjualan)=$month and pj.tgl_penjualan=$date";
            } else if ($date != 0 && $date_to != 0) {


                $where = "AND tgl_penawaran between '$date' and '$date_to'";
            } else if ($date != 0 && $date_to != 0 && $year_month != 0) {


                $where = "AND YEAR(pj.tgl_penjualan)='$year'AND MONTH(pj.tgl_penjualan)=$month AND pj.tgl_penjualan between '$date' and '$date_to'";
            }

            $query = " SELECT t.id_transaksi,
                        tgl_penjualan,pj.id_penjualan,pj.no_penjualan,
                        t.tebal_transaksi,t.panjang_transaksi,lebar_transaksi,
                        t.berat,t.jumlah,t.harga,t.total,t.layanan,
                        pemasok.nama_pemasok,nama_produk,no_pengiriman,
                        tgl_pengiriman,nama_pegawai,nama_pelanggan,no_penawaran,tgl_penawaran,no_pembelian FROM transaksi t
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
                        left join tagihan on tagihan.id_pengiriman=pg.id_pengiriman
                        where t.tidak_terpakai=0 and jabatan_pegawai='SALES'and no_tagihan is null
                         $where";
        } else if ($this->type == 'quotation') {




            if ($year_month != 0 && $date == 0) {

                $where = "AND YEAR(pn.tgl_penawaran)='$year'AND MONTH(pn.tgl_penawaran)=$month";
            } elseif ($date != 0 && $year_month == 0 && $date_to == 0) {
                // echo "masuk sini2";
                // die;

                $where = "AND pn.tgl_penawaran='$date'";
            } elseif ($year_month != 0 && $date && $date_to == 0) {


                $where = "AND YEAR(pn.tgl_penawaran)='$year'AND MONTH(pn.tgl_penawaran)=$month and pn.tgl_penawaran=$date";
            } else if ($date != 0 && $date_to != 0) {


                $where = "AND tgl_penawaran between '$date' and '$date_to'";
            } else if ($date != 0 && $date_to != 0 && $year_month != 0) {


                $where = "AND YEAR(pn.tgl_penawaran)='$year'AND MONTH(pn.tgl_penawaran)=$month AND pn.tgl_penawaran between '$date' and '$date_to'";
            }






            $query = "select b.*,sum(b.total) as total_quotation ,(
                        select sum(total) from transaksi 
                        left join penawaran on transaksi.id_transaksi = penawaran.id_transaksi
                        join penjualan on penawaran.id_transaksi=penjualan.id_transaksi
                        where no_penawaran=b.no_penawaran
                                                    ) as total_penjualan from(
                        select  t.id_transaksi,pn.tgl_penawaran,pn.no_penawaran,
                                                tgl_penjualan,pj.id_penjualan,pj.no_penjualan,
                                                t.jumlah,t.harga,t.total,t.layanan,subtotal,ppn,ongkir,
                                                t.total as total_transaksi,nama_pelanggan,tgl_pembelian,no_pembelian,nama_pegawai,no_po_customer from transaksi t 
                        left join penawaran pn on t.id_transaksi=pn.id_transaksi
                        left join penjualan pj on t.id_transaksi=pj.id_transaksi
                        left join pembelian pm on pm.id_penjualan=pj.id_penjualan
                        join pelanggan pg on pg.id_pelanggan=t.id_pelanggan
                        join pegawai on pegawai.id_pegawai=t.id_pegawai
                        where jabatan_pegawai='SALES' $where
                        )b
                        group by b.no_penawaran ORDER by b.tgl_penawaran asc";


            // echo $query;
            // die;
        } else if ($this->type == 'omzet') {

            if ($year_month != 0 && $date == 0) {

                $where = "AND YEAR(tgl_penawaran)='$year'AND MONTH(tgl_penawaran)=$month";
            } elseif ($date != 0 && $year_month == 0 && $date_to == 0) {
                // echo "masuk sini2";
                // die;

                $where = "AND tgl_penawaran='$date'";
            } elseif ($year_month != 0 && $date && $date_to == 0) {


                $where = "AND YEAR(tgl_penawaran)='$year'AND MONTH(tgl_penawaran)=$month and tgl_penawaran=$date";
            } else if ($date != 0 && $date_to != 0) {


                $where = "AND tgl_penawaran between '$date' and '$date_to'";
            } else if ($date != 0 && $date_to != 0 && $year_month != 0) {


                $where = "AND YEAR(tgl_penawaran)='$year'AND MONTH(tgl_penawaran)=$month AND tgl_penawaran between '$date' and '$date_to'";
            }


            $query = "SELECT transaksi.id_transaksi, no_penawaran,tgl_penawaran,no_penjualan,tgl_penjualan,nama_pelanggan,
            no_pembelian,tgl_pembelian,nama_pemasok,no_pengiriman,tgl_pengiriman,no_tagihan,tgl_tagihan,
            no_pembayaran,tgl_pembayaran,transaksi.subtotal ,transaksi.ppn,transaksi.total,subtotal_detail_pembelian,ppn_detail_pembelian,total_detail_pembelian
            FROM transaksi
			join penawaran on penawaran.id_transaksi = transaksi.id_transaksi
            join penjualan on  penjualan.id_transaksi=transaksi.id_transaksi
            left join pembelian on pembelian.id_penjualan=penjualan.id_penjualan
			left join detail_transaksi_pembelian on detail_transaksi_pembelian.id_pembelian=pembelian.id_pembelian
            left join penerimaan_barang on penerimaan_barang.id_pembelian=pembelian.id_pembelian
            left join pengiriman on penerimaan_barang.id_penerimaan_barang=pengiriman.id_penerimaan_barang
			left join tagihan on tagihan.id_pengiriman=pengiriman.id_pengiriman
            left join pembayaran on pembayaran.id_transaksi=transaksi.id_transaksi
            join pelanggan on transaksi.id_pelanggan = pelanggan.id_pelanggan
            join pemasok on pembelian.id_pemasok = pemasok.id_pemasok
            where penawaran.tidak_terpakai=0 $where
            order by tgl_penawaran desc";
        }







        return DB::select($query);
    }
}

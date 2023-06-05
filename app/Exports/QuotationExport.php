<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class QuotationExport implements FromView, ShouldAutoSize, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */


    public $month, $date, $type;

    public function __construct($month = null, $date = null, $type = null)
    {
        $this->month = $month;
        $this->date = $date;
        $this->type = $type;
    }
    public function view($month = null, $date = null): View
    {


        if ($this->type == 'detail') {
            $view = 'export_detail_report';
        }

        if ($this->type == 'customer_omzet') {
            $view = 'export_customer_omzet';
        }

        if ($this->type == 'out_standing') {
            $view = 'export_out_standing_report';
        }

        if ($this->type == 'quotation') {
            $view = 'quotation_report_export';
        }




        return view('quotation.' . $view, [
            'data' => $this->data($this->month, $this->date),
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $data = $this->data($this->month, $this->date);

        $jumlah_baris = count($data) + 2;

        $panjang_kolom = '';
        if ($this->type == 'detail') {
            $panjang_kolom = 'N';
        } else if ($this->type == 'customer_omzet') {

            $panjang_kolom = 'F';
        } else if ($this->type == 'out_standing') {

            $panjang_kolom = 'N';
        } else if ($this->type == 'quotation') {
            $panjang_kolom = 'K';
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

    public function data($month = null, $date = null)
    {

        $where = '';

        if ($this->type == 'detail') {
            if ($month != '0' && $date == null) {
                $where = "WHERE MONTH(p.tgl_penawaran)=$month";
            } elseif ($date && $month == '0') {

                $where = "WHERE DAY(p.tgl_penawaran)=$date";
            } elseif ($month != null && $date != null) {

                $where = "WHERE MONTH(p.tgl_penawaran)=$month and DAY(p.tgl_penawaran)=$date";
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
						t.jumlah,t.harga,t.total,t.layanan,pemasok.nama_pemasok FROM transaksi t
						join penawaran p on t.id_transaksi = p.id_transaksi
						join penjualan pj on p.id_transaksi = pj.id_transaksi
						left join pembelian pm on pm.id_penjualan = pj.id_penjualan
						left join penerimaan_barang pb on pb.id_pembelian=pm.id_pembelian
						left join pengiriman pg on pg.id_penerimaan_barang = pb.id_penerimaan_barang
                        left join pemasok on pemasok.id_pemasok=pm.id_pemasok
                        $where
						group by p.no_penawaran
                        
						) b";
        } else if ($this->type == 'customer_omzet') {

            if ($month != '0' && $date == null) {
                $where = "and MONTH(p.tgl_penawaran)=$month";
            } elseif ($date && $month == '0') {

                $where = "and DAY(p.tgl_penawaran)=$date";
            } elseif ($month != null && $date != null) {

                $where = "and MONTH(p.tgl_penawaran)=$month and DAY(p.tgl_penawaran)=$date";
            }

            $query = " select b.*,(
            select sum(subtotal) from transaksi 
            join penawaran on transaksi.id_transaksi = penawaran.id_transaksi
            where no_penawaran = b.no_penawaran 
            ) as total_penawaran, (
            select sum(subtotal) from transaksi 
            join penawaran on transaksi.id_transaksi = penawaran.id_transaksi
            where no_penawaran = b.no_penawaran and penawaran.tidak_terpakai=1
            ) as total_penawaran_loss,(
            select sum(subtotal)  from transaksi 
            join penawaran on transaksi.id_transaksi = penawaran.id_transaksi
            join penjualan on transaksi.id_transaksi=penjualan.id_transaksi
            where transaksi.tidak_terpakai=0 and no_penawaran=b.no_penawaran
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

            if ($month != '0' && $date == null) {
                $where = "and MONTH(pj.tgl_penjualan)=$month";
            } elseif ($date && $month == '0') {

                $where = "and DAY(pj.tgl_penjualan)=$date";
            } elseif ($month != null && $date != null) {

                $where = "and MONTH(pj.tgl_penjualan)=$month and DAY(pj.tgl_penjualan)=$date";
            }

            $query = " SELECT t.id_transaksi,
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
                        where t.tidak_terpakai=0 and jabatan_pegawai='SALES' $where";
        } else if ($this->type == 'quotation') {

            if ($month != '0' && $date == null) {
                $where = "and MONTH(p.tgl_penawaran)=$month";
            } elseif ($date && $month == '0') {

                $where = "and DAY(p.tgl_penawaran)=$date";
            } elseif ($month != null && $date != null) {

                $where = "and MONTH(p.tgl_penawaran)=$month and DAY(p.tgl_penawaran)=$date";
            }

            $query = "select b.*,(select sum(total) from transaksi 
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
                        where jabatan_pegawai='SALES'$where
						group by p.no_penawaran
						) b";
        }







        return DB::select($query);
    }
}

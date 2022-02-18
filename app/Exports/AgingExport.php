<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
class AgingExport implements FromView,ShouldAutoSize, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('aging.export', [
            'data' => DB::table('transaksi')
            ->selectRaw("no_tagihan,tgl_tagihan, DATE_ADD(tgl_tagihan, INTERVAL 31 DAY) AS DUE_DATE,nama_pelanggan, total, Datediff( CURDATE(),tgl_tagihan) as selisih,tgl_tagihan, 
            total AS total_selisih")
            ->join('pelanggan', 'transaksi.id_pelanggan', "=", "pelanggan.id_pelanggan")
            ->join('penawaran', 'penawaran.id_transaksi', "=", "transaksi.id_transaksi")
            ->join('tagihan', 'tagihan.id_transaksi', "=", "transaksi.id_transaksi")
            ->join('detail_transaksi_penawaran', 'detail_transaksi_penawaran.id_penawaran', "=", "penawaran.id_penawaran")
            ->where('status_transaksi', "=", "bill")
            ->groupBy('no_tagihan','tgl_tagihan')
            ->get(),
            'tittle' => "Aging Schedule",
            'pelanggan' => DB::table('pelanggan')->get(),
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $jumlah_baris=DB::table('transaksi')
        ->selectRaw('count(id_transaksi) as id')
        ->where('status_transaksi',"=",'bill')
        ->first();
        $jumlah_baris=$jumlah_baris->id;
        $jumlah_baris=$jumlah_baris+2;

        $sheet->getStyle("A1:H$jumlah_baris")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);
    }
}

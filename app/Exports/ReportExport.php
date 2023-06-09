<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\QuotationModel;

class ReportExport implements FromView, ShouldAutoSize
{

    private $QuotationModel;
    private $kode_transaksi;
    function __construct($kode_transaksi)
    {

        $this->kode_transaksi = $kode_transaksi;
        $this->QuotationModel = new QuotationModel();
    }

    public function view(): View
    {


        $data = [
            'tittle' => 'print Quotation',
            'data' => $this->QuotationModel->show($this->kode_transaksi),
        ];

        return view('quotation.print', $data);
    }
}

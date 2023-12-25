<?php

namespace App\Http\Controllers;

use App\Models\GeneralReport;
use Illuminate\Http\Request;
use App\Exports\QuotationExport;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Exception;



class GeneralReportController extends Controller
{

    public $GeneralReportModel;
    public function __construct()
    {
        $this->GeneralReportModel = new GeneralReport();
    }

    public function omzetReport()
    {
        $data = [
            'tittle' => 'Omzet Report ',
        ];

        return view('GeneralReport.omzetReport', $data);
    }

    public function omzetReportAjax()
    {
        // dd(request()->input('month'));
        $data = $this->GeneralReportModel->omzetReport(request()->input('month'), request()->input('date'), request()->input('date_to'));
        // dd($data);
        // echo json_encode([
        //     'data' => $data,
        //     'draw' => count($data)
        // ]);
        echo json_encode($data);
        // $dataTable = DataTables::of($data);
        // return $dataTable->toJson();
    }

    public function omzetReportExport($year_month = null, $date = null, $date_to = null)
    {

        $tgl = '';

        if ($year_month != 0) {
            $tgl = explode('-', $year_month)[1];
        } else if ($date != 0) {
            $tgl = $date;
        } else {

            $tgl = date('Y-m-d');
        }

        // try{
        //     Excel::download(new QuotationExport($year_month, $date, 'omzet', $date_to), "Omzet Report_$tgl.xlsx");
        // }catch(Exception $e){
        //     dd($e);
        // }


        return Excel::download(new QuotationExport($year_month, $date, 'omzet', $date_to), "Omzet Report_$tgl.xlsx");
    }
}

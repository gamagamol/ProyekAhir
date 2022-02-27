<?php

namespace App\Http\Controllers;

use App\Models\Custumor;
use Illuminate\Http\Request;
use App\Models\ReportDetailSalesModel;
use Illuminate\Support\Facades\DB;

class ReportDetailSales extends Controller
{
    public $model;
    public function __construct()
    {
        $this->model = new ReportDetailSalesModel();
    }

    public function index()
    {



        $data = [
            'tittle' => " Report Detail Sales",
            'data' => $this->model->index(),
            'custumor' => DB::table('pelanggan')->get(),
        ];
        return view('report.revenue', $data);
    }
}

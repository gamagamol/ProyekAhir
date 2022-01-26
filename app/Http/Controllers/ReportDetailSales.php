<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReportDetailSalesModel;

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

        ];
        return view('report.revenue', $data);
    }
}

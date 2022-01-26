<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseReportModel;
use Illuminate\Support\Facades\DB;

class PurchaseReportController extends Controller
{
    public $model;
    public function __construct()
    {
        $this->model= new PurchaseReportModel;
    }
    public function index()
    {
        $data=[
            'tittle'=>"Purchase Detail Report",
            'data'=>$this->model->index(),
        ];
        return view('report.purchase',$data);
    }

}

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
        $this->model = new PurchaseReportModel();
    }
    public function index()
    {
        $serch = request()->get('serch');
        if ($serch) {
            $data = $this->model->index($serch);
        } else {
            $data = $this->model->index();
        }
        $data = [
            'tittle' => "Purchase Detail Report",
            'data' => $data,
            'deta' => $this->model->index(),

        ];
        return view('report.purchase', $data);
    }
}

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
        $serch= request()->get('search');
        if ($serch) {
        
            if(request()->get('search')=='All'){
               $data= $this->model->index();
            }else{
                $data =  $this->model->index($serch);
            }
        }else{
            $data =  $this->model->index();

        }

        

        $data = [
            'tittle' => " Report Detail Sales",
            'data' => $data,
            'custumor' => DB::table('pelanggan')->get(),
        ];
        return view('report.revenue', $data);
    }


    public function status_transaki(){

        $serch=request()->input('serch');
        if ($serch) {
            $data= $this->model->status_transaki($serch);
        }else{
            $data = $this->model->status_transaki(); 
        }

        // dd($data);
        $data = [
            'tittle' => " Status Transaksi",
            'data' =>$data,
            'no_penjualan'=> $this->model->status_transaki(),

           
        ];
        
        return view('report.status_transaksi', $data);
    }



    
}

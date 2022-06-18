<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\Testmail;
use Illuminate\Support\Facades\Mail;
use App\Models\BillPaymentModel;
use App\Http\Controllers\BillPaymentController;
use Barryvdh\DomPDF\Facade as PDF;

class EmailController extends Controller
{
    public function __construct()
    {
        $this->model = new BillPaymentModel();
        $this->bc=new BillPaymentController;
        
    }
    public function email($no_tagihan)
    {


        $no_tagihan = str_replace('-', '/', $no_tagihan);
        $data = $this->model->detail($no_tagihan);
        
        $total=0;
        foreach($data as $d){
            $total+=$d->total;
        }

        $dueDate = $this->model->index($no_tagihan);

        

        // CETAK PDF
        $details = [
            'perwakilan' => $data[0]->perwakilan,
            'total'=> $total + $data[0]->ongkir,
            'data' => $data,
            'due_date'=>$dueDate[0]->DUE_DATE

            
        ];
        Mail::to($data[0]->email)->send(new \App\Mail\Testmail($details,$no_tagihan));
      
        $subject=$data[0]->email;
        return redirect()->to('bill')->with('email',"Email has been succes send to $subject");
    }

    
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AgingScheduleModel;
use Illuminate\Support\Facades\DB;
use App\Exports\AgingExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;
use App\Mail\Testmail;

class AgingScheduleController extends Controller
{
    public $model;
    public function __construct()
    {
        $this->model = new AgingScheduleModel();
    }
    public function index()
    {
        $id_pelanggan = request()->get('id_pelanggan');
        if ($id_pelanggan) {
            $data = $this->model->index($id_pelanggan);
        } else {
            $data = $this->model->index();
        }


        $pelanggan = DB::table('pelanggan')->get();
        if ($pelanggan) {
            $pelanggan = $pelanggan;
        } else {
            $pelanggan = 0;
        }
        // dd(date('Y-m-d'));
        // dd($data);
        $data = [
            'tittle' => "Aging Schedule",
            'data' => $data,
            'pelanggan' => $pelanggan


        ];
        return view('aging.index', $data);
    }

    public function export()
    {
        return Excel::download(new AgingExport, 'AgingScheduleExport.xlsx');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JurnalModel;

class JurnalController extends Controller
{
    public $JurnalModel;

    public function __construct()
    {
        $this->JurnalModel = new JurnalModel();
    }

    public function index()
    {


        $data = [
            'tittle' => " Journal",
            'data' => $this->JurnalModel->index()
        ];
        return view('jurnal.index', $data);
    }
}

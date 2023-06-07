<?php

namespace App\Http\Controllers;

use App\Models\SupplierModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public $Supplier_model;
    public function __construct()
    {
        $this->Supplier_model = new SupplierModel();
    }

    public function index()
    {
        $serch = request('cari');
        if ($serch) {
            $data = $this->Supplier_model->index($serch);
        } else {
            $data = $this->Supplier_model->index();
        }

        $data = [
            'tittle' => "Data Supplier",
            'data' => $data
        ];
        return view('supplier.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $data = [
            'tittle' => 'Add data Supplier',
        ];
        return view("supplier.insert", $data);
    }

    public function store(Request $request)
    {

        $rules = [
            'nama_pemasok' => 'required|string',
            'alamat_pemasok' => 'required',
            'perwakilan_pemasok' => 'required|string'
        ];
        $message = [
            
            'nama_pemasok.required' => "The NAME COMPANY'S field is required",
            'nama_pemasok.string' => "The NAME COMPANY'S must be string",
            'alamat_pemasok.required' => "The ADDRESS COMPANY'S field is required",
            'perwakilan_pemasok.required' => "The SIDE COMPANY'S must be string",
            'perwakilan_pemasok.string' => "The SIDE COMPANY'S must be string",

        ];
        $validated = Validator::make($request->all(), $rules, $message);

        if ($validated->fails()) {
            return redirect('supplier/create')->withErrors($validated)->withInput();
        }
        $validated = [
            'id_pemasok' => strtoupper($request->input('id_pemasok')),
            'nama_pemasok' => strtoupper($request->input('nama_pemasok')),
            'alamat_pemasok' => strtoupper($request->input('alamat_pemasok')),
            'perwakilan_pemasok' => strtoupper($request->input('perwakilan_pemasok')),
        ];
        $this->Supplier_model->insert($validated);
        return redirect('supplier')->with('success', 'Data Entered Successfully');
    }

    public function show($id)
    {
        $data = [
            'tittle' => 'Change Supplier Data',
            'data' => $this->Supplier_model->show($id)
        ];
        return view('supplier.update', $data);
    }
    public function update(Request $request)
    {
        $kode = $request->input('id_pemasok');
        $rules = [
            'nama_pemasok' => 'required|string',
            'alamat_pemasok' => 'required',
            'perwakilan_pemasok' => 'required|string'
        ];
        $message = [
            'nama_pemasok.required' => "The NAME COMPANY'S field is required",
            'nama_pemasok.string' => "The NAME COMPANY'S must be string",
            'alamat_pemasok.required' => "The ADDRESS COMPANY'S field is required",
            'perwakilan_pemasok.required' => "The SIDE COMPANY'S must be string",
            'perwakilan_pemasok.string' => "The SIDE COMPANY'S must be string",

        ];
        $validated = Validator::make($request->all(), $rules, $message);

        if ($validated->fails()) {
            return redirect()->route('supplier.show', $kode)->withErrors($validated)->withInput();
        }
        $validated = [
            'id_pemasok' => strtoupper($request->input('id_pemasok')),
            'nama_pemasok' => strtoupper($request->input('nama_pemasok')),
            'alamat_pemasok' => strtoupper($request->input('alamat_pemasok')),
            'perwakilan_pemasok' => strtoupper($request->input('perwakilan_pemasok')),
        ];
        $this->Supplier_model->updt($validated);
        return redirect('supplier')->with('success', 'Data Updated Successfully');
    }
}

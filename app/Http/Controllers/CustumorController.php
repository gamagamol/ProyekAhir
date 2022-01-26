<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustumorModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class CustumorController extends Controller
{
    public $custumor_model;
    public function __construct()
    {
        $this->custumor_model = new CustumorModel();
    }

    public function index()
    {
        $serch = request('cari');
        if ($serch) {
            $data = $this->custumor_model->index($serch);
        } else {
            $data = $this->custumor_model->index();
        }

        $data = [
            'tittle' => "Data Custumor",
            'data' => $data
        ];
        return view('Custumor.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $id_pelanggan = DB::table('pelanggan')
            ->selectRaw('concat("P00",max(substring(id_pelanggan,4,3))+2) as id_pelanggan')
            ->first();
        $data = [
            'tittle' => 'Add data Custumor',
            'id_pelanggan' =>  $id_pelanggan->id_pelanggan
        ];
        return view("Custumor.addCustumor", $data);
    }

    public function store(Request $request)
    {

        $rules = [
            'id_pelanggan' => 'required|unique:pelanggan,id_pelanggan',
            'nama_pelanggan' => 'required|string',
            'alamat_pelanggan' => 'required',
            'perwakilan' => 'required|string'
        ];
        $message = [
            'id_pelanggan.required' => "The CODE COMPANY'S field is required",
            'id_pelanggan.unique' => "The CODE COMPANY'S has already taken",
            'nama_pelanggan.required' => "The NAME COMPANY'S field is required",
            'nama_pelanggan.string' => "The NAME COMPANY'S must be string",
            'alamat_pelanggan.required' => "The ADDRESS COMPANY'S field is required",
            'perwakilan.required' => "The SIDE COMPANY'S must be string",
            'perwakilan.string' => "The SIDE COMPANY'S must be string",

        ];
        $validated = Validator::make($request->all(), $rules, $message);

        if ($validated->fails()) {
            return redirect('custumor/create')->withErrors($validated)->withInput();
        }
        $validated = [
            'id_pelanggan' => strtoupper($request->input('id_pelanggan')),
            'nama_pelanggan' => strtoupper($request->input('nama_pelanggan')),
            'alamat_pelanggan' => strtoupper($request->input('alamat_pelanggan')),
            'perwakilan' => strtoupper($request->input('perwakilan')),
        ];
        $this->custumor_model->insert($validated);
        return redirect('custumor')->with('success', 'Data Entered Successfully');
    }

    public function show($id)
    {
        $data = [
            'tittle' => 'Change Custumor Data',
            'data' => $this->custumor_model->show($id)
        ];
        return view('Custumor.editcustumor', $data);
    }
    public function update(Request $request)
    {
        $kode = $request->input('id_pelanggan');
        $rules = [
            'nama_pelanggan' => 'required|string',
            'alamat_pelanggan' => 'required',
            'perwakilan' => 'required|string'
        ];
        $message = [
            'nama_pelanggan.required' => "The NAME COMPANY'S field is required",
            'nama_pelanggan.string' => "The NAME COMPANY'S must be string",
            'alamat_pelanggan.required' => "The ADDRESS COMPANY'S field is required",
            'perwakilan.required' => "The SIDE COMPANY'S must be string",
            'perwakilan.string' => "The SIDE COMPANY'S must be string",

        ];
        $validated = Validator::make($request->all(), $rules, $message);

        if ($validated->fails()) {
            return redirect()->route('custumor.show', $kode)->withErrors($validated)->withInput();
        }
        $validated = [
            'id_pelanggan' => strtoupper($request->input('id_pelanggan')),
            'nama_pelanggan' => strtoupper($request->input('nama_pelanggan')),
            'alamat_pelanggan' => strtoupper($request->input('alamat_pelanggan')),
            'perwakilan' => strtoupper($request->input('perwakilan')),
        ];

        $this->custumor_model->updt($validated);
        return redirect('custumor')->with('success', 'Data Updated Successfully');
    }
}

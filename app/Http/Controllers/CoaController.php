<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CoaModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;




class CoaController extends Controller
{
    public $akun_model;
    public function __construct()
    {


        $this->akun_model = new CoaModel();
    }
    public function index()
    {

        $cari = request('cari');

        if ($cari) {
            $data = $this->akun_model->index($cari);
        } else {
            $data = $this->akun_model->index();
        }


        $data = [
            'tittle' => " Chart Of Account",
            'data' => $data,
            'cari' => DB::table('akun')->get(),
        ];
        return view("COA.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'tittle' => "Add account"
        ];
        return view("COA.addcoa", $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'kode_akun' => 'required|unique:akun|integer',
            'nama_akun' => 'required|string',
            'header_akun' => 'numeric|nullable'
        ];
        $message = [
            'kode_akun.required' => "The account code field is required!",
            'kode_akun.unique' => "The account code has already taken!",
            'kode_akun.integer' => "The account code must be integer!",
            'nama_akun.required' => "The account name field is required!",
            'nama_akun.alpha' => "The account name must be string!",
            'header_akun.integer' => "The account code must be integer!",
        ];
        $validated = Validator::make($request->all(), $rules, $message);
        if ($validated->fails()) {
            return redirect('COA/create')->withErrors($validated)->withInput();
        }
        $validated = [
            'kode_akun' => strtoupper($request->input('kode_akun')),
            'nama_akun' => strtoupper($request->input('nama_akun')),
            'header_akun' => strtoupper($request->input('header_akun')),
        ];

        $this->akun_model->insert($validated);
        return redirect('COA')->with('success', 'Data entered successfully');
    }

    public function show($kode)
    {

        $kode = $this->akun_model->show($kode);
        $data = [
            'tittle' => "Change Chart of Account",
            'data' => $kode
        ];
        return view('COA.update', $data);
    }



    public function update(Request $request,)
    {
        $kode = $request->input('kode_akun');
        $rules = [
            'kode_akun' => 'required|integer',
            'nama_akun' => 'required|alpha',
            'header_akun' => 'numeric|nullable'
        ];
        $message = [
            'kode_akun.required' => "The account code field is required!",
            'kode_akun.integer' => "The account code must be integer!",
            'nama_akun.required' => "The account name field is required!",
            'nama_akun.alpha' => "The account name must be string!",
            'header_akun.integer' => "The account code must be integer!",
        ];
        $validated = Validator::make($request->all(), $rules, $message);
        if ($validated->fails()) {
            return redirect()->route('COA.show', $kode)->withErrors($validated)->withInput();
        }
        $validated = [
            'kode_akun' => strtoupper($request->input('kode_akun')),
            'nama_akun' => strtoupper($request->input('nama_akun')),
            'header_akun' => strtoupper($request->input('header_akun')),
        ];

        $this->akun_model->updt($validated);
        return redirect('COA')->with('success', 'Data Updated successfully');
    }
}

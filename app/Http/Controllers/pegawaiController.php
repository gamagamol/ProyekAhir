<?php

namespace App\Http\Controllers;

use App\Models\pegawaiModel;
use App\Models\User;
use Illuminate\Http\Request;


class pegawaiController extends Controller
{

    public $pegawai;
    public $user;


    public function __construct()
    {
        $this->pegawai = new pegawaiModel();
        $this->user = new User();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'data' => $this->pegawai->index(),
            'tittle' => 'Master Data Pegawai'
        ];
        // dd($data);
        return view('pegawai.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('pegawai.insert', [
            'tittle' => 'Tambah Pegawai',
            'position' => $this->pegawai->getListPosition()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'kode_pegawai' => 'required|unique:pegawai,kode_pegawai',
            'nama_pegawai' => 'required',
            'jabatan_pegawai' => 'required',
            'jabatan_pegawai_input' => 'unique:pegawai,jabatan_pegawai'
        ]);

    

        $this->pegawai->insert([
            'kode_pegawai' => strtoupper($request->input('kode_pegawai')),
            'nama_pegawai' => strtoupper($request->input('nama_pegawai')),
            'jabatan_pegawai' => ($request->input('jabatan_pegawai_input')!=null) ? strtoupper($request->input('jabatan_pegawai_input')) : strtoupper($request->input('jabatan_pegawai')),
        ]);

        return redirect('pegawai')->with('success', 'Success to insert employee data');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = [
            'data' => $this->pegawai->getEmployeeById($id),
            'tittle' => 'Master Data Pegawai',
            'position' => $this->pegawai->getListPosition()

        ];
        // dd($data);
        return view('pegawai.update', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {

        $this->pegawai->updatePegawai($request->input('id'), [
            'kode_pegawai' => strtoupper($request->input('kode_pegawai')),
            'nama_pegawai' => strtoupper($request->input('nama_pegawai')),
            'jabatan_pegawai' => strtoupper($request->input('jabatan_pegawai')),
        ]);

        return redirect('pegawai')->with('success', 'Success to Update employee data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $ussername = request()->session()->get('ussername');
        $id_pegawai = $this->user->getIdByUssername($ussername);

        $this->pegawai->updatePegawai($id, [
            'deleted_by' => $id_pegawai->id,
            'deleted_at' => date('Y-m-d'),
        ]);

        return redirect('pegawai')->with('success', 'Success to Delete employee data');
    }
}

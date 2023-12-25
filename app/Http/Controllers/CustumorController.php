<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustumorModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class CustumorController extends Controller
{
    public $custumor_model;
    public $top;
    public function __construct()
    {
        $this->custumor_model = new CustumorModel();
        $this->top = [30, 45, 60];
    }

    public function index()
    {
        $serch = request('cari');
        if ($serch) {
            $data = $this->custumor_model->index($serch);
            $type = 'false';
        } else {
            $data = $this->custumor_model->index();
            $type = 'true';
        }


        $data = [
            'tittle' => "Data Custumor",
            'data' => $data,
            'type' => $type,
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
            ->selectRaw('CONCAT("P", MAX(CAST(TRIM("P" FROM id_pelanggan) AS SIGNED))+1) as id_pelanggan')
            ->first();

        $data = [
            'tittle' => 'Add data Custumor',
            'id_pelanggan' =>  $id_pelanggan->id_pelanggan,
            "top" => $this->top,
        ];

        return view("Custumor.addCustumor", $data);
    }

    public function store(Request $request)
    {

        $rules = [
            'id_pelanggan' => 'required|unique:pelanggan,id_pelanggan',
            'nama_pelanggan' => 'required|string',
            'alamat_pelanggan' => 'required',
            'perwakilan' => 'required|string',
            'email' => 'required|email:rfc,dns',
            'contact' => 'required',
            'kota' => 'required',
            'nb' => 'required',
            'npwp' => 'required',
            'alamat_npwp' => 'required',
            'top' => 'required',
        ];
        $message = [
            'id_pelanggan.required' => "The CODE COMPANY'S field is required",
            'id_pelanggan.unique' => "The CODE COMPANY'S has already taken",
            'nama_pelanggan.required' => "The NAME COMPANY'S field is required",
            'nama_pelanggan.string' => "The NAME COMPANY'S must be string",
            'alamat_pelanggan.required' => "The ADDRESS COMPANY'S field is required",
            'perwakilan.required' => "The SIDE COMPANY'S must be string",
            'perwakilan.string' => "The SIDE COMPANY'S must be string",
            'contact.required' => "The CONTACT COMPANY'S field is required",
            'kota.required' => "The CITY COMPANY'S field is required",
            'nb.required' => "The NB COMPANY'S field is required",
            'npwp.required' => "The NPWP COMPANY'S field is required",
            'alamat_npwp.required' => "The NPWP ADDRES COMPANY'S field is required",
            'top.required' => "The Term Of Payment field is required",
        ];
        $validated = Validator::make($request->all(), $rules, $message);
        // dd($validated->fails());

        if ($validated->fails()) {
            return redirect('custumor/create')->withErrors($validated)->withInput();
        }
        $validated = [
            'id_pelanggan' => strtoupper($request->input('id_pelanggan')),
            'nama_pelanggan' => strtoupper($request->input('nama_pelanggan')),
            'alamat_pelanggan' => strtoupper($request->input('alamat_pelanggan')),
            'perwakilan' => strtoupper($request->input('perwakilan')),
            'email' => $request->input('email'),
            'contact' => $request->input('contact'),
            'nb' => $request->input('nb'),
            'npwp' => $request->input('npwp'),
            'alamat_npwp' => $request->input('alamat_npwp'),
            'alamat_npwp' => $request->input('alamat_npwp'),
            'kota' => $request->input('kota'),
            'top' => $request->input('top'),
            'note_khusus' => $request->input('note_khusus'),
        ];
        $this->custumor_model->insert($validated);
        return redirect('custumor')->with('success', 'Data Entered Successfully');
    }

    public function show($id)
    {
        $data = [
            'tittle' => 'Change Custumor Data',
            'data' => $this->custumor_model->show($id),
            "top" => $this->top,
        ];
        // dd($data);
        return view('Custumor.editcustumor', $data);
    }
    public function update(Request $request)
    {
        $kode = $request->input('id_pelanggan');
        $rules = [
            // 'id_pelanggan' => 'required|unique:pelanggan,id_pelanggan',
            'nama_pelanggan' => 'required|string',
            'alamat_pelanggan' => 'required',
            'perwakilan' => 'required|string',
            'email' => 'required|email:rfc,dns',
            'contact' => 'required',
            'kota' => 'required',
            'nb' => 'required',
            'npwp' => 'required',
            'alamat_npwp' => 'required',
            'top' => 'required',
        ];
        $message = [
            // 'id_pelanggan.required' => "The CODE COMPANY'S field is required",
            'id_pelanggan.unique' => "The CODE COMPANY'S has already taken",
            'nama_pelanggan.required' => "The NAME COMPANY'S field is required",
            'nama_pelanggan.string' => "The NAME COMPANY'S must be string",
            'alamat_pelanggan.required' => "The ADDRESS COMPANY'S field is required",
            'perwakilan.required' => "The SIDE COMPANY'S must be string",
            'perwakilan.string' => "The SIDE COMPANY'S must be string",
            'contact.required' => "The CONTACT COMPANY'S field is required",
            'kota.required' => "The CITY COMPANY'S field is required",
            'nb.required' => "The NB COMPANY'S field is required",
            'npwp.required' => "The NPWP COMPANY'S field is required",
            'alamat_npwp.required' => "The NPWP ADDRES COMPANY'S field is required",
            'top.required' => "The Term Of Payment field is required",


        ];
        $validated = Validator::make($request->all(), $rules, $message);

        // dd($request->all());
        // dd($validated->errors());

        if ($validated->fails()) {
            return redirect()->route('custumor.show', $kode)->withErrors($validated)->withInput();
        }
        $validated = [
            'id_pelanggan' => strtoupper($request->input('id_pelanggan')),
            'nama_pelanggan' => strtoupper($request->input('nama_pelanggan')),
            'alamat_pelanggan' => strtoupper($request->input('alamat_pelanggan')),
            'perwakilan' => strtoupper($request->input('perwakilan')),
            'email' => $request->input('email'),
            'contact' => $request->input('contact'),
            'nb' => $request->input('nb'),
            'npwp' => $request->input('npwp'),
            'alamat_npwp' => $request->input('alamat_npwp'),
            'alamat_npwp' => $request->input('alamat_npwp'),
            'kota' => $request->input('kota'),
            'top' => $request->input('top'),
            'note_khusus' => $request->input('note_khusus'),

        ];

        $this->custumor_model->updt($validated);
        return redirect('custumor')->with('success', 'Data Updated Successfully');
    }
}

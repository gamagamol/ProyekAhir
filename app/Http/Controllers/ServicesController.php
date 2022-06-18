<?php

namespace App\Http\Controllers;

use App\Models\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ServicesController extends Controller
{
    
    public function __construct()
    {
        $this->services=new Services();
    }


    public function index()
    {
        $serch=request()->get('cari');
        if($serch){
           
            $data=$this->services->index($serch);
        }else{
            $data=$this->services->index();
        }


        $data=[
            'tittle'=>'List Services',
            'data'=>$data,
            'services'=> $data = $this->services->index()
        ];
        return view('services.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('services.create',['tittle'=>'Create Services']);
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
                'nama_layanan'=>'required'
            ]);

           Services::insert(['nama_layanan'=>$request->nama_layanan]);
           return redirect('services')->with('success','Success to insert service data');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Services  $services
     * @return \Illuminate\Http\Response
     */
    public function show($id_layanan)
    {
       
        $data=[
            'tittle'=>'Update Service',
            'data'=>
            Services::where('id_layanan', $id_layanan)->first()

        ];

        return view('services.update',$data);
    }

  

  
    public function update(Request $request)
    {
        $request->validate([
            'nama_layanan'=>'required'
        ]);
        $data= ['nama_layanan'=> $request->nama_layanan];
        DB::table('layanan')->where('id_layanan',$request->id_layanan)->update($data);
        return redirect('services')->with('success', 'Success to update service data');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Services  $services
     * @return \Illuminate\Http\Response
     */
    public function destroy(Services $services)
    {
        //
    }
}

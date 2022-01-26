<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;



class ProductController extends Controller
{
    public $product_model;
    public function __construct()
    {
        $this->product_model = new ProductModel();
    }

    public function index()
    {
        $serch = request('cari');
        if ($serch) {
            $data = $this->product_model->index($serch);
        } else {
            $data = $this->product_model->index();
        }

        $data = [
            'tittle' => 'List Product',
            'data' => $data
        ];
        return  view('Product.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'tittle' => "Add data product"
        ];
        return view('Product.AddProduct', $data);
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
            'nama_produk' => 'required|string',
            'bentuk_produk' => 'required|string'
        ];
        $message = [
            'nama_produk.required' => 'The NAME PRODUCT field is required!',
            'nama_produk.string' => 'The NAME PRODUCT must be string!',
            'bentuk_produk.required' => 'The FORM PRODUCT field is required!',
            'bentuk_produk.string' => 'The FORM PRODUCT must be string!',
        ];
        $validated = Validator::make($request->all(), $rules, $message);

        if ($validated->fails()) {
            return redirect('product/create')->withErrors($validated)->withInput();
        }


        $validated = [
            'id_produk' => $request->input('id_produk'),
            'nama_produk' => strtoupper($request->input('nama_produk')),
            'jenis_produk' => $request->input('jenis_produk'),
            'bentuk_produk' => $request->input('bentuk_produk')
        ];

        $this->product_model->insert($validated);
        return redirect('product')->with('success', 'Data Entered Successfully');
    }


    public function show($kode)
    {
        $data = $this->product_model->show($kode);
        $data = [
            'tittle' => " Change Product Data",
            'data' => $data
        ];
        return view('Product.editproduct', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Custumor  $custumor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $kode = $request->input('id_produk');
        $rules = [
            'nama_produk' => 'required|string',
            'bentuk_produk' => 'required|string'
        ];
        $message = [
            'nama_produk.required' => 'The NAME PRODUCT field is required!',
            'nama_produk.string' => 'The NAME PRODUCT must be string!',
            'bentuk_produk.required' => 'The FORM PRODUCT field is required!',
            'bentuk_produk.string' => 'The FORM PRODUCT must be string!',
        ];
        $validated = Validator::make($request->all(), $rules, $message);

        if ($validated->fails()) {
            return redirect()->route('product.show', $kode)->withErrors($validated)->withInput();
        }


        $validated = [
            'id_produk' => $request->input('id_produk'),
            'nama_produk' => strtoupper($request->input('nama_produk')),
            'jenis_produk' => $request->input('jenis_produk'),
            'bentuk_produk' => $request->input('bentuk_produk')
        ];
        $this->product_model->updt($validated);
        return redirect('product')->with('success', 'Data Updated Successfully');
    }
}

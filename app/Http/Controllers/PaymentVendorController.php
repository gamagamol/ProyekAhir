<?php

namespace App\Http\Controllers;

use App\Models\PaymentVendorModel;
use Illuminate\Http\Request;

class PaymentVendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $data=[
          'tittle'=>"Payment To Vendor",
      ];
      return view('paymentvendor.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        echo "hello";
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PaymentVendorModel  $paymentVendorModel
     * @return \Illuminate\Http\Response
     */
    public function show(PaymentVendorModel $paymentVendorModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PaymentVendorModel  $paymentVendorModel
     * @return \Illuminate\Http\Response
     */
    public function edit(PaymentVendorModel $paymentVendorModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PaymentVendorModel  $paymentVendorModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PaymentVendorModel $paymentVendorModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PaymentVendorModel  $paymentVendorModel
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaymentVendorModel $paymentVendorModel)
    {
        //
    }
}

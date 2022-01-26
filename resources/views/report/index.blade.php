@extends('template.index')
@section('content')
    <div class="container">
        <div class="card shadow mb-4">
            <div class="card-header py-3 mt-2">
                <h6 class="m-0 font-weight-bold text-primary">Sales Detail Report </h6>
            </div>

            <form action="" method="post">
                <div class="form-group col-md-6 ml-2 mt-2">
                    <input type="text" name='cari' class="form-control " id="formGroupExampleInput"
                        placeholder="Find Your Custumor" autocomplete="off" autofocus>
                </div>
                <button type=submit name=submit class="btn btn-primary ml-4">submit</button>
            </form>
            <div class="card-body">
                <div class="table-responsive text-center">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

                        <tr>
                            <td>No</td>
                            <td>Quotation Number</td>
                            <td>Invoice Number</td>
                            <td>Invoice Date</td>
                            <td>Custumor Name</td>
                            <td>Amount</td>
                            <td>Vat</td>
                            <td>shipment</td>
                            <td>Total Amount </td>
                            <td>Quotation Detail </td>


                        </tr>
                       @foreach ($data as $d )
                       <tr>
                           <td>{{$loop->iteration}}</td>
                           <td>{{$d->no_penawaran}}</td>
                           <td>{{$d->no_tagihan}}</td>
                           <td>{{$d->tgl_tagihan}}</td>
                           <td>{{$d->nama_pelanggan}}</td>
                           <td>{{"Rp.".number_format($d->subtotal, 2, ',', '.')}}</td>
                           <td>{{"Rp.".number_format($d->ppn, 2, ',', '.')}}</td>
                           <td>{{"Rp.".number_format($d->ongkir, 2, ',', '.')}}</td>
                           <td>{{"Rp.".number_format($d->total, 2, ',', '.')}}</td>
                           <td>
                               <a href={{ url('quotation', $d->kode_transaksi) }} class="btn btn-primary">Detail Quotation</a>
                           </td>
                           </tr>

                       @endforeach
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection()

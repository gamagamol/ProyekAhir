@extends('template.index')
@section('content')
    <div class="container">
        <div class="card shadow mb-4">
            <div class="card-header py-3 mt-2">
                <h6 class="m-0 font-weight-bold text-primary">Purchase Detail Report </h6>
            </div>

            <form action="" method="post">
                <div class="form-group col-md-6 ml-2 mt-2">
                    <input type="text" name='cari' class="form-control " id="formGroupExampleInput"
                        placeholder="Find Your Supplier" autocomplete="off" autofocus>
                </div>
                <button type=submit name=submit class="btn btn-primary ml-4">submit</button>
            </form>
            <div class="card-body">
                <div class="table-responsive text-center">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

                        <tr>
                            <td>No</td>
                            <td>Supplier Name</td>
                            <td>Purchase Number</td>
                            <td>Purchase Date</td>
                            <td>Due Date</td>
                            <td>Total Amount </td>

                        </tr>
                       @foreach ($data as $d )
                       <tr>
                           <td>{{$loop->iteration}}</td>
                           <td>{{$d->nama_pemasok}}</td>
                           <td>{{$d->no_pembelian}}</td>
                           <td>{{$d->tgl_pembelian}}</td>
                           <td>{{$d->DUE_DATE}}</td>
                           <td>{{"Rp.".number_format($d->subtotal, 2, ',', '.')}}</td>
                          
                           </tr>

                       @endforeach
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection()

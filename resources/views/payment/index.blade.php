@extends('template.index')
@section('content')

@if(session()->has('success'))
<div class="alert alert-success" role="alert">
    {{session('success')}}
  </div>
@endif
<div class="container"> 
<div class="card shadow mb-4">
    <div class="card-header py-3 mt-2">
        <h6 class="m-0 font-weight-bold text-primary">Payment</h6>
    </div>
  
        <form action="" method="post">
       <div class="form-group col-md-6 ml-2 mt-2">
            <input type="text" name='cari' class="form-control " id="formGroupExampleInput" placeholder="Find Your Number Delivery" autocomplete="off" autofocus>
        </div>
        <button type=submit name=submit class="btn btn-primary ml-4">submit</button>
       </form>
    <div class="card-body">
      
       <div class="table-responsive text-center">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

                        <tr>
                            <td>No</td>
                            <td>customer</td>
                            <td>Date</td>
                            <td>No Payment</td>
                            <td>Job number</td>
                            <td>Weight (kg)</td>
                            <td>Total Amount</td>
                            <td>Processing</td>
                            <td>Prepared</td>
                            <td>Action</td>



                        </tr>
                        <?php $i = 1; ?>
                        @foreach ($data as $d)


                            <tr>
                                <td> {{ $loop->iteration }}</td>
                                <td>{{ $d->nama_pelanggan }}</td>
                                <td style="min-width:120px">{{ $d->tgl_pembayaran }}</td>
                                <td>{{ $d->no_pembayaran }}</td>
                                <td>{{ $d->nomor_pekerjaan }}</td>
                               
                                <td>{{ $d->berat }}</td>
                                
                                <td>{{ 'Rp.' . number_format($d->total) }}</td>
                                <td>{{ $d->layanan }}</td>
                                <td>{{ $d->nama_pengguna }}</td>
                                <td>

                                  

                                    <a href="{{ url('payment/detail', str_replace('/','-',$d->no_pembayaran)) }}" class="btn btn-info mt-1">
                                        Detail </a>
                                </td>
                            </tr>







                        @endforeach
                    </table>
                    {{ $data->links() }}

                </div>
    </div>
</div>
  </div> 
@endsection()



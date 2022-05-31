@extends('template.index')
@section('content')

    @if (session()->has('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif
    <div class="container">
        <div class="card shadow mb-4">
            <div class="card-header py-3 mt-2">
                <h6 class="m-0 font-weight-bold text-primary"> {{ $tittle }} </h6>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-md-4 mt-3">
                        <form action={{ url('paymentvendor') }} method="GET" id="serch-form">
                            <select class="form-control form-select" aria-label="Default select example" name='serch'
                                id="serch">
                               @foreach ($deta as $d )
                                   <option value="{{$d->no_pembelian}}">{{$d->no_pembelian}}</option>
                               @endforeach
                            </select>
                            <button type=submit name=submit class="btn btn-primary mt-3" id="serch-button">submit</button>
                        </form>
                    </div>
                </div>

            </div>
            <div class="card-body">

                <div class="table-responsive text-center">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

                        <tr>
                            <td>No</td>
                            <td>Supplier</td>
                            <td>Date</td>
                            <td>No Purchase Order</td>
                            <td>Job Number</td>
                            <td>Prepared</td>
                            <td>Action</td>



                        </tr>
                        <?php $i = 1; ?>
                        @foreach ($data as $d)

                            <tr>
                                <td> {{ $loop->iteration }}</td>
                                <td>{{ $d->nama_pemasok }}</td>
                                <td style="min-width:120px">{{ $d->tgl_pembelian }}</td>
                                <td>{{ $d->no_pembelian }}</td>
                                <td>{{ $d->nomor_pekerjaan }}</td>

                                
                                <td>{{ $d->nama_pengguna }}</td>
                                <td>

                                    <a href="{{ url('paymentvendor', str_replace('/', '-', $d->no_pembelian)) }}"
                                        class="btn btn-primary mt-1">
                                        payment </a>

                                    <a href="{{ url('paymentvendor/detail', str_replace('/', '-', $d->no_pembelian)) }}"
                                        class="btn btn-info mt-1">
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

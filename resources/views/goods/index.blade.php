@extends('template.index')
@section('content')
    <div class="container">
        @if (session()->has('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        <div class="card shadow mb-4">
            <div class="card-header py-3 mt-2">
                <h6 class="m-0 font-weight-bold text-primary">Goods Recipt</h6>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-md-4 mt-3">
                        <form action={{ url('goods') }} method="GET" id="serch-form">
                            <select class="form-control form-select" aria-label="Default select example" name='serch'
                                id="serch">
                                @foreach ($deta as $d)
                                    <option value={{ $d->no_penerimaan }}>{{ $d->no_penerimaan }}</option>
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
                            <td>Date</td>
                            <td>No Goods Recipt </td>
                            <td>Job number</td>
                          
                            <td>Weight(Kg)</td>
                           
                            <td>Total Amount</td>
                            <td>Processing</td>
                            <td>customer</td>
                            <td>Prepared</td>
                            <td>Action</td>



                        </tr>
                        <?php $i = 1; ?>
                        @foreach ($data as $d)


                            <tr>
                                <td> {{ $loop->iteration }}</td>
                                <td style="min-width:120px">{{ $d->tgl_penerimaan }}</td>
                                <td>{{ $d->no_penerimaan }}</td>
                                <td>{{ $d->nomor_pekerjaan }}</td>
                               
                                <td>{{ $d->berat }}</td>
                               
                                <td>{{ 'Rp.' . number_format($d->total) }}</td>
                                <td>{{ $d->layanan }}</td>
                                <td>{{ $d->nama_pelanggan }}</td>
                                <td>{{ $d->nama_pengguna }}</td>
                                <td>

                                    <a class="btn btn-primary" href="{{ url('delivery', str_replace('/','-',$d->no_penerimaan)) }}">

                                        Delivery
                                    </a>
                                     <a href="{{ url('goods/detail', str_replace('/','-',$d->no_penerimaan)) }}" class="btn btn-info mt-1">
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

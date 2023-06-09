@extends('template.index')
@section('content')
    @if (session()->has('success'))
        <div class="alert alert-success" role="alert">
            <button type="button" class="close" data-dismiss="alert">×</button>
            {{ session('success') }}
        </div>
    @elseif (session()->has('email'))
        <div class="alert alert-success" role="alert">
            <button type="button" class="close" data-dismiss="alert">×</button>
            {{ session('email') }}
        </div>
    @endif
    <div class="container-xxl mx-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3 mt-2">
                <h6 class="m-0 font-weight-bold text-primary">Bill Payment</h6>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-md-4 mt-3">
                        <form action={{ url('bill') }} method="GET" id="serch-form">
                            <select class="form-control form-select" aria-label="Default select example" name='serch'
                                id="serch">
                                <option value="All">All</option>
                                @foreach ($deta as $d)
                                    <option value={{ $d->no_tagihan }}>{{ $d->no_tagihan }}</option>
                                @endforeach
                            </select>
                            <button type=submit name=submit class="btn btn-primary mt-3" id="serch-button">submit</button>
                        </form>
                    </div>
                </div>
            </div>



            <div class="card-body">

                <a href="{{ url('show') }}" class="btn btn-primary mb-3">Create
                    Bill Payment</a>
                <div class="table-responsive text-center">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

                        <tr>
                            <td>No</td>
                            <td>customer</td>
                            <td>Date</td>
                            <td>Due Date</td>
                            <td>No Bill Payment</td>
                            <td>Prepared</td>
                            <td>Action</td>
                            <td>Document</td>




                        </tr>
                        <?php $i = 1; ?>
                        @foreach ($data as $d)
                            <tr>
                                <td> {{ $loop->iteration }}</td>
                                <td>{{ $d->nama_pelanggan }}</td>
                                <td style="min-width:120px">{{ $d->tgl_tagihan }}</td>
                                <td style="min-width:120px">{{ $d->DUE_DATE }}</td>
                                <td>{{ $d->no_tagihan }}</td>
                                <td>{{ $d->nama_pengguna }}</td>
                                <td>


                                    <a href="{{ url('payment/show', str_replace('/', '-', $d->no_tagihan)) }}"
                                        class="btn btn-primary mt-1"
                                        @if ($d->status_transaksi == 'payment') {{ 'hidden' }} @endif> Payment</a>

                                    <a href="{{ url('bill/detail', str_replace('/', '-', $d->no_tagihan)) }}"
                                        class="btn btn-info mt-2">Detail</a>


                                </td>

                                <td>
                                    <a href="{{ url('bill/print', str_replace('/', '-', $d->no_tagihan)) }}"
                                        class="btn btn-info mt-2 w-75" ><i class="fa fa-print"
                                            aria-hidden="true"></i></a>
                                    {{-- <a href="{{ url('email', str_replace('/', '-', $d->no_tagihan)) }}"
                                        class="btn btn-secondary mt-2 w-75"
                                        @if ($d->status_transaksi == 'payment') {{ 'hidden' }} @endif> <i
                                            class="fa fa-envelope" aria-hidden="true"></i>
                                    </a> --}}
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

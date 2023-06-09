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
                <h6 class="m-0 font-weight-bold text-primary">Quotation</h6>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-md-4 mt-3">
                        <form action={{ url('quotation') }} method="GET" id="serch-form">
                            <select class="form-control form-select" aria-label="Default select example" name='serch'
                                id="serch">
                                <option value="ALL">All</option>
                                @foreach ($deta as $d)
                                    <option value={{ $d->no_penawaran }}>{{ $d->no_penawaran }}</option>
                                @endforeach
                            </select>
                            <button type=submit name=submit class="btn btn-primary mt-3" id="serch-button">submit</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <a href="{{ url('quotation/create') }}" class="btn btn-primary ml-1 mt-3 mb-3"> <i
                        class="fas fa-plus-circle me-1  " style="letter-spacing: 2px"></i> Add Item</a>
                <div class="table-responsive text-center">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

                        <tr>
                            <td>No</td>
                            <td>Date</td>
                            <td>No Quotation</td>
                            <td>Processing</td>
                            <td>customer</td>
                            <td>Prepared</td>
                            <td>Sales</td>
                            <td>Action</td>
                            <td>Document</td>
                        </tr>
                        <?php $i = 1; ?>
                        @foreach ($data as $d)
                            <tr>
                                <td> {{ $loop->iteration }}</td>
                                <td style="min-width:120px">{{ $d->tgl_penawaran }}</td>
                                <td>{{ $d->no_penawaran }}</td>
                                <td>{{ $d->nomor_pekerjaan }}</td>

                                <td>{{ $d->nama_pelanggan }}</td>
                                <td>{{ $d->nama_pengguna }}</td>
                                <td>{{ $d->nama_pegawai }}</td>
                                <td>


                                    <a href="{{ url('sales', $d->kode_transaksi) }}" class="btn btn-primary mt-1"
                                        @if ($d->status_transaksi != 'quotation') {{ 'hidden' }} @endif>
                                        Sales </a>

                                    <a href="{{ url('quotation', $d->kode_transaksi) }}" class="btn btn-info mt-1">
                                        Detail </a>
                                </td>
                                <td>
                                    <a href={{ url('quotation/print', $d->kode_transaksi) }} class="btn btn-primary"
                                       >
                                        Print</a>
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

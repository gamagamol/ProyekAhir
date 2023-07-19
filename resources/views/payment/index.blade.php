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
                <h6 class="m-0 font-weight-bold text-primary">Payment</h6>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-md-4 mt-3">
                        <form action={{ url('payment') }} method="GET" id="serch-form">
                            <select class="form-control form-select" aria-label="Default select example" name='serch'
                                id="serch">
                                <option value="All">All</option>
                                @foreach ($deta as $d)
                                    <option value={{ $d->no_pembayaran }}>{{ $d->no_pembayaran }}</option>
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
                        <thead>

                            <tr>
                                <td>No</td>
                                <td>No Purchase </td>
                                <td>Customer</td>
                                <td>Date Payment</td>
                                <td>No Payment </td>
                                <td>Job number</td>
                                <td>Prepared</td>
                                <td>Action</td>



                            </tr>
                        </thead>
                        <tbody>

                            <?php $i = 1; ?>
                            @foreach ($data as $d)
                                <tr>
                                    <td> {{ $loop->iteration }}</td>
                                    <td>{{ $d->no_pembelian }}</td>
                                    <td>{{ $d->nama_pelanggan }}</td>
                                    <td style="min-width:120px">{{ $d->tgl_pembayaran }}</td>
                                    <td>{{ $d->no_pembayaran }}</td>
                                    <td>{{ $d->nomor_pekerjaan }}</td>
                                    <td>{{ $d->nama_pengguna }}</td>
                                    <td>
                                        <a href="{{ url('payment/detail', str_replace('/', '-', $d->no_pembayaran)) }}"
                                            class="btn btn-info mt-1">
                                            Detail </a>
                                        <a href="{{ url('payment/print', str_replace('/', '-', $d->no_pembayaran)) }}"
                                            class="btn btn-primary mt-1">
                                            print </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                    {{-- {{ $data->links() }} --}}

                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            let table = new DataTable('#dataTable');
        })
    </script>
@endsection()

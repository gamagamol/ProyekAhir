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

            <div class="card-body">

                <div class="table-responsive text-center">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>

                            <tr>
                                <td>No</td>
                                <td>Date Purchase</td>
                                <td>No Purchase </td>
                                <td>Customer</td>
                                <td>Payment Date</td>
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
                                    <td>{{ $d->tgl_penjualan }}</td>
                                    <td>{{ $d->no_penjualan }}</td>
                                    <td>{{ $d->nama_pelanggan }}</td>
                                    <td>{{ $d->tgl_pembayaran }}</td>
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

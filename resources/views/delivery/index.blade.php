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
                <h6 class="m-0 font-weight-bold text-primary">Delivery Order</h6>
            </div>


            <div class="card-body">

                <div class="table-responsive text-center">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>

                            <tr>
                                <td>No</td>
                                <td>Delivery Date</td>
                                <td>No Sales </td>
                                <td>No Delivery </td>
                                <td>Transaction Number</td>
                                <td>Customer</td>
                                <td>Prepared</td>
                                <td>Action</td>
                                <td>Document</td>



                            </tr>
                        </thead>
                        <tbody>

                            <?php $i = 1; ?>
                            @foreach ($data as $d)
                                <tr>
                                    <td> {{ $loop->iteration }}</td>
                                    <td style="min-width:120px">{{ $d->tgl_pengiriman }}</td>
                                    <td>{{ $d->no_penjualan }}</td>
                                    <td>{{ $d->no_pengiriman }}</td>
                                    <td>{{ $d->nomor_transaksi }}</td>
                                    <td>{{ $d->nama_pelanggan }}</td>
                                    <td>{{ $d->nama_pengguna }}</td>
                                    <td>
                                        <a href="{{ url('delivery/detail', str_replace('/', '-', $d->no_pengiriman)) }}"
                                            class="btn btn-info mt-1">
                                            Detail </a>
                                    </td>
                                    <td>
                                        <a href={{ url('delivery/print', str_replace('/', '-', $d->no_pengiriman)) }}
                                            class="btn btn-primary">Print </a>
                                        <a href={{ url('delivery/print/stiker', str_replace('/', '-', $d->no_pengiriman)) }}
                                            class="btn btn-primary mt-2"> Stiker</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            let table = new DataTable('#dataTable', {
                "scrollY": "300px",
                "scrollX": "300px",

            });
        })
    </script>
@endsection()

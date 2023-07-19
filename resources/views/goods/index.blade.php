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

           
            <div class="card-body">

                <div class="table-responsive text-center">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>

                            <tr>
                                <td>No</td>
                                <td>Goods Date</td>
                                <td>No Purchase</td>
                                <td>No Goods Recipt </td>
                                <td>Job number</td>
                                <td>customer</td>
                                <td>Prepared</td>
                                <td>Action</td>



                            </tr>
                        </thead>
                        <tbody>

                            <?php $i = 1; ?>
                            @foreach ($data as $d)
                                <tr>
                                    <td> {{ $loop->iteration }}</td>
                                    <td style="min-width:120px">{{ $d->tgl_penerimaan }}</td>
                                    <td>{{ $d->no_pembelian }}</td>
                                    <td>{{ $d->no_penerimaan }}</td>
                                    <td>{{ $d->nomor_pekerjaan }}</td>
                                    <td>{{ $d->nama_pelanggan }}</td>
                                    <td>{{ $d->nama_pengguna }}</td>
                                    <td>


                                        <a class="btn btn-primary"
                                            href="{{ url('delivery', str_replace('/', '-', $d->no_penerimaan)) }}"
                                            @if ($d->jumlah_detail_pengiriman == $d->jumlah_detail_penerimaan) {{ 'hidden' }} @endif>

                                            Delivery
                                        </a>
                                        <a href="{{ url('goods/detail', [str_replace('/', '-', $d->no_pembelian), str_replace('/', '-', $d->no_penerimaan)]) }}"
                                            class="btn btn-info mt-1">
                                            Detail </a>

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
            let table = new DataTable('#dataTable');
        })
    </script>
@endsection()

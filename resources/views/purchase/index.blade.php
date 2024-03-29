@extends('template.index')
@section('content')
    {{-- @dd($data); --}}

    <style>
        .table-wrapper {
            position: relative;
            overflow: auto;
            max-height: 300px;
            /* Sesuaikan dengan kebutuhan tinggi tabel */
        }

        .table-wrapper thead th {
            position: sticky;
            top: 0;
            background-color: #ffffff;
            /* Warna latar belakang header */
        }
    </style>
    <div class="container">
        @if (session()->has('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        <div class="card shadow mb-4">
            <div class="card-header py-3 mt-2">
                <h6 class="m-0 font-weight-bold text-primary">Purchase Order</h6>
            </div>

            {{-- <div class="container">
                <div class="row">
                    <div class="col-md-4 mt-3">
                        <form action={{ url('purchase') }} method="GET" id="serch-form">
                            <select class="form-control form-select" aria-label="Default select example" name='serch'
                                id="serch">
                                <option value=""><a href="{{url('purchase')}}">All</a></option>
                                @foreach ($deta as $d)
                                    <option value={{ $d->no_pembelian }}>{{ $d->no_pembelian }}</option>
                                @endforeach
                            </select>
                            <button type=submit name=submit class="btn btn-primary mt-3" id="serch-button">submit</button>
                        </form>
                    </div>
                </div>
            </div> --}}
            <div class="card-body">

                <div class="table-responsive text-center">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>

                            <tr>
                                <td>No</td>
                                <td>Purchase Date</td>
                                <td>No Sales</td>
                                <td>No Purchase</td>
                                <td>Transaction number</td>
                                <td>Customer</td>
                                <td>Supplier</td>
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
                                    <td style="min-width:120px">{{ $d->tgl_pembelian }}</td>
                                    <td>{{ $d->no_penjualan }}</td>
                                    <td>{{ $d->no_pembelian }}</td>
                                    <td>{{ $d->nomor_transaksi }}</td>
                                    <td>{{ $d->nama_pelanggan }}</td>
                                    <td>{{ $d->nama_pemasok }}</td>
                                    <td>{{ $d->nama_pengguna }}</td>
                                    <td>


                                        <a class="btn btn-primary"
                                            href="{{ url('goods', str_replace('/', '-', $d->no_pembelian)) }}"
                                            @if ($d->jumlah_detail_penerimaan) {{ 'hidden' }} @endif>
                                            Good Recipt
                                        </a>
                                        <a href="{{ url('purchase/detail', str_replace('/', '-', $d->no_pembelian)) }}"
                                            class="btn btn-info mt-1">
                                            Detail </a>

                                    </td>
                                    <td>
                                        <a href="{{ url('purchase/print', str_replace('/', '-', $d->no_pembelian)) }}"
                                            class="btn btn-primary mt-1">
                                            Print </a>
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
            let table = new DataTable('#dataTable', {
                "scrollY": "300px",
                "scrollX": "300px",

            });

            // let table = new DataTable('#dataTable', {
            //     "scrollY": "300px",
            //     "scrollCollapse": true,
            //     "paging": true,
            //     "stickyHeader": true // Enable stickyHeader
            // });
        })
    </script>
@endsection()

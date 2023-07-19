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
                <h6 class="m-0 font-weight-bold text-primary">{{ $tittle }} </h6>
            </div>

            {{-- <div class="row ml-3">
                <div class="col-md-4 mt-3">
                    <form action={{ url('paymentvendor/report/report') }} method="GET" id="serch-form">
                        <select class="form-control form-select" aria-label="Default select example" name='serch'
                            id="serch">
                            @foreach ($deta as $d)
                                <option value="{{ $d->no_pembelian }}">{{ $d->no_pembelian }}</option>
                            @endforeach
                        </select>
                        <button type=submit name=submit class="btn btn-primary mt-3" id="serch-button">submit</button>
                    </form>
                </div>
            </div> --}}
            <div class="card-body">
                <div class="table-responsive text-center">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>

                            <tr>
                                <th>No</th>
                                <th>Supplier Name</th>
                                <th>Purchase Number</th>
                                <th>Purchase Date</th>
                                <th>Payment Number</th>
                                <th>Paymeny Date</th>
                                <th>Total Amount </th>

                            </tr>
                        </thead>
                        <tbody>

                            <?php $total = 0; ?>
                            @foreach ($data as $d)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $d->nama_pemasok }}</td>
                                    <td>{{ $d->no_pembelian }}</td>
                                    <td>{{ $d->tgl_pembelian }}</td>
                                    <td>{{ $d->no_pembayaran_vendor }}</td>
                                    <td>{{ $d->tgl_pembayaran_vendor }}</td>
                                    <td>{{ 'Rp.' . number_format($d->subtotal_detail_pembelian, 2, ',', '.') }}</td>
                                    <?php $total += $d->subtotal_detail_pembelian; ?>
                                </tr>
                            @endforeach

                        </tbody>
                        <tbody>
                            <tr>
                                <td colspan="6">Total</td>
                                <td>{{ 'Rp.' . number_format($total, 2, ',', '.') }}</td>
                            </tr>
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

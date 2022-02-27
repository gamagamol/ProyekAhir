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
                <h6 class="m-0 font-weight-bold text-primary">Purchase Detail Report </h6>
            </div>

            <div class="row ml-3">
                <div class="col-md-4 mt-3">
                    <form action={{ url('paymentvendor') }} method="GET" id="serch-form">
                        <select class="form-control form-select" aria-label="Default select example" name='serch' id="serch">
                            @foreach ($deta as $d)
                                <option value="{{ $d->no_pembelian }}">{{ $d->no_pembelian }}</option>
                            @endforeach
                        </select>
                        <button type=submit name=submit class="btn btn-primary mt-3 ml-3" id="serch-button">submit</button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive text-center">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

                        <tr>
                            <td>No</td>
                            <td>Supplier Name</td>
                            <td>Purchase Number</td>
                            <td>Purchase Date</td>
                            <td>Due Date</td>
                            <td>Total Amount </td>

                        </tr>
                        <?php $total=0; ?>

                        @foreach ($data as $d)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $d->nama_pemasok }}</td>
                                <td>{{ $d->no_pembelian }}</td>
                                <td>{{ $d->tgl_pembelian }}</td>
                                <td>{{ $d->DUE_DATE }}</td>
                                <td>{{ 'Rp.' . number_format($d->subtotal, 2, ',', '.') }}</td>
                              
                            </tr>
                                <?php $total=$total+$d->subtotal ?>

                        @endforeach
                       <tr>
                                <td colspan="5">{{"TOTAL"}}</td>
                                <td>{{'Rp.' . number_format($total, 2, ',', '.')}}</td>
                            </tr>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection()

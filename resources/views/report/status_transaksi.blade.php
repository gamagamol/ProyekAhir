@extends('template.index')
@section('content')
{{-- @dd($data) --}}
    <div class="container">
        @if (session()->has('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        <div class="card shadow mb-4">
            <div class="card-header py-3 mt-2">
                <h6 class="m-0 font-weight-bold text-primary">Transaction Status </h6>
            </div>

            <div class="row ml-3">
                <div class="col-md-4 mt-3">
                    <form action={{ url('status_transaksi') }} method="GET" id="serch-form">
                        <select class="form-control form-select" aria-label="Default select example" name='serch' id="serch">
                            @foreach ($no_penjualan as $d)
                                <option value="{{ $d->no_penjualan }}">{{ $d->no_penjualan }}</option>
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
                            <td>Date</td>
                            <td>Sales Number</td>
                            <td>Quotation</td>
                            <td>Sales</td>
                            <td>Purchase </td>
                            <td>Goods Receipt</td>
                            <td>Delivery</td>
                            <td>Bill Payment</td>
                            <td>Payment </td>
                            <td>Custumor </td>
                            <td>Prepared </td>
                        </tr>
                        @foreach ($data as $d )
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td >{{$d->tgl_penjualan}}</td>
                            <td>{{$d->no_penjualan}}</td>

                            <td>
                         @if ($d->no_penawaran)
                         <i class="fa fa-check text-success" aria-hidden="true"></i>
                             @else
                             
                             <i class="fa fa-ban text-danger" aria-hidden="true"></i>
                         @endif
                            </td>
                            <td>
                         @if ($d->no_penjualan)
                         <i class="fa fa-check text-success" aria-hidden="true"></i>
                             @else
                             
                             <i class="fa fa-ban text-danger" aria-hidden="true"></i>
                         @endif
                            </td>
                            <td>
                         @if ($d->no_pembelian)
                         <i class="fa fa-check text-success" aria-hidden="true"></i>
                             @else
                             
                             <i class="fa fa-ban text-danger" aria-hidden="true"></i>
                         @endif
                            </td>
                            <td>
                         @if ($d->no_penerimaan)
                         <i class="fa fa-check text-success" aria-hidden="true"></i>
                             @else
                             
                             <i class="fa fa-ban text-danger" aria-hidden="true"></i>
                         @endif
                            </td>
                            <td>
                         @if ($d->no_pengiriman)
                         <i class="fa fa-check text-success" aria-hidden="true"></i>
                             @else
                             
                             <i class="fa fa-ban text-danger" aria-hidden="true"></i>
                         @endif
                            </td>
                            <td>
                         @if ($d->no_tagihan)
                         <i class="fa fa-check text-success" aria-hidden="true"></i>
                             @else
                             
                             <i class="fa fa-ban text-danger" aria-hidden="true"></i>
                         @endif
                            </td>
                            <td>
                         @if ($d->no_pembayaran)
                         <i class="fa fa-check text-success" aria-hidden="true"></i>
                             @else
                             
                             <i class="fa fa-ban text-danger" aria-hidden="true"></i>
                         @endif
                            </td>
                            <td>
                                {{$d->nama_pelanggan}}
                            </td>
                            <td>
                                {{$d->nama_pengguna}}
                            </td>

                        </tr>
                        @endforeach
                       
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection()

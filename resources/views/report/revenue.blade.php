@extends('template.index')
@section('content')
    <div class="container">
        <div class="card shadow mb-4">
            <div class="card-header py-3 mt-2">
                <h6 class="m-0 font-weight-bold text-primary">Sales Detail Report </h6>
            </div>

             <form action={{ url('SDR') }} method="GET" id="serch-form">
                <div class="form-group col-md-6 ml-2 mt-2">
                    <select class="custom-select" name="search">
                      
                        <option value="All">All</option>
                       
                        @foreach ($custumor as $c)
                            <option value={{$c->id_pelanggan}}>{{$c->nama_pelanggan}}</option>
                        @endforeach

                    </select>
                </div>
                <button type=submit name=submit class="btn btn-primary ml-4">submit</button>
            </form>
            <div class="card-body">
                <div class="table-responsive text-center">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

                        <tr>
                            <td>No</td>
                            <td>Custumor Name</td>
                            <td>Sales Number</td>
                            <td>Invoice Number</td>
                            <td>Invoice Date</td>
                            <td>Amount</td>
                            <td>Total Amount </td>

                        </tr>
                        <?php $no = 1; ?>
                        <?php $total = 0; ?>

                        @foreach ($data as $d)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ $d->nama_pelanggan }} </td>
                                <td>{{ $d->no_penjualan }}</td>
                                <td>{{ $d->no_tagihan }}</td>
                                <td>{{ $d->tgl_tagihan }}</td>
                                <td>{{ 'Rp.' . number_format($d->subtotal, 2, ',', '.') }}</td>
                                <td>{{ 'Rp.' . number_format($d->subtotal, 2, ',', '.') }}</td>

                            </tr>
                            <?php $total = $d->subtotal + $total; ?>
                        @endforeach
                        <tr>
                            <td colspan="6">{{ 'TOTAL' }}</td>
                            <td>{{ 'Rp.' . number_format($total, 2, ',', '.') }}</td>
                        </tr>

                        </tr>
                        <?php $no++; ?>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection()

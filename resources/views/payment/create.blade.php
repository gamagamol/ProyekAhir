@extends('template.index')
@section('content')
    <div class="container">
        @if (session()->has('failed'))
            <div class="alert alert-danger" role="alert">
                {{ session('failed') }}
            </div>
        @endif
        <div class="card shadow mb-4">
            <div class="card-header py-3 mt-2">
                <h6 class="m-0 font-weight-bold text-primary">Create Payment</h6>
            </div>
            <form action={{ url('payment') }} method="post">

                <div class="container">
                    <div class="row">
                        <div class="col-md-3 mt-2">
                            <input type="date" required name="tgl_pembayaran" class="form-control" value={{$data[0]->tgl_tagihan}}>

                        </div>
                       
                    </div>
                </div>
                <div class="card-body">

                    <div class="table-responsive text-center">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

                            <tr>
                                <td>No</td>
                                <td>Date</td>
                                <td>No Bill Payment</td>
                                <td>Job number</td>
                                <td>Grade</td>
                                <td colspan="3">Material Size</td>
                                <td>QTY</td>

                                <td>Weight(Kg)</td>
                                <td>Unit Price</td>
                                <td>Shipment</td>
                                <td>Amount</td>
                                <td>VAT 11%</td>
                                <td>Total Amount</td>
                                <td>Processing</td>
                                <td>customer</td>



                            </tr>
                            @csrf
                            <?php $i = 1; ?>
                            @foreach ($data as $d)


                                <tr>
                                    <td hidden> <input type="text"  value={{ $d->id_transaksi }} name="id_transaksi[]"></td>
                                    <td hidden> <input type="text"  value={{ $d->no_tagihan }} name="no_tagihan[]"></td>
                                    <td hidden> <input type="text"  value={{ $d->id_produk }} name="id_produk[]"></td>
                                    <td>
                                        {{$loop->iteration}}
                                    </td>
                                    <td style="min-width:120px">{{ $d->tgl_tagihan }}</td>
                                    <td>{{ $d->no_tagihan }}</td>
                                    <td>{{ $d->nomor_pekerjaan }}</td>
                                    <td>{{ $d->nama_produk }}</td>
                                    <td>{{ $d->tebal_transaksi }}</td>
                                    <td>{{ $d->lebar_transaksi }}</td>
                                    <td>{{ $d->panjang_transaksi }}</td>
                                    <td>{{ $d->jumlah }}</td>
                                    <td>{{ $d->berat }}</td>
                                    <td>{{ 'Rp.' . number_format($d->harga) }}</td>
                                    <td>{{ 'Rp.' . number_format($d->ongkir) }}</td>
                                    <td>{{ 'Rp.' . number_format($d->subtotal) }}</td>
                                    <td>{{ 'Rp.' . number_format($d->ppn) }}</td>
                                    <td>{{ 'Rp.' . number_format($d->total) }}</td>
                                    <td>{{ $d->layanan }}</td>
                                    <td>{{ $d->nama_pelanggan }}</td>



                                </tr>







                            @endforeach
                        </table>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col">
                                <button type=submit name=submit class="btn btn-primary">submit</button>
                                  <a href={{url("bill")}} class="btn btn-primary">Back</a>
                            </div>
                        </div>
                    </div>
            </form>
        </div>
    </div>
    </div>

@endsection()

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
                <h6 class="m-0 font-weight-bold text-primary">Bill payment</h6>
            </div>
            <form action={{ url('bill') }} method="post">
                @csrf

                <div class="container">
                    <div class="row">
                        <div class="col-md-3 mt-2">
                            <input type="date" required name="tgl_pembayaran" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mt-2">
                            <select name="no_pembelian" id="no_pembelian" class="form-control" required>
                                <option value=""> Select Purchase Number</option>
                                @foreach ($no_pembelian as $np)
                                    <option value="{{ str_replace('/', '-', $np->no_pembelian) }}">{{ $np->no_pembelian }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <div class="table-responsive text-center">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <td>No</td>
                                    <td>Date Delivery</td>
                                    <td>No Delivery</td>
                                    <td>Job number</td>
                                    <td>Grade</td>
                                    <td colspan="3">Material Size</td>
                                    <td>QTY</td>
                                    <td>Grade</td>
                                    <td colspan="3">Material Size</td>
                                    <td>QTY</td>
                                    <td>Weight(Kg)</td>
                                    <td>Unit Price</td>
                                    <td>Shipment</td>
                                    <td>Amount</td>
                                    <td>VAT 10%</td>
                                    <td>Total Amount</td>
                                    <td>Processing</td>
                                    <td>customer</td>
                                    <td>Prepared</td>



                                </tr>
                            </thead>

                            <?php $i = 0; ?>
                            <?php
                            $selesai = false;
                            $sudah_terkirim = 0;
                            $total_unit = 0;
                            ?>
                            {{-- @foreach ($data as $d)
                                <?php
                                $sudah_terkirim += $d->sudah_terkirim;
                                $total_unit += $d->jumlah_detail_penjualan;
                                ?>
                                <tr>
                                    <td hidden> <input type="text" value={{ $d->id_transaksi }} name="id_transaksi[]">
                                    </td>
                                    <td hidden> <input type="text" value={{ $d->id_produk }} name="id_produk[]"></td>
                                    <td hidden> <input type="text" value={{ $d->id_pengiriman }} name="id_pengiriman[]">
                                    <td hidden> <input type="text" value={{ $d->no_pengiriman }} name="no_pengiriman[]">

                                    </td>
                                    <td> {{ $loop->iteration }}</td>
                                    <td style="min-width:120px">{{ $d->tgl_pengiriman }}</td>
                                    <td>{{ $d->no_pengiriman }}</td>
                                    <td>{{ $d->nomor_pekerjaan }}</td>
                                    <td>{{ $d->nama_produk }}</td>
                                    <td>{{ $d->tebal_transaksi }}</td>
                                    <td>{{ $d->lebar_transaksi }}</td>
                                    <td>{{ $d->panjang_transaksi }}</td>
                                    <td>{{ $d->jumlah }}</td>
                                    <td>{{ $d->nama_produk }}</td>
                                    <td>{{ $d->tebal_penawaran }}</td>
                                    <td>{{ $d->lebar_penawaran }}</td>
                                    <td>{{ $d->panjang_penawaran }}</td>
                                    <td>{{ $d->jumlah }}</td>
                                    <td>{{ $d->berat }}</td>
                                    <td>{{ 'Rp.' . number_format($d->harga) }}</td>
                                    <td>{{ 'Rp.' . number_format($d->ongkir) }}</td>
                                    <td>{{ 'Rp.' . number_format($d->subtotal) }}</td>
                                    <td>{{ 'Rp.' . number_format($d->ppn) }}</td>
                                    <td>{{ 'Rp.' . number_format($d->total) }}</td>
                                    <td>{{ $d->layanan }}</td>
                                    <td>{{ $d->nama_pelanggan }}</td>
                                    <td>{{ $d->nama_pengguna }}</td>

                                </tr>

                                <?php $i++; ?>
                            @endforeach --}}



                        </table>
                    </div>

                    <div class="container">
                        <div class="row">
                            <div class="col">
                                <button type=submit name=submit class="btn btn-primary">submit</button>
                                <a href={{ url()->previous() }} class="btn btn-primary"> Back</a>
                            </div>
                        </div>
                    </div>
            </form>
        </div>
    </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#no_pembelian').change(function() {
                $.ajax({
                    url: `{{ url('bill/getPurchaseDetail') }}/${$('#no_pembelian').val()}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {


                    }
                })
            })
        })
    </script>
@endsection()

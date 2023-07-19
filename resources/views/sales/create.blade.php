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
                <h6 class="m-0 font-weight-bold text-primary">Create Sales Order</h6>
            </div>
            <form action={{ url('sales') }} method="post">

                <div class="container">
                    <div class="row">
                        <div class="col-md-3 mt-2">
                            <label for="">Sales Date</label>
                            <input type="date" name="tgl_penjualan" class="form-control"
                                value={{ $data[0]->tgl_penawaran }}>
                            <input type="text" value="{{ $data[0]->kode_transaksi }}" name="kode_transaksi" hidden>

                        </div>


                    </div>
                    <div class="row">
                        <div class="col-md-3 mt-3">
                            <label for="">No Po Customer</label>
                            <input type="text" class="form-control" name="no_po_customer" required>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-end ">
                        Check All
                        <input type="checkbox" id="check_all" class="mx-2">

                    </div>
                    <div class="table-responsive text-center">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

                            <tr>
                                <td>No</td>
                                <td>Date</td>
                                <td>No Quotation</td>
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
                            <?php
                            
                            $i = 1;
                            $subtotal = 0;
                            $ppn = 0;
                            $total = 0;
                            ?>
                            @foreach ($data as $d)
                                <tr>

                                    <td><input type="checkbox" name="id_transaksi[]" id='id_transaksi'
                                            value="{{ $d->id_transaksi }}"></td>
                                    <td style="min-width:120px">{{ $d->tgl_penawaran }}</td>
                                    <td>{{ $d->no_penawaran }}</td>
                                    <td>{{ $d->nomor_pekerjaan }}</td>
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



                                </tr>
                                <?php
                                $subtotal += $d->subtotal;
                                $ppn += $d->ppn;
                                $total += $d->total;
                                
                                ?>
                            @endforeach

                            <tr>
                                <td colspan='12'>TOTAL</td>
                                <td>{{ 'Rp.' . number_format($subtotal) }}</td>
                                <td>{{ 'Rp.' . number_format($ppn) }}</td>
                                <td>{{ 'Rp.' . number_format($total + $data[0]->ongkir) }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col">


                                <p href="" class="btn btn-primary mt-3" onclick="MoveCreate()">submit</p>

                                <a href="{{ url('quotation') }}" class="btn btn-primary">back</a>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
    {{-- Modal Move Create --}}

    <div id="modal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sales</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure to continue the transaction? please check the details first! </p>
                    <button type=submit name=submit class="btn btn-primary ">submit</button>
                    <a href="{{ url('quotation', $d->kode_transaksi) }}" class="btn btn-info mt-1">
                        Detail </a>

                </div>
            </div>
        </div>
    </div>
    </form>

    <script>
        function MoveCreate() {
            $('#modal').modal('show');
        }

        $('#check_all').click(function() {
            if ($(this).is(':checked')) {
                $('input:checkbox').not(this).attr('checked', 'checked');
            } else {
                $('input:checkbox').removeAttr('checked');
            }
        })
    </script>
@endsection()

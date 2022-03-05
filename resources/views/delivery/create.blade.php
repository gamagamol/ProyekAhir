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
                <h6 class="m-0 font-weight-bold text-primary">Delivery Order</h6>
            </div>
            <form action={{ url('delivery') }} method="post">

                <div class="container">
                    <div class="row">
                        <div class="col-md-3 mt-2">
                            <input type="date" name="tgl_pengiriman" class="form-control"
                                value={{ $data[0]->tgl_penerimaan }}>

                        </div>
                    </div>

                </div>
                <div class="card-body">
                    <div class="container" style="margin-left:87% ">
                        <div class="row mt-2 ">
                            <div class="col align-self-end">
                                <div class="form-check align-self-end">
                                    <input class="form-check-input" type="checkbox" value="" id="select_all">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Select All
                                    </label>
                                </div>
                            </div>
                        </div>
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
                                <td>VAT 10%</td>
                                <td>Total Amount</td>
                                <td>Processing</td>
                                <td>customer</td>



                            </tr>
                            @csrf
                            <?php $i = 1; ?>
                            @foreach ($data as $d)
                                <tr>
                                    <td> <input type="checkbox" value={{ $d->id_transaksi }} name="id_transaksi[]"
                                            id="select{{ $loop->iteration }}"></td>
                                    <td style="min-width:120px">{{ $d->tgl_penerimaan }}</td>
                                    <td>{{ $d->no_penerimaan }}</td>
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
                            <input type="text" value="{{ count($data) }}" hidden id="lenght_data">
                        </table>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col">
                                <button type=submit name=submit class="btn btn-primary">submit</button>
                                <a href={{ url('delivery') }} class="btn btn-primary">Back</a>
                            </div>
                        </div>
                    </div>
            </form>
        </div>
    </div>
    </div>


    <script>
        $(document).ready(function() {
            //    cheked
            $('#select_all').click(function(event) {
                let select = $('#lenght_data').val();
                for (let i = 1; i <= select; i++) {
                   if ($('#select' + i).is(":checked")) {
                    $('#select' + i).prop('checked', false);
                       
                   }else{

                       $('#select' + i).prop('checked', true);
                   }
                   
                }


            });

            // end cheked
           
        });
    </script>
@endsection()

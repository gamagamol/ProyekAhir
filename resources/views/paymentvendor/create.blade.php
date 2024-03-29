@extends('template.index')
@section('content')
    <div class="container">
        @if (session()->has('failed'))
            <div class="alert alert-danger" role="alert">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

                {{ session('failed') }}
            </div>
        @endif
        <div class="card shadow mb-4">
            <div class="card-header py-3 mt-2">
                <h6 class="m-0 font-weight-bold text-primary"> {{ $tittle }} </h6>
            </div>
            <form action={{ url('paymentvendor') }} method="post">

                <div class="container ">
                    <div class="row ml-2">
                        <div class="col-md-3 mt-2">
                            <input type="date" name="tgl_pembelian" class="form-control"
                                value={{ $data[0]->tgl_pembelian }}>
                            <input type="text" value="{{ $data[0]->no_pembelian }}" name="kode_transaksi" hidden>

                        </div>


                    </div>



                    <div class="card-body">

                        <div class="table-responsive text-center">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

                                <tr>
                                    <td>No</td>
                                    <td>Date</td>
                                    <td>No Sales</td>
                                    <td>Job number</td>
                                    <td>Grade</td>
                                    <td colspan="3">Material Size</td>
                                    <td>QTY</td>
                                    <td>Weight(Kg)</td>
                                    <td>Unit Price</td>
                                    <td>Total Amount</td>

                                </tr>
                                @csrf
                                <?php
                                $i = 1;
                                $total = 0;
                                $total_ongkir = 0;
                                $total_ppn = 0;
                                
                                ?>
                                @foreach ($data as $d)
                                    <tr>

                                        <td>{{ $loop->iteration }}</td>
                                        <td style="min-width:120px">{{ $d->tgl_pembelian }}</td>
                                        <td>{{ $d->no_pembelian }}</td>
                                        <td>{{ $d->nomor_pekerjaan }}</td>
                                        <td>{{ $d->nama_produk }}</td>
                                        <td>{{ ($d->tebal_detail_pembelian)?$d->tebal_detail_pembelian : $d->tebal_transaksi }}</td>
                                        <td>{{ ($d->lebar_detail_pembelian)?$d->lebar_detail_pembelian : $d->lebar_transaksi }}</td>
                                        <td>{{ ($d->panjang_detail_pembelian)?$d->panjang_detail_pembelian : $d->panjang_transaksi }}</td>
                                        <td>{{ ($d->jumlah_detail_pembelian)?$d->jumlah_detail_pembelian : $d->jumlah }}</td>
                                        <td>{{ ($d->berat_detail_pembelian)?$d->berat_detail_pembelian : $d->berat }}</td>
                                        @php
                                            $harga=($d->harga_detail_pembelian)?$d->harga_detail_pembelian:$d->harga;
                                            $subtotal=($d->subtotal_detail_pembelian)?$d->subtotal_detail_pembelian:$d->subtotal;
                                        @endphp
                                        <td>{{ 'Rp.' . number_format($harga) }}</td>
                                        <td>{{ 'Rp.' . number_format($subtotal) }}</td>
                                       
                                       
                                        <?php $total += $d->total_detail_pembelian; ?>
                                       


                                    </tr>
                                @endforeach
                                {{-- <?php $total = $total - $total_ongkir - $total_ppn; ?> --}}
                                <tr>
                                    <td colspan="11">TOTAL</td>
                                    <td>{{ 'Rp.' . number_format($total) }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="container mt-2">
                            <div class="row">
                                <div class="col">
                                    <button type=submit name=submit class="btn btn-primary" id="submit">submit</button>
                                    <a href="{{ url('paymentvendor') }}" class="btn btn-primary">back</a>
                                </div>
                            </div>
                        </div>
            </form>
        </div>
    </div>
    </div>
    </div>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script>
        $('#installment_payment').mask('000.000.000.000.000', {
            reverse: true
        });
        $(document).ready(function() {

            $('#installment').click(function() {
                $('#row_installment').removeAttr('hidden');
                $('#submit').attr('hidden', true);

                // check cicilan

                let total_debt = <?= $total ?>;
                $('#check_debt').click(function() {
                    let installment = $('#installment_payment').val();
                    installment = parseInt(installment.replace(/[^\w\s]/gi, ''));

                    if (installment > total_debt) {
                        swal("Alert", "Your installment payment is wrong!", "error");
                    } else {
                        swal("Alert", "Your installment payment is right!", "success");

                        $('#submit').removeAttr('hidden');

                    }


                })


            })




        });
    </script>
@endsection()

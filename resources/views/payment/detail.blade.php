@extends('template.index')
@section('content')
    <style>
        .tab-content {
            border-left: 1px solid #ddd;
            padding-left: 15px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 15px;
        }
    </style>

    @if (session()->has('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif


    <div class="container  ">
        <div class="card shadow mb-4 ml-4 mr-4">
            <div class="card-header py-3 mb-2 ">
                <h6 class="m-0 font-weight-bold text-primary">Detail Payment</h6>
            </div>


            <div class="container mt-4">
                <ul class="nav nav-tabs" id="myTabs">
                    <li class="nav-item">
                        <a class="nav-link active" id="tab1-tab" data-toggle="tab" href="#tab1">Goods</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab2-tab" data-toggle="tab" href="#tab2">Service</a>
                    </li>

                </ul>

                <div class="tab-content mt-2">
                    <div class="tab-pane fade show active" id="tab1">
                        <div id="table_atas">
                            <div class="table-responsive text-center mt-2">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <tr>
                                        <td>Date</td>
                                        <td>Job number</td>
                                        <td>Grade</td>
                                        <td colspan="3">Material Size</td>
                                        <td>Grade</td>
                                        <td>QTY</td>

                                        <td>Weight(Kg)</td>
                                        <td>Unit Price</td>
                                        <td>Shipment</td>
                                        <td>Amount</td>
                                        <td>VAT 11%</td>
                                        <td>Total Amount</td>
                                        <td>Processing</td>
                                        <td>Custumor</td>

                                    </tr>
                                    <?php $total = 0; ?>
                                    <?php $jumlah = 0; ?>
                                    <?php $berat = 0; ?>
                                    <?php $subtotal = 0; ?>
                                    <?php $ppn = 0; ?>
                                    @foreach ($data as $p)
                                        @if ($p->type == 1)
                                            <tr>

                                                <td style="min-width:120px">
                                                    {{ $p->tgl_pembayaran }}
                                                </td>
                                                <td>
                                                    {{ $p->nomor_pekerjaan }}
                                                </td>
                                                <td>
                                                    {{ $p->nama_produk }}
                                                </td>
                                                <td>
                                                    {{ $p->tebal_penawaran }}
                                                </td>
                                                <td>
                                                    {{ $p->lebar_penawaran }}
                                                </td>

                                                <td>
                                                    {{ $p->panjang_penawaran }}
                                                </td>

                                                <td>
                                                    {{ $p->nama_produk }}
                                                </td>
                                                <td>
                                                    {{ $p->jumlah }}
                                                </td>


                                                <td>
                                                    {{ $p->berat }}
                                                </td>
                                                <td>
                                                    {{ 'Rp' . number_format($p->harga) }}
                                                </td>
                                                <td>
                                                    {{ 'Rp' . number_format($p->ongkir) }}
                                                </td>
                                                <td>
                                                    {{ 'Rp' . number_format($p->subtotal) }}
                                                </td>
                                                <td>
                                                    {{ 'Rp' . number_format($p->ppn) }}

                                                </td>
                                                <td>
                                                    {{ 'Rp' . number_format($p->total) }}
                                                </td>
                                                <td>
                                                    {{ $p->layanan }}
                                                </td>
                                                <td>
                                                    {{ $p->nama_pelanggan }}
                                                </td>



                                            </tr>

                                            <?php $berat += $p->berat; ?>
                                            <?php $jumlah += $p->jumlah; ?>
                                            <?php $ppn += $p->ppn; ?>
                                            <?php $subtotal += $p->subtotal; ?>
                                            <?php $total += $p->total; ?>
                                        @endif
                                    @endforeach
                                    <tr>
                                        <td colspan="7">TOTAL</td>

                                        <td> {{ $jumlah }} </td>
                                        <td> {{ $berat }} </td>
                                        <td> </td>
                                        <td> </td>
                                        <td> {{ 'Rp' . number_format($subtotal) }} </td>
                                        <td> {{ 'Rp' . number_format($ppn) }} </td>
                                        <td> {{ 'Rp' . number_format($total + $data[0]->ongkir) }} </td>
                                    </tr>
                                </table>
                            </div>

                        </div>
                    </div>
                    <div class="tab-pane fade" id="tab2">
                        <div class="table-responsive text-center mt-2">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <tr>
                                    <td>Date</td>
                                    <td>Job number</td>
                                    <td>Grade</td>
                                    <td colspan="3">Material Size</td>
                                    <td>Grade</td>
                                    <td>QTY</td>
                                    <td>Weight(Kg)</td>
                                    <td>Unit Price</td>
                                    <td>Shipment</td>
                                    <td>Amount</td>
                                    <td>VAT 11%</td>
                                    <td>VAT 2%</td>
                                    <td>Total Amount</td>
                                    <td>Processing</td>
                                    <td>Custumor</td>

                                </tr>
                                <?php $total = 0; ?>
                                <?php $jumlah = 0; ?>
                                <?php $berat = 0; ?>
                                <?php $subtotal = 0; ?>
                                <?php $ppn = 0; ?>
                                <?php $ppn12 = 0; ?>
                                @foreach ($data as $p)
                                    @if ($p->type == 2)
                                        <tr>

                                            <td style="min-width:120px">
                                                {{ $p->tgl_pembayaran }}
                                            </td>
                                            <td>
                                                {{ $p->nomor_pekerjaan }}
                                            </td>
                                            <td>
                                                {{ $p->nama_produk }}
                                            </td>
                                            <td>
                                                {{ $p->tebal_penawaran }}
                                            </td>
                                            <td>
                                                {{ $p->lebar_penawaran }}
                                            </td>

                                            <td>
                                                {{ $p->panjang_penawaran }}
                                            </td>

                                            <td>
                                                {{ $p->nama_produk }}
                                            </td>
                                            <td>
                                                {{ $p->jumlah }}
                                            </td>


                                            <td>
                                                {{ $p->berat }}
                                            </td>
                                            <td>
                                                {{ 'Rp' . number_format($p->harga) }}
                                            </td>
                                            <td>
                                                {{ 'Rp' . number_format($p->ongkir) }}
                                            </td>
                                            <td>
                                                {{ 'Rp' . number_format($p->subtotal) }}
                                            </td>
                                            <td>
                                                {{ 'Rp' . number_format($p->ppn) }}
                                            </td>
                                            <td>
                                                {{ 'Rp' . number_format($p->harga * 0.02) }}
                                            </td>
                                            <td>
                                                {{ 'Rp' . number_format($p->total) }}
                                            </td>
                                            <td>
                                                {{ $p->layanan }}
                                            </td>
                                            <td>
                                                {{ $p->nama_pelanggan }}
                                            </td>



                                        </tr>

                                        <?php $berat += $p->berat; ?>
                                        <?php $jumlah += $p->jumlah; ?>
                                        <?php $ppn += $p->ppn; ?>
                                        <?php $ppn12 += $p->harga * 0.02; ?>
                                        <?php $subtotal += $p->subtotal; ?>
                                        <?php $total += $p->total; ?>
                                    @endif
                                @endforeach
                                <tr>
                                    <td colspan="7">TOTAL</td>

                                    <td> {{ $jumlah }} </td>
                                    <td> {{ $berat }} </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> {{ 'Rp' . number_format($subtotal) }} </td>
                                    <td> {{ 'Rp' . number_format($ppn) }} </td>
                                    <td> {{ 'Rp' . number_format($subtotal * 0.02) }} </td>
                                    <td> {{ 'Rp' . number_format($total + $data[0]->ongkir) }} </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                </div>
                <a href="{{ url('payment') }}" class="btn btn-primary  mb-4 ml-3" style="margin-top: 30px">Back</a>
            </div>



        </div>
    </div>

    <script>
        $('#harga').mask('000.000.000.000.000', {
            reverse: true
        });
        $('#ongkir').mask('000.000.000.000.000', {
            reverse: true
        });


        function selesai() {
            var table_atas = document.getElementById("table_atas");
            table_atas.setAttribute("hidden", true);
            var submit = document.getElementById("submit");
            submit.removeAttribute("hidden");

        }

        function drop() {
            // bikin form nya
            console.log("hello");
            let id_produk = document.getElementById('id_produk').value.split('|');

            let bentuk_produk = id_produk[1];

            let lebar = document.getElementById('lebar_transaksi');
            if (bentuk_produk == "CYLINDER") {

                lebar.setAttribute('value', 0);
                lebar.setAttribute('readonly', true);
            } else {
                lebar.removeAttribute('readonly');
            }



        }
    </script>
@endsection

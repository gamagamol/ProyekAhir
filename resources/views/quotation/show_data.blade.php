@extends('template.index')
@section('content')
    @if (session()->has('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif
    <div class="container  ">
        <div class="card shadow mb-4 ml-4 mr-4">
            <div class="card-header py-3 mb-2 ">
                <h6 class="m-0 font-weight-bold text-primary">Show Data Quotation</h6>
            </div>

            <div class="container mt-2">
                <div id="table_atas">
                    <div class="table-responsive text-center mt-2">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

                            <tr>
                                <td>Date</td>
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
                                <td>VAT 11%</td>
                                <td>Total Amount</td>
                                <td>Processing</td>
                                <td>Custumor</td>

                            </tr>
                            <?php
                            $subtotal = 0;
                            $total = 0;
                            $ongkir = 0;
                            $ppn = 0;
                            ?>
                            @foreach ($data as $p)
                                <tr>

                                    <td style="min-width:120px">
                                        {{ $p->tgl_penawaran }}
                                    </td>
                                    <td>
                                        {{ $p->nomor_pekerjaan }}
                                    </td>
                                    <td>
                                        {{ $p->nama_produk }}
                                    </td>
                                    <td>
                                        {{ $p->tebal_transaksi }}
                                    </td>
                                    <td>
                                        {{ $p->lebar_transaksi }}
                                    </td>

                                    <td>
                                        {{ $p->panjang_transaksi }}
                                    </td>
                                    <td>
                                        {{ $p->jumlah }}
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
                                <?php
                                $subtotal += $p->subtotal;
                                $ongkir += $p->ongkir;
                                $ppn += $p->ppn;
                                $total += $p->total;
                                
                                ?>
                            @endforeach

                            <tr>
                                <td colspan='15'>TOTAL</td>
                                <td>{{'Rp.'.number_format($subtotal)}}</td>
                                <td>{{'Rp.'.number_format($ppn)}}</td>
                                <td>{{'Rp.'.number_format($total)}}</td>
                            </tr>

                        </table>
                    </div>

                    <a href="{{ url()->previous() }}" class="btn btn-primary  mb-4 ml-3" style="margin-top: 30px">Back</a>
                    <a href="{{ url('quotation') }}" class="btn btn-primary  mb-4 ml-3" style="margin-top: 30px">Next</a>





                </div>
            </div>

        </div>
    </div>









    </form>
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

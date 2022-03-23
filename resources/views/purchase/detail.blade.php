@extends('template.index')
@section('content')
    @dump($data)
    @if (session()->has('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif
    <div class="container  ">
        <div class="card shadow mb-4 ml-4 mr-4">
            <div class="card-header py-3 mb-2 ">
                <h6 class="m-0 font-weight-bold text-primary">Detail Purchase Order</h6>
            </div>

            <div class="container mt-2">
                <div id="table_atas">





                    <div class="table-responsive text-center mt-2">


                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

                            <tr>
                                <td>Date</td>
                                <td>No Transaction</td>
                                <td>Job number</td>
                                <td>Grade</td>
                                <td colspan="3">Material Size</td>
                                <td>QTY</td>
                                <td>Grade</td>
                                <td colspan="3">Material Size</td>
                                <td>QTY</td>
                                <td>Weight(Kg)</td>
                                <td>Unit Price</td>
                                <td>Amount</td>
                                <td>VAT 10%</td>
                                <td>Total Amount</td>
                                <td>Processing</td>
                                <td>Custumor</td>
                                <td>Supplier</td>

                            </tr>

                            @foreach ($data as $p)
                                <tr>

                                    <td style="min-width:120px">
                                        {{ $p->tgl_pembelian }}
                                    </td>
                                    <td>
                                        {{ $p->no_pembelian }}
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
                                        {{ $p->jumlah_detail_pembelian }}
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
                                        {{ $p->jumlah_detail_pembelian }}
                                    </td>
                                    <td>
                                        {{ $p->berat_detail_pembelian }}
                                    </td>
                                    <td>
                                        {{ 'Rp' . number_format($p->harga_detail_pembelian) }}
                                    </td>
                                  
                                    <td>
                                        {{ 'Rp' . number_format($p->subtotal_detail_pembelian) }}
                                    </td>
                                    <td>
                                        {{ 'Rp' . number_format($p->ppn_detail_pembelian) }}

                                    </td>
                                    <td>
                                        {{ 'Rp' . number_format($p->total_detail_pembelian) }}
                                    </td>
                                    <td>
                                        {{ $p->layanan }}
                                    </td>
                                    <td>
                                        {{ $p->nama_pelanggan }}
                                    </td>
                                    <td>
                                        {{ $p->nama_pemasok }}
                                    </td>



                                </tr>
                            @endforeach

                        </table>
                    </div>

                    <a href="{{ url('sales') }}" class="btn btn-primary  mb-4 ml-3" style="margin-top: 30px">Back</a>



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

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
    @if (session()->has('failed'))
        <div class="alert alert-danger" role="alert">
            {{ session('failed') }}
        </div>
    @endif





    <div class="container  ">
        <div class="card shadow mb-4 ml-4 mr-4">
            <div class="card-header py-3 mb-2 ">
                <h6 class="m-0 font-weight-bold text-primary">Bill Payment</h6>
            </div>
            <form action={{ url('bill') }} method="post">
                @csrf
                <div class="col-md-3 mt-2 d-flex justify-content-end ml-3">
                    <input type="date" required name="tgl_pembayaran" class="form-control" value='<?= date('Y-m-d') ?>'>
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
                            <div class="table-responsive text-center">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <td colspan="8">Quotation</td>
                                        <td colspan="15">Delivery</td>
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

                                    <tbody id="bodyTable-1">

                                    </tbody>



                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab2">
                            <div class="table-responsive text-center">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <td colspan="8">Quotation</td>
                                        <td colspan="15">Delivery</td>
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
                                            <td>VAT 11%</td>
                                            <td>VAT 12%</td>
                                            <td>Total Amount</td>
                                            <td>Processing</td>
                                            <td>customer</td>
                                            <td>Prepared</td>



                                        </tr>
                                    </thead>

                                    <tbody id="bodyTable-2">

                                    </tbody>



                                </table>
                            </div>
                        </div>

                    </div>
                    <a href={{ url()->previous() }} class="btn btn-primary my-3"> Back</a>
                    <button type=submit name=submit class="btn btn-primary my-3">submit</button>
            </form>
        </div>




    </div>
    </div>



    <script>
        let no_penjualan = '<?= $no_penjualan ?>'

        $(document).ready(function() {
            $.ajax({
                url: `{{ url('bill/getSalesDetail') }}/${no_penjualan}`,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    html = ''
                    html2 = ''
                    data.map((d, i) => {

                        if (d.type == 1) {

                            html += `<tr>`
                            html += `<td>${i+1}</td>`
                            html +=
                                `<td hidden> <input type="text" value=${ d.id_transaksi } name="id_transaksi[]"></td>`
                            html +=
                                `<td hidden> <input type="text" value=${ d.id_produk } name="id_produk[]"></td>`
                            html +=
                                `<td hidden> <input type="text" value=${ d.id_pengiriman } name="id_pengiriman[]">`
                            html +=
                                `<td hidden> <input type="text" value=${ d.no_pengiriman } name="no_pengiriman[]"></td>`
                            html +=
                                `<td style="min-width:120px">${ d.tgl_pengiriman }</td>`
                            html += `<td>${ d.no_pengiriman }</td>`
                            html += `<td>${ d.nomor_pekerjaan }</td>`
                            html += `<td>${ d.nama_produk }</td>`
                            html += `<td>${ d.tebal_penawaran }</td>`
                            html += `<td>${ d.lebar_penawaran }</td>`
                            html += `<td>${ d.panjang_penawaran }</td>`
                            html += `<td>${ d.jumlah }</td>`
                            html += `<td>${ d.nama_produk }</td>`
                            html += `<td>${ d.tebal_penawaran }</td>`
                            html += `<td>${ d.lebar_penawaran }</td>`
                            html += `<td>${ d.panjang_penawaran }</td>`
                            html += `<td>${ d.jumlah }</td>`
                            html += `<td>${ d.berat }</td>`
                            html +=
                                `<td>${ 'Rp.' + new Intl.NumberFormat('en-DE').format( d.harga )}</td>`
                            html +=
                                `<td>${ 'Rp.' + new Intl.NumberFormat('en-DE').format(d.ongkir )}</td>`
                            html +=
                                `<td>${ 'Rp.' + new Intl.NumberFormat('en-DE').format(d.subtotal )}</td>`
                            html +=
                                `<td>${ 'Rp.' + new Intl.NumberFormat('en-DE').format(d.ppn )}</td>`
                            html +=
                                `<td>${ 'Rp.' + new Intl.NumberFormat('en-DE').format(d.total )}</td>`
                            html += `<td>${ d.layanan }</td>`
                            html += `<td>${ d.nama_pelanggan }</td>`
                            html += `<td>${ d.nama_pengguna }</td>`
                            html += `</tr>`

                        } else {

                            html2 += `<tr>`
                            html2 += `<td>${i+1}</td>`
                            html2 +=
                                `<td hidden> <input type="text" value=${ d.id_transaksi } name="id_transaksi[]"></td>`
                            html2 +=
                                `<td hidden> <input type="text" value=${ d.id_produk } name="id_produk[]"></td>`
                            html2 +=
                                `<td hidden> <input type="text" value=${ d.id_pengiriman } name="id_pengiriman[]">`
                            html2 +=
                                `<td hidden> <input type="text" value=${ d.no_pengiriman } name="no_pengiriman[]"></td>`
                            html2 +=
                                `<td style="min-width:120px">${ d.tgl_pengiriman }</td>`
                            html2 += `<td>${ d.no_pengiriman }</td>`
                            html2 += `<td>${ d.nomor_pekerjaan }</td>`
                            html2 += `<td>${ d.nama_produk }</td>`
                            html2 += `<td>${ d.tebal_penawaran }</td>`
                            html2 += `<td>${ d.lebar_penawaran }</td>`
                            html2 += `<td>${ d.panjang_penawaran }</td>`
                            html2 += `<td>${ d.jumlah }</td>`
                            html2 += `<td>${ d.nama_produk }</td>`
                            html2 += `<td>${ d.tebal_penawaran }</td>`
                            html2 += `<td>${ d.lebar_penawaran }</td>`
                            html2 += `<td>${ d.panjang_penawaran }</td>`
                            html2 += `<td>${ d.jumlah }</td>`
                            html2 += `<td>${ d.berat }</td>`
                            html2 +=
                                `<td>${ 'Rp.' + new Intl.NumberFormat('en-DE').format( d.harga )}</td>`
                            html2 +=
                                `<td>${ 'Rp.' + new Intl.NumberFormat('en-DE').format(d.ongkir )}</td>`
                            html2 +=
                                `<td>${ 'Rp.' + new Intl.NumberFormat('en-DE').format(d.subtotal )}</td>`

                            html2 +=
                                `<td>${ 'Rp.' + new Intl.NumberFormat('en-DE').format(d.ppn )}</td>`
                            html2 +=
                                `<td>${ 'Rp.' + new Intl.NumberFormat('en-DE').format(d.subtotal * 0.12 )}</td>`
                            html2 +=
                                `<td>${ 'Rp.' + new Intl.NumberFormat('en-DE').format(d.total )}</td>`
                            html2 += `<td>${ d.layanan }</td>`
                            html2 += `<td>${ d.nama_pelanggan }</td>`
                            html2 += `<td>${ d.nama_pengguna }</td>`
                            html2 += `</tr>`

                        }



                    })


                    $('#bodyTable-1').html(html)
                    $('#bodyTable-2').html(html2)

                }
            })
        })
    </script>
@endsection()

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
                            <select name="no_penjualan" id="no_penjualan" class="form-control" required>
                                <option value=""> Select Sales Number</option>
                                @foreach ($no_penjualan as $np)
                                    <option value="{{ str_replace('/', '-', $np->no_penjualan) }}">{{ $np->no_penjualan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mt-2 d-flex justify-content-end">
                            <input type="date" required name="tgl_pembayaran" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="card-body">

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

                            <tbody id="bodyTable">

                            </tbody>



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
            $('#no_penjualan').change(function() {
                $.ajax({
                    url: `{{ url('bill/getSalesDetail') }}/${$('#no_penjualan').val()}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        html = ''
                        console.log(data);
                        data.map((d, i) => {
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
                        })

                  
                        $('#bodyTable').html(html)

                    }
                })
            })
        })
    </script>
@endsection()

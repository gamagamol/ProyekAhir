@extends('template.index')
@section('content')
    <div class="container">
        @if (session()->has('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        <div class="card shadow mb-4">
            <div class="card-header py-3 mt-2">
                <h6 class="m-0 font-weight-bold text-primary">{{ $tittle }}</h6>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-md-6 mt-3">
                        <div class="row">
                            <table class="table table-bordered text-center">
                                <tr>
                                    <td>Quotation Month</td>
                                    <td>:</td>
                                    <td>
                                        <input type="month" class="form-control" name="month" id="month">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Quotation Day</td>
                                    <td>:</td>
                                    <td>
                                        <input type="date" class="form-control" name="date" id="date">
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col d-flex justify-content-end">

                                <button type="button" class="btn btn-primary mx-2" id="clear"> Clear</button>
                                <button type="button" class="btn btn-primary" id="search"> Search</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col d-flex justify-content-end ">
                        <a class="btn btn-success" id="btn-export" href="#">
                            <i class="fas fa-file-excel"></i>
                            Export</a>

                    </div>
                </div>
            </div>
            <div class="card-body">

                <div class="table-responsive text-center">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Date Quotation</th>
                                <th>No Quotation</th>
                                <th>Customer</th>
                                <th>Job Number</th>
                                <th>Grade</th>
                                <th>Thikness</th>
                                <th>Width</th>
                                <th>Length</th>
                                <th>QTY</th>
                                <th>Grade</th>
                                <th>Thikness</th>
                                <th>Width</th>
                                <th>Length</th>
                                <th>QTY</th>
                                <th>Weight</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                                <th>Process</th>
                                <th>Date Sales</th>
                                <th>No Sales</th>
                                <th>Supplier</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="Tbody">

                        </tbody>



                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection()

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
</script>
<script>
    let baseUrl = `{{ url('/') }}`



    const report = {

        callBackend: function(data = null) {
            $.ajax({

                url: `${baseUrl}/quotationReportDetailAjax`,
                type: 'GET',
                data: data,
                dataType: 'json',
                success: function(data) {
                    html = ''
                    data.map((d) => {
                        html += `<tr>`

                        html += `<td>${ d.tgl_penawaran }</td>`
                        html += `<td>${ d.no_penawaran }</td>`
                        html += `<td>${ d.nama_pelanggan }</td>`
                        html += `<td>${ d.nomor_pekerjaan }</td>`
                        html += `<td>${ d.nama_produk }</td>`
                        html += `<td>${ d.tebal_transaksi }</td>`
                        html += `<td>${ d.lebar_transaksi }</td>`
                        html += `<td>${ d.panjang_transaksi }</td>`
                        html += `<td>${ d.jumlah }</td>`
                        html += `<td>${ d.nama_produk }</td>`
                        html += `<td>${ d.tebal_penawaran }</td>`
                        html += `<td>${ d.lebar_penawaran }</td>`
                        html += `<td>${ d.panjang_penawaran }</td>`
                        html += `<td>${ d.jumlah }</td>`
                        html += `<td>${ d.berat }</td>`
                        html +=
                            `<td>Rp.${Intl.NumberFormat('en-IN', { maximumSignificantDigits: 3 }).format( d.harga) }</td>`
                        html +=
                            `<td>Rp.${Intl.NumberFormat('en-IN', { maximumSignificantDigits: 3 }).format( d.total) }</td>`
                        html += `<td>${ d.layanan }</td>`
                        html += `<td>${ (d.tgl_penjualan)?d.tgl_penjualan:'-' }</td>`
                        html += `<td>${ (d.no_penjualan)?d.no_penjualan:'-' }</td>`
                        html += `<td>${ (d.nama_pemasok)?d.nama_pemasok:'-' }</td>`
                        html += `<td>${ (d.no_penjualan) ? 'CLOSE' :'OPEN'  }</td>`
                        html += `</tr>`
                    })


                    $('#Tbody').html(html)
                }
            })
        },
        search: function() {
            data = {
                month: $('#month').val(),
                date: $('#date').val()
            }


            this.callBackend(data)
        }
    }

    $(document).ready(function() {

        report.callBackend()
        $('#search').click(() => {
            report.search()
        })

        $('#clear').click(function() {
            $('#month').val('')
            $('#date').val('')
        })
        $('#btn-export').click(function() {
            let month = $('#month').val()
            let date = $('#date').val()

            // if (month.length > 1) {
            //     month = month[1]
            // }

            // if (date.length > 1) {
            //     date = date[2]
            // }

            let url = `${baseUrl}/exportDetailReport`

            $(this).attr('href', `${url}/${(month.length >1) ? month : 0}/${date}`)
        })


    })
</script>

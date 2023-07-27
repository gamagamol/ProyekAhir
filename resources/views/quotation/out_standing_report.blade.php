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
                                    <td>Month</td>
                                    <td>:</td>
                                    <td>
                                        <input type="month" class="form-control" name="month" id="month">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Day</td>
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
                    <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0"
                        style="width: 1200px">
                        <thead>
                            <tr>
                                <th style="width:500px">Sales Date</th>
                                <th>Develivery Date</th>
                                <th>Sales Number</th>
                                <th>Purchase Number</th>
                                <th>Develivery Number</th>
                                <th>Sales</th>
                                <th>Grade</th>
                                <th colspan="3">Matrial Size</th>
                                <th>Weight</th>
                                <th>Qty</th>
                                <th>Process</th>
                                <th>Customer</th>
                                <th>Supplier</th>
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

                url: `${baseUrl}/outStandingReportAjax`,
                type: 'GET',
                data: data,
                dataType: 'json',
                success: function(data) {
                    html = ''

                    data.data.map((d) => {
                        html += `<tr class='text-center'>`
                        html += `<td>${ d.tgl_penjualan }</td>`
                        html += `<td>${ (d.tgl_pengiriman) ? d.tgl_pengiriman : '-' }</td>`
                        html += `<td>${ d.no_penjualan }</td>`
                        html += `<td>${ (d.no_pembelian) ? d.no_pembelian :'-' }</td>`
                        html += `<td>${ (d.no_pengiriman) ? d.no_pengiriman :'-' }</td>`
                        html += `<td>${ d.nama_pegawai }</td>`
                        html += `<td>${ d.nama_produk }</td>`
                        html += `<td>${ d.panjang_transaksi }</td>`
                        html += `<td>${ d.lebar_transaksi }</td>`
                        html += `<td>${ d.tebal_transaksi }</td>`
                        html += `<td>${ d.berat }</td>`
                        html += `<td>${ d.jumlah }</td>`
                        html += `<td>${ d.layanan }</td>`
                        html += `<td>${ d.nama_pelanggan }</td>`
                        html += `<td>${ (d.nama_pemasok) ? d.nama_pemasok :'-' }</td>`


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
            console.log(data);

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
            let month = $('#month').val().split('-')
            let date = $('#date').val().split('-')

            if (month.length > 1) {
                month = month[1]
            }

            if (date.length > 1) {
                date = date[2]
            }



            let url = `${baseUrl}/outStandingReportExport`

            $(this).attr('href', `${url}/${(month.length >1) ? month : 0}/${date}`)
        })


    })
</script>

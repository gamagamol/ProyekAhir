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
                                <th>Customer</th>
                                <th>Sales Name</th>
                                <th>QTN</th>
                                <th>Sales</th>
                                <th>Loss</th>

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

                url: `${baseUrl}/customerOmzetReportAjax`,
                type: 'GET',
                data: data,
                dataType: 'json',
                success: function(data) {
                    html = ''
                    let total_penawaran = 0
                    let total_penawaran_loss = 0
                    let total_penjualan = 0

                    data.data.map((d) => {
                        html += `<tr>`
                        html += `<td>${ d.nama_pelanggan }</td>`
                        html += `<td>${ d.nama_pegawai }</td>`
                        html +=
                            `<td>Rp.${ new Intl.NumberFormat('en-IN', { maximumSignificantDigits: 3 }).format( d.total_penawaran) }</td>`


                        html +=
                            `<td>Rp.${new Intl.NumberFormat('en-IN', { maximumSignificantDigits: 3 }).format(d.total_penjualan) }</td>`
                        html +=
                            `<td>Rp.${new Intl.NumberFormat('en-IN', { maximumSignificantDigits: 3 }).format( d.total_penawaran_loss )}</td>`

                        html += `</tr>`

                        total_penawaran += parseInt(d.total_penawaran)
                        total_penawaran_loss += (d.total_penawaran_loss) ? parseInt(d
                            .total_penawaran_loss) : 0
                        total_penjualan += (d.total_penjualan) ? parseInt(d
                            .total_penjualan) : 0
                    })

                    html += `<tr>
                            <td colspan='2' class='text-center'> Grand Total </td>
                            <td> Rp.${new Intl.NumberFormat('en-IN', { maximumSignificantDigits: 3 }).format(total_penawaran)} </td>
                            <td> Rp.${new Intl.NumberFormat('en-IN', { maximumSignificantDigits: 3 }).format(total_penjualan)} </td>
                            <td> Rp.${new Intl.NumberFormat('en-IN', { maximumSignificantDigits: 3 }).format(total_penawaran_loss)} </td>
                            </tr>
                        `



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



            let url = `${baseUrl}/customerOmzetReportExport`

            $(this).attr('href', `${url}/${(month.length >1) ? month : 0}/${date}`)
        })


    })
</script>

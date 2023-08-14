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
                                <tr>
                                    <td>Quotation Day (To)</td>
                                    <td>:</td>
                                    <td>
                                        <input type="date" class="form-control" name="date_to" id="date_to">
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

    {{-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
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
                date: $('#date').val(),
                date_to: $('#date_to').val()

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
             $('#date_to').val('')

        })
        $('#btn-export').click(function() {
             let year_month = $('#month').val()
            let date = $('#date').val()
            let date_to = $('#date_to').val()

            if (year_month == '') {
                year_month = '0'
            }

            if (date == '') {
                date = '0'
            }

            if (date_to == '') {
                date_to = '0'
            }

            let url = `${baseUrl}/exportDetailReport`

            $(this).attr('href', `${url}/${year_month}/${date}/${date_to}`)
        })


    })
</script> --}}

    {{-- jquery --}}
    <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM="
        crossorigin="anonymous"></script>
    {{-- data table --}}
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script>
        let baseUrl = `{{ url('/') }}`

        function callBackend(data = null) {

            let result = []
            $.ajax({
                url: `${baseUrl}/quotationReportDetailAjax`,
                type: 'GET',
                data: data,
                async: false,
                dataType: 'json',
                success: function(data) {
                    data.map((d) => {
                        result.push(d)
                    })
                }
            })

            return result;

            // dataTable(result)
        }


        let data = callBackend();
        // console.log(data);

        const dataTablee = $('#dataTable').DataTable({
            pageLength: 5,
            scrollX: true,
            autoWidth: true,
            data: data,
            columns: [{
                    mData: null,
                    mRender: function(d) {
                        return (d.tgl_penawaran) ? d.tgl_penawaran : '-'
                    }
                },
                {
                    mData: null,
                    mRender: function(d) {
                        return (d.no_penawaran) ? d.no_penawaran : '-'
                    }
                },
                {
                    mData: null,
                    mRender: function(d) {
                        return (d.nama_pelanggan) ? d.nama_pelanggan : '-'

                    }
                },
                {
                    mData: null,
                    mRender: function(d) {
                        return (d.nomor_pekerjaan) ? d.nomor_pekerjaan : '-'

                    }
                },
                {
                    mData: null,
                    mRender: function(d) {
                        return (d.nama_produk) ? d.nama_produk : '-'

                    }
                },
                {
                    mData: null,
                    mRender: function(d) {
                        return (d.tebal_transaksi) ? d.tebal_transaksi : 0

                    }
                },

                {
                    mData: null,
                    mRender: function(d) {
                        return (d.lebar_transaksi) ? d.lebar_transaksi : 0

                    }
                },

                {
                    mData: null,
                    mRender: function(d) {
                        return (d.panjang_transaksi) ? d.panjang_transaksi : 0

                    }
                },
                {
                    mData: null,
                    mRender: function(d) {
                        return (d.jumlah) ? d.jumlah : 0

                    }
                },
                 {
                    mData: null,
                    mRender: function(d) {
                        return (d.nama_produk) ? d.nama_produk : '-'

                    }
                },
                {
                    mData: null,
                    mRender: function(d) {
                        return (d.tebal_penawaran) ? d.tebal_penawaran : 0

                    }
                },

                {
                    mData: null,
                    mRender: function(d) {
                        return (d.lebar_penawaran) ? d.lebar_penawaran : 0

                    }
                },

                {
                    mData: null,
                    mRender: function(d) {
                        return (d.panjang_penawaran) ? d.panjang_penawaran : 0

                    }
                },
                {
                    mData: null,
                    mRender: function(d) {
                        return (d.jumlah) ? d.jumlah : 0

                    }
                },
                {
                    mData: null,
                    mRender: function(d) {
                        return (d.berat) ? d.berat : 0

                    }
                },
                {
                    mData: null,
                    mRender: function(d) {
                        return (d.harga) ? Intl.NumberFormat('en-IN', { maximumSignificantDigits: 3 }).format( d.harga)  : 0

                    }
                },
                {
                    mData: null,
                    mRender: function(d) {
                        return (d.total) ? Intl.NumberFormat('en-IN', { maximumSignificantDigits: 3 }).format( d.total)  : 0

                    }
                },
                {
                    mData: null,
                    mRender: function(d) {
                        return (d.layanan) ? d.layanan : 0

                    }
                }
                ,{
                    mData: null,
                    mRender: function(d) {
                        return (d.tgl_penjualan) ? d.tgl_penjualan : '-'

                    }
                },
                {
                    mData: null,
                    mRender: function(d) {
                        return (d.no_penjualan) ? d.no_penjualan : '-'

                    }
                },
                {
                    mData: null,
                    mRender: function(d) {
                        return (d.nama_pemasok) ? d.nama_pemasok : '-'

                    }
                },
                {
                    mData: null,
                    mRender: function(d) {
                        return (d.no_penjualan) ? 'CLOSE' : 'OPEN'

                    }
                },
               

            ]
        })



        $('#search').click(() => {
            let data = {
                month: $('#month').val(),
                date: $('#date').val(),
                date_to: $('#date_to').val()
            }
            let newData = callBackend(data)
            dataTablee.clear().draw();
            dataTablee.rows.add(newData).draw();

        })

        $('#clear').click(function() {
            $('#month').val('')
            $('#date').val('')
            dataTablee.clear().draw();
            dataTablee.rows.add(data).draw();

        })


        $('#btn-export').click(function() {
            let year_month = $('#month').val()
            let date = $('#date').val()
            let date_to = $('#date_to').val()

            if (year_month == '') {
                year_month = 0
            }

            if (date == '') {
                date = 0
            }

            if (date_to == '') {
                date_to = 0
            }



            let url = `${baseUrl}/exportDetailReport`

            $(this).attr('href', `${url}/${year_month}/${date}/${date_to}`)
        })
    </script>
@endsection()

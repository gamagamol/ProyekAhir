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
                                    <td> Month</td>
                                    <td>:</td>
                                    <td>
                                        <input type="month" class="form-control" name="month" id="month">
                                    </td>
                                </tr>
                                <tr>
                                    <td> Day</td>
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
                            <th>NO QUOTATION</th>
                            <th>TGL QUOTATION</th>
                            <th>NO SALES</th>
                            <th>TGL SALES</th>
                            <th>NAMA CUSTOMER</th>
                            <th>NAMA SALES</th>
                            <th>NO PEMBELIAN</th>
                            <th>TGL PEMBELIAN</th>
                            <th>NAMA SUPPLIER</th>
                            <th>NO DELIVERY</th>
                            <th>TGL DELIVERY</th>
                            <th>NO INVOICE</th>
                            <th>TGL INVOICE</th>
                            <th>NO PEMBAYARAN</th>
                            <th>TGL PEMBAYARAN</th>
                            <th>SUBTOTAL</th>
                            <th>PPH11%</th>
                            <th>TOTAL</th>
                            <th>SUBTOTAL PEMBELIAN</th>
                            <th>PPH11% PEMBELIAN</th>
                            <th>TOTAL PEMBELIAN</th>
                            <th>TGL FAKTUR</th>
                            <th>TUKAR INVOICE</th>
                            <th>FAKTUR PAJAK</th>
                            <th>OMZET</th>
                        </thead>
                        <tbody id="Tbody">
                        </tbody>



                    </table>
                </div>
            </div>
        </div>
    </div>

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
                url: `${baseUrl}/omzetReportAjax`,
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
        const dataTablee = $('#dataTable').DataTable({
            // lengthChange: false,
            pageLength: 5,
            // ordering: false,
            // pagingType: 'input',
            scrollX: true,
            autoWidth: true,
            data: data,
            columns: [{
                    mData: null,
                    mRender: function(d) {
                        return (d.no_penawaran) ? d.no_penawaran : '-'
                    }
                },
                {
                    mData: null,
                    mRender: function(d) {
                        return (d.tgl_penawaran) ? d.tgl_penawaran : '-'
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
                        return (d.tgl_penjualan) ? d.tgl_penjualan : '-'

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
                        return (d.nama_pegawai) ? d.nama_pegawai : '-'

                    }
                },
                {
                    mData: null,
                    mRender: function(d) {
                        return (d.no_pembelian) ? d.no_pembelian : '-'

                    }
                },
                {
                    mData: null,
                    mRender: function(d) {
                        return (d.tgl_pembelian) ? d.tgl_pembelian : '-'

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
                        return (d.no_pengiriman) ? d.no_pengiriman : '-'

                    }
                },
                {
                    mData: null,
                    mRender: function(d) {
                        return (d.tgl_pengiriman) ? d.tgl_pengiriman : '-'

                    }
                },
                {
                    mData: null,
                    mRender: function(d) {
                        return (d.no_tagihan) ? d.no_tagihan : '-'

                    }
                },
                {
                    mData: null,
                    mRender: function(d) {
                        return (d.tgl_tagihan) ? d.tgl_tagihan : '-'

                    }
                },
                {
                    mData: null,
                    mRender: function(d) {
                        return (d.no_pembayaran) ? d.no_pembayaran : '-'

                    }
                },
                {
                    mData: null,
                    mRender: function(d) {
                        return (d.tgl_pembayaran) ? d.tgl_pembayaran : '-'

                    }
                },
                {
                    mData: null,
                    mRender: function(d) {
                        return (d.subtotal) ? new Intl.NumberFormat('en-IN', {
                            maximumSignificantDigits: 3
                        }).format(d.subtotal) : 0

                    }
                },
                {
                    mData: null,
                    mRender: function(d) {
                        return (d.ppn) ? new Intl.NumberFormat('en-IN', {
                            maximumSignificantDigits: 3
                        }).format(d.ppn) : 0

                    }
                },
                {
                    mData: null,
                    mRender: function(d) {
                        return (d.total) ? new Intl.NumberFormat('en-IN', {
                            maximumSignificantDigits: 3
                        }).format(d.total) : 0

                    }
                },
                {
                    mData: null,
                    mRender: function(d) {
                        return (d.subtotal_detail_pembelian) ? new Intl.NumberFormat('en-IN', {
                            maximumSignificantDigits: 3
                        }).format(d.subtotal_detail_pembelian) : 0

                    }
                },
                {
                    mData: null,
                    mRender: function(d) {
                        return (d.ppn_detail_pembelian) ? new Intl.NumberFormat('en-IN', {
                            maximumSignificantDigits: 3
                        }).format(d.ppn_detail_pembelian) : 0

                    }
                },
                {
                    mData: null,
                    mRender: function(d) {
                        return (d.total_detail_pembelian) ? new Intl.NumberFormat('en-IN', {
                            maximumSignificantDigits: 3
                        }).format(d.total_detail_pembelian) : 0

                    }
                },

                {
                    mData: null,
                    mRender: function(d) {
                        return '-'
                    }
                },
                {
                    mData: null,
                    mRender: function(d) {
                        return '-'
                    }
                },
                {
                    mData: null,
                    mRender: function(d) {
                        return '-'
                    }
                },
                {
                    mData: null,
                    mRender: function(d) {

                        let total = (d.total) ? parseInt(d.total) : 0
                        let total_detail_pembelian = (d.total_detail_pembelian) ? parseInt(d
                            .total_detail_pembelian) : 0

                        let omzet=total - total_detail_pembelian
                        return (omzet) ? new Intl.NumberFormat('en-IN', {
                            maximumSignificantDigits: 3
                        }).format(omzet) : 0
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



            let url = `${baseUrl}/omzetReportExport`

            $(this).attr('href', `${url}/${year_month}/${date}/${date_to}`)
        })
    </script>
@endsection()

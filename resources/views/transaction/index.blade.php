@extends('template.index')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/CustomStyleselect2.css') }}">

    <style>
        .mb-0>a {
            display: block;
            position: relative;
        }

        .mb-0>a:after {
            content: "\f078";
            /* fa-chevron-down */
            font-family: 'FontAwesome';
            position: absolute;
            right: 0;
        }

        .mb-0>a[aria-expanded="true"]:after {
            content: "\f077";
            /* fa-chevron-up */
        }
    </style>
    <div class="container">
        <div class="card shadow mb-4">
            <div class="card-header py-3 mt-2">
                <h6 class="m-0 font-weight-bold text-primary">{{ $tittle }} </h6>
            </div>
            <div class="card-body">
                <div class="row mb-5">
                    <div class="col">
                        <input type="month" id="date" class="form-control">
                    </div>
                    <div class="col">
                        <select name="transactionNumber" id="transactionNumber" class="form-control select2"></select>
                    </div>
                </div>
                <div id="accordion">

                    <div class="card mb-5">
                        <div class="card-header" id="heading-1">
                            <h5 class="mb-0">
                                <a role="button" data-toggle="collapse" href="#collapse-1" aria-expanded="true"
                                    aria-controls="collapse-1">
                                    Tracking Transaction Number
                                </a>
                            </h5>
                        </div>
                        <div id="collapse-1" class="collapse " data-parent="#accordion" aria-labelledby="heading-1">
                            <div class="card-body">

                                <div id="accordion-1">
                                    <div class="card mb-3">
                                        <div class="card-header" id="heading-1-6">
                                            <h5 class="mb-0">
                                                <a class="collapsed" role="button" data-toggle="collapse"
                                                    href="#collapse-1-6" aria-expanded="false" aria-controls="collapse-1-6">
                                                    Sales Order
                                                </a>
                                            </h5>
                                        </div>
                                        <div id="collapse-1-6" class="collapse" data-parent="#accordion-1"
                                            aria-labelledby="heading-1-6">
                                            <div class="card-body" id="salesOrder">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-3">
                                        <div class="card-header" id="heading-1-1">
                                            <h5 class="mb-0">
                                                <a class="collapsed" role="button" data-toggle="collapse"
                                                    href="#collapse-1-1" aria-expanded="false" aria-controls="collapse-1-1">
                                                    Purchase
                                                </a>
                                            </h5>
                                        </div>
                                        <div id="collapse-1-1" class="collapse" data-parent="#accordion-1"
                                            aria-labelledby="heading-1-1">
                                            <div class="card-body" id="purchaseOrder">


                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-3">
                                        <div class="card-header" id="heading-3-1">
                                            <h5 class="mb-0">
                                                <a class="collapsed" role="button" data-toggle="collapse"
                                                    href="#collapse-3-1" aria-expanded="false" aria-controls="collapse-3-1">
                                                    Good Receipt
                                                </a>
                                            </h5>
                                        </div>
                                        <div id="collapse-3-1" class="collapse" data-parent="#accordion-1"
                                            aria-labelledby="heading-3-1">
                                            <div class="card-body" id="goodReceipt">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-3">
                                        <div class="card-header" id="heading-1-3">
                                            <h5 class="mb-0">
                                                <a class="collapsed" role="button" data-toggle="collapse"
                                                    href="#collapse-1-3" aria-expanded="false" aria-controls="collapse-1-3">
                                                    Devlivery
                                                </a>
                                            </h5>
                                        </div>
                                        <div id="collapse-1-3" class="collapse" data-parent="#accordion-1"
                                            aria-labelledby="heading-1-3">
                                            <div class="card-body" id="deliveryOrder">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-3">
                                        <div class="card-header" id="heading-1-4">
                                            <h5 class="mb-0">
                                                <a class="collapsed" role="button" data-toggle="collapse"
                                                    href="#collapse-1-4" aria-expanded="false"
                                                    aria-controls="collapse-1-4">
                                                    Invoice
                                                </a>
                                            </h5>
                                        </div>
                                        <div id="collapse-1-4" class="collapse" data-parent="#accordion-1"
                                            aria-labelledby="heading-1-4">
                                            <div class="card-body" id="invoiceOrder">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-3">
                                        <div class="card-header" id="heading-1-5">
                                            <h5 class="mb-0">
                                                <a class="collapsed" role="button" data-toggle="collapse"
                                                    href="#collapse-1-5" aria-expanded="false"
                                                    aria-controls="collapse-1-5">
                                                    Payment
                                                </a>
                                            </h5>
                                        </div>
                                        <div id="collapse-1-5" class="collapse" data-parent="#accordion-1"
                                            aria-labelledby="heading-1-5">
                                            <div class="card-body" id="paymentOrder">

                                            </div>
                                        </div>
                                    </div>



                                </div>

                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>


    <script>
        $('.select2').select2();

        let result;

            let baseUrl = `{{ url('/') }}`
            $('#date').change(() => {
                let date = $('#date').val()
                $.ajax({
                    url: `${baseUrl}/getTransactionNumberByDate/${date}`,
                    type: 'GET',
                    dataType: 'json',
                    async: false,
                    success: function(data) {
                        html = `<option value='null' >Please Choose Transaction Number</option>`

                        data.data.map((d) => {
                            html +=
                                `<option value='${d.no_penjualan}'>${d.no_penjualan} </option>`
                        })
                        result = data

                        $('#transactionNumber').html(html)



                        // $('#transactionNumber').on('change',function(){
                        //     let transactionData = data.data.filter((sales) => {
                        //         if (sales.no_penjualan == $(
                        //                 '#transactionNumber').val()) {
                        //             return sales
                        //         }
                        //     })

                        //     transactionData.map((t) => {
                        //         $('#salesOrder').html(
                        //             `<p>${t.tgl_penjualan} -> ${t.no_penjualan} </p>`
                        //             )

                        //         // pembelian
                        //         html_pem = ''

                        //         t.pembelian.map((pem, i) => {

                        //             if (i == 0) {
                        //                 html_pem +=
                        //                     `<p>${t.tgl_pembelian[i]} -> ${pem} </p>`

                        //             } else {
                        //                 // console.log(t.pembelian[i]);
                        //                 if (t.pembelian[i - 1] != pem) {
                        //                     html_pem +=
                        //                         `<p>${t.tgl_pembelian[i]} -> ${pem} </p>`

                        //                 }
                        //             }
                        //         })

                        //         $('#purchaseOrder').html(html_pem)

                        //         // penerimaan
                        //         html_penerimaan = ''

                        //         t.penerimaan.map((penerimaan, i) => {

                        //             if (i == 0) {
                        //                 html_penerimaan +=
                        //                     `<p>${t.tgl_penerimaan[i]} -> ${penerimaan} </p>`

                        //             } else {
                        //                 if (t.penerimaan[i - 1] !=
                        //                     penerimaan) {
                        //                     html_penerimaan +=
                        //                         `<p>${t.tgl_penerimaan[i]} -> ${penerimaan} </p>`

                        //                 }
                        //             }
                        //         })

                        //         $('#goodReceipt').html(html_penerimaan)

                        //         // pengiriman
                        //         html_pengiriman = ''

                        //         t.pengiriman.map((pengiriman, i) => {

                        //             if (i == 0) {
                        //                 html_pengiriman +=
                        //                     `<p>${t.tgl_pengiriman[i]} -> ${pengiriman} </p>`

                        //             } else {
                        //                 if (t.pengiriman[i - 1] !=
                        //                     pengiriman) {
                        //                     html_pengiriman +=
                        //                         `<p>${t.tgl_pengiriman[i]} -> ${pengiriman} </p>`

                        //                 }
                        //             }
                        //         })

                        //         $('#deliveryOrder').html(html_pengiriman)

                        //         // tagihan
                        //         $('#invoiceOrder').html(
                        //             `<p>${t.tgl_tagihan} -> ${t.tagihan} </p>`
                        //             )
                        //         // tagihan
                        //         $('#paymentOrder').html(
                        //             `<p>${t.tgl_pembayaran} -> ${t.pembayaran} </p>`
                        //             )

                        //     })

                        //     $('#collapse-1').addClass('show')
                        //     $('#collapse-1-6').addClass('show')

                        // })

                    }
                })

            })


            $('#transactionNumber').change(function() {

                let transactionData = result.data.filter((sales) => {
                    if (sales.no_penjualan == $(
                            '#transactionNumber').val()) {
                        return sales
                    }
                })

                transactionData.map((t) => {
                    $('#salesOrder').html(
                        `<p>${t.tgl_penjualan} -> ${t.no_penjualan} </p>`
                    )

                    // pembelian
                    html_pem = ''

                    t.pembelian.map((pem, i) => {

                        if (i == 0) {
                            html_pem +=
                                `<p>${(t.tgl_pembelian[i])?t.tgl_pembelian[i]:'-'} -> ${(pem)?pem:'-'} </p>`

                        } else {
                            // console.log(t.pembelian[i]);
                            if (t.pembelian[i - 1] != pem) {
                                html_pem +=
                                    `<p>${(t.tgl_pembelian[i])?t.tgl_pembelian[i]:'-'} -> ${(pem)?pem:'-'} </p>`

                            }
                        }
                    })

                    $('#purchaseOrder').html(html_pem)

                    // penerimaan
                    html_penerimaan = ''

                    t.penerimaan.map((penerimaan, i) => {

                        if (i == 0) {
                            html_penerimaan +=
                                `<p>${(t.tgl_penerimaan[i])?t.tgl_penerimaan[i]:'-'} -> ${(penerimaan)?penerimaan:'-'} </p>`

                        } else {
                            if (t.penerimaan[i - 1] !=
                                penerimaan) {
                                html_penerimaan +=
                                    `<p>${(t.tgl_penerimaan[i])?t.tgl_penerimaan[i]:'-'} -> ${(penerimaan)?penerimaan:'-'} </p>`

                            }
                        }
                    })

                    $('#goodReceipt').html(html_penerimaan)

                    // pengiriman
                    html_pengiriman = ''

                    t.pengiriman.map((pengiriman, i) => {

                        if (i == 0) {
                            html_pengiriman +=
                                `<p>${(t.tgl_pengiriman[i])?t.tgl_pengiriman[i]:'-'} -> ${(pengiriman)?pengiriman:'-'} </p>`

                        } else {
                            if (t.pengiriman[i - 1] !=
                                pengiriman) {
                                html_pengiriman +=
                                    `<p>${(t.tgl_pengiriman[i])?t.tgl_pengiriman[i]:'-'} -> ${(pengiriman)?pengiriman:'-'} </p>`

                            }
                        }
                    })

                    $('#deliveryOrder').html(html_pengiriman)

                    // tagihan
                    $('#invoiceOrder').html(`<p>${(t.tgl_tagihan) ? t.tgl_tagihan:'-'} -> ${(t.tagihan)?t.tagihan:'-'} </p>`)
                    // tagihan
                    $('#paymentOrder').html(`<p>${(t.tgl_pembayaran) ? t.tgl_pembayaran:'-'} -> ${(t.pembayaran) ? t.pembayaran:'-;'} </p>` )

                })

                $('#collapse-1').addClass('show')
                $('#collapse-1-6').addClass('show')



            })
    </script>
@endsection()

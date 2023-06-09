<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>{{ $tittle }}</title>
    <style>
        .rincian-table {
            font-weight: bold;
            font-size: 1.1rem;
            border-collapse: separate;
            border-spacing: 0 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        {{-- strat cop surat --}}
        <div class="row">
            <div class="col">
                <img src="{{ asset('assets/img/ikhi.jpg') }}" alt="logo" class="mt-5 ml-5 mb-2 " width="15%">
                <hr class=" border border-5 border-dark"> <br>
                <div class="row mb-3">

                    <div class="col">
                        <h3 class="text-strat "> PT IBARAKI KOGYO HANAN INDONESIA</h3>
                    </div>
                    <div class="col">
                        <h3 class="text-end  "> PURCHASE ORDER</h3>
                    </div>
                </div>
                <hr class=" border border-5 border-dark">
            </div>
        </div>
        {{-- end cop surat --}}

        <div class="row">
            <div class="col">
                <h5>

                    New Three One Building, Jl. Industri Timur Raya Blok WW5 Jl. Jababeka Raya No.18, Mekarmukti,
                    Cikarang Utara, Bekasi Regency, West Java 17531 <br>
                    Phone: (021) 8932 6362<br>
                    Email : sales@ibaraki.co.id<br>
                    ahmadsolihin@ibaraki.co.id<br>
                </h5>

            </div>

            <div class="col-md-4">
                <h5 class=" ml-5">
                    Date : {{ $data[0]->tgl_pembelian }} <br><br>
                    NO PO : {{ $data[0]->no_pembelian }} <br><br>
                    NO QTH : {{ $data[0]->no_penawaran }} <br>

                </h5>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col">
                <h4>
                    Purchase order for :
                </h4>
                <h5>
                    {{ $data[0]->perwakilan }} <br>
                    {{ $data[0]->alamat_pelanggan }} <br>

                </h5>
            </div>
        </div>


        <div class="row">
            <div class="col">
                <table class="table table-bordered  boder-5 border-dark text-center fw-bold" id="dataTable"
                    width="100%" cellspacing="0">
                    <tr>
                        <td colspan="7">INQUIRY</td>
                        <td colspan="9">QUOTATION</td>
                    </tr>
                    <tr>
                        <td>No</td>
                        <td>Job Number</td>
                        <td>Grade</td>
                        <td colspan="3">Material Size</td>
                        <td>QTY</td>
                        <td>Grade</td>
                        <td colspan="3">Material Size</td>
                        <td>QTY</td>
                        <td>WEIGHT(KG)</td>
                        <td>Price</td>
                        <td>Amount</td>
                        <td>Processing</td>



                    </tr>
                    @foreach ($data as $p)
                        <tr>

                            <td style="min-width:120px">
                                {{ $p->tgl_pembelian }}
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
                                {{ $p->jumlah_detail_penjualan }}
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
                                {{ 'Rp' . number_format($p->harga) }}
                            </td>
                            <td>
                                {{ 'Rp' . number_format($p->subtotal_detail_pembelian) }}
                            </td>
                            <td>
                                {{ $p->layanan }}
                            </td>
                        </tr>
                    @endforeach
                </table>

                <table class="rincian-table d-flex justify-content-end" cellpadding='10'>
                    <tr>
                        <td>Total Amount</td>
                        <td>:</td>
                        <td>Rp.{{ number_format($subtotal) }}</td>
                    </tr>
                    <tr>
                        <td>VAT</td>
                        <td>:</td>
                        <td>Rp.{{ number_format($subtotal * 0.11) }}</td>
                    </tr>
                    <tr>
                        <td>Total Amount</td>
                        <td>:</td>
                        <td>Rp.{{ number_format($total) }}</td>
                    </tr>
                </table>

                <h5 class="text-decoration-underline "> Terbilang:</h5>
                <h6> {{ $penyebut }} </h6>

            </div>
        </div>

        {{-- <div class="row">
                    <div class="row mt-5 ms-5">
                        <div class="col">
                            <h5 class="text-center">
                                PT KOGYO HANAN INDONESIA
                            </h5>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col">
                            <h6 class="text-decoration-underline" style="margin-top: 120px">
                                Sales Admin
                            </h6>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col ">
                            <h6 class="text-decoration-underline">
                                {{ $data[0]->nama_pegawai }}
                            </h6>
                        </div>
                    </div>
                </div> --}}




        <div class="row justify-content-md-end">
            <div class="col-md-6">
                    <h5 class="text-end"> PT KOGYO HANAN INDONESIA </h6>
            </div>
        </div>
        <div class="row justify-content-md-end">
            <div class="col-md-3">
                    <h6 class="text-decoration-underline text-center" style="margin-top: 120px;"> Sales Admin </h6>
            </div>
        </div>
        <div class="row justify-content-md-end">
            <div class="col-md-3">
                    <h6 class="text-center" >  {{ $data[0]->nama_pegawai }} </h6>
            </div>
        </div>

    </div>

    </div>


    </div>










    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>

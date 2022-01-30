<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>{{ $tittle }}</title>

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
                        <h2 class="text-strat "> PT IBARAKI KOGYO HANAN INDONESIA</h2>
                    </div>
                    <div class="col">
                        <h2 class="text-end  "> QUOTATION</h2>
                    </div>
                </div>
                <hr class=" border border-5 border-dark">
            </div>
        </div>
        {{-- end cop surat --}}

        <div class="row">
            <div class="col">
                <h5>

                    Jl Antilop VI Blok I - 2 No. 7 Jayamukti Cikarang <br>
                    Phone: (021) 8932 6362<br>
                    Email : sales@ibaraki.co.id<br>
                    ahmadsolihin@ibaraki.co.id<br>
                </h5>

            </div>

            <div class="col-md-4">
                <h5 class="ml-5 ">Date : {{ $data[0]->tgl_penawaran }} <br><br></h5>
                <h5 class=" ml-5">

                    Quotation : {{ $data[0]->no_penawaran }} <br><br>
                    Customer : {{ $data[0]->id_pelanggan }} <br>

                </h5>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col">
                <h4>
                    Quotation For :
                </h4>
                <h5>
                    {{ $data[0]->perwakilan }} <br>
                    {{ $data[0]->nama_pelanggan }} <br>
                    {{ $data[0]->alamat_pelanggan }} <br>

                </h5>
            </div>

            <div class="col-md-4 mt-4">
                <div class="">

                    <h5 class=" ml-5">Quotation valid until:
                        {{ date('Y-m-d', strtotime($data[0]->tgl_penawaran . ' + 3 days')) }} </h5>
                </div>
                <div class="">

                    <h5 class=" ml-5">Prepared by: {{ $data[0]->nama_pengguna }} </h5>
                </div>

            </div>
        </div>


        <div class="row">
            <div class="col">
                <table class="table table-bordered  boder-5 border-dark text-center fw-bold" id="dataTable" width="100%"
                    cellspacing="0">

                    <tr>
                        <td colspan="9">INQUIRY</td>
                        <td colspan="8">QUOTATION</td>
                    </tr>
                    <tr>
                        <td>No</td>
                        <td>Tanggal</td>
                        <td>Job Number</td>
                        <td>Grade</td>
                        <td colspan="3">Material Size</td>
                        <td>QTY</td>
                        <td>Grade</td>
                        <td colspan="3">Material Size</td>
                        <td>QTY</td>
                        <td>WEIGHT(KG)</td>
                        <td>Unit Price</td>
                        <td>Amount</td>
                        <td>Procesing</td>



                    </tr>
                    @foreach ($data as $p)

                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td style="min-width:120px">
                                {{ $p->tgl_penawaran }}
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
                                {{ $p->jumlah }}
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
                                {{ $p->jumlah }}
                            </td>
                            <td>
                                {{ $p->berat }}
                            </td>
                            <td>
                                {{ 'Rp' . number_format($p->harga) }}
                            </td>
                            <td>
                                {{ 'Rp' . number_format($p->subtotal) }}
                            </td>
                            <td>
                                {{ $p->layanan }}
                            </td>
                        </tr>
                    @endforeach
                </table>

                <?php
                
                ?>
                <h5 class="text-end mb-5">
                    {{ 'Amount  : Rp' . number_format($p->subtotal) }} <br>
                    {{ ' Vat    : Rp' . number_format($p->ppn) }} <br>
                    {{ ' Total  : Rp' . number_format($p->total) }}</h5>


            </div>

        </div>



        <div class="row align-items-center mt-1 ">
            <div class="col-md-7">

                <h5>
                    Remark: <br>
                    Payment cash <br>
                    Please Mention our Quotation Number in your PO as a reference <br>
                    Stock availability valid until 7 days from quotation date <br>

                    <b>
                        Payment transfer <br>
                        PT IBARAKI KOGYO HANAN INDONESIA <br>
                        BANK MANDIRI KCP BEKASI <br>
                        KOTA DELTAMAS <br>
                        No Rekening : 156-00-1733899-9

                    </b>
                    <br> <br>
                    Phone Number : 0812-4422-2275 <br>
                    e-mail : sales@ibaraki.co.id


                </h5>

            </div>



        </div>

        <div class="row mt-5 ms-5">
            <div class="col text-strat">

                <h5 class="text-center">
                    PT KOGYO HANAN INDONESIA
                </h5>
                <div class="row">
                    <div class="col">
                        <h6 class="text-decoration-underline" style="margin-top: 120px">
                            Senior Sales Engineer



                        </h6>
                        <h6>
                            Muhammad Mulyadi Rizali
                        </h6>
                    </div>
                    <div class="col">
                        <h6 class="text-decoration-underline" style="margin-top: 120px">
                            Senior Sales Engineer



                        </h6>
                        <h6>
                            Muhammad Mulyadi Rizali
                        </h6>
                    </div>
                </div>
            </div>


            <div class="col text-center">

                <h5>
                    {{ $data[0]->nama_pelanggan }}
                </h5>
                <h6 class="text-decoration-underline" style="margin-top: 120px">
                    Customer



                </h6>
                <h6>
                   {{$data[0]->perwakilan}}
                </h6>
            </div>



        </div>

        {{-- <div class="row mt-4 ">
           

                <div class="col  mt-5 " style="margin-top:300px; ">
                    <h6 class="text-decoration-underline" style="margin-top:100px; ">
                        Senior Sales Engineer
                       
                    </h6>
                    <h6>
                         Muhammad Mulyadi Rizali
                    </h6>
                </div>

                <div class="col mt-5 " style="margin-top:300px; ">
                    <h6 class="text-decoration-underline" style="margin-top:100px; ">
                        Senior Sales Engineer
                       
                    </h6>
                    <h6>
                         Muhammad Mulyadi Rizali
                    </h6>
                </div>
            </div>



        </div> --}}


    </div>










    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>

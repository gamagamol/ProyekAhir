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
                        <h3 class="text-strat "> PT.Ibaraki Kogyo Hanan Indonesia</h3>
                    </div>
                    <div class="col">
                        <h3 class="text-end  "> Payment</h3>
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
                    Date : {{ $data[0]->tgl_tagihan }} <br><br>
                    NO Payment : {{ $data[0]->no_pembayaran }} <br>
                    NO Invoice : {{ $data[0]->no_tagihan }} <br><br>

                </h5>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col">
                <h4>
                    Payment Order From :
                </h4>
                <h5>
                    {{ $data[0]->perwakilan }} <br>
                    {{ $data[0]->nama_pelanggan }} <br>
                    {{ $data[0]->alamat_pelanggan }} <br>

                </h5>
            </div>
        </div>


        <div class="row">
            <div class="col">
                <table class="table table-bordered  boder-5 border-dark text-center fw-bold" id="dataTable"
                    width="100%" cellspacing="0">
                    <tr>
                        <td colspan="8">INQUIRY</td>
                        <td colspan="8">Sales</td>
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



                    </tr>
                    <?php
                    $subtotal = 0;
                    $ppn = 0;
                    $ongkir = 0;
                    $total = 0;
                    ?>
                    @foreach ($data as $p)
                        <?php
                        $subtotal += $p->subtotal;
                        $ppn += $p->ppn;
                        $ongkir += $p->ongkir;
                        $total += $p->total;
                        ?>

                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td style="min-width:120px">
                                {{ $p->tgl_tagihan }}
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
                        </tr>
                    @endforeach
                </table>


                <h5 class="text-end mb-5">
                    {{ 'Amount  : Rp' . number_format($subtotal) }} <br>
                    {{ ' Vat    : Rp' . number_format($ppn) }} <br>
                    {{ ' Shippment    : Rp' . number_format($data[0]->ongkir) }} <br>
                    {{ ' Total  : Rp' . number_format($total + $data[0]->ongkir) }}</h5>

                <h5 class="text-decoration-underline "> Terbilang:</h5>
                <h6> {{ $total_penyebut }} </h6>
            </div>

        </div>



        <div class="row align-items-center mt-4 ">
            <div class="col">
                <h4 class="text-decoration-underline">
                    Payment Transfer :
                </h4>
                <h4>
                    PT IBARAKI KOGYO HANAN INDONESIA <br>
                    BANK MANDIRI KCP BEKASI <br>
                    KOTA DELTAMAS <br>
                    No Rekening : 156-00-1733899-9
                </h4>

            </div>


            <div class="col text-end">

                <h4>
                    {{ 'Bekasi,' . date('Y-M-d') }}
                </h4>
                <br>
                <br>
                <br>
                <br>
                <h4 class="text-decoration-underline me-5">
                    Taufan
                </h4>
                <h4>Finance & Accounting</h4>
            </div>
        </div>


    </div>










    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>

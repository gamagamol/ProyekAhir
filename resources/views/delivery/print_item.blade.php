{{-- @dd($penerimaan) --}}
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
                        <h3 class="text-end  "> Delivery Order</h3>
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
                <h5 class=" ml-5">
                    Date : {{ $data[0]['tgl_pengiriman'] }} <br><br>
                    NO DO : {{ $data[0]['no_pengiriman'] }} <br><br>
                    NO PO : {{ $data[0]['no_penawaran'] }} <br>

                </h5>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col">
                <h4>
                    Delivery order for :
                </h4>
                <h5>
                    {{ $data[0]['perwakilan'] }} <br>
                    {{ $data[0]['nama_pelanggan'] }} <br>
                    {{ $data[0]['alamat_pelanggan'] }} <br>

                </h5>
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
                        <td>Date</td>
                        <td>Job Number</td>
                        <td>Grade</td>
                        <td colspan="3">Material Size</td>
                        <td>QTY</td>
                        <td>WEIGHT(KG)</td>
                        <td>Grade</td>
                        <td colspan="3">Material Size</td>
                        <td>QTY</td>
                        <td>WEIGHT(KG)</td>



                    </tr>
                    <?php $i = 0; ?>
                    @foreach ($data as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td style="min-width:120px">
                                {{ $p['tgl_pengiriman'] }}
                            </td>
                            <td>
                                {{ $p['nomor_pekerjaan'] }}
                            </td>

                            <td>
                                {{ $p['nama_produk' ]}}
                            </td>
                            <td>
                                {{ $p['tebal_produk'] }}
                            </td>
                            <td>
                                {{ $p['lebar_produk'] }}
                            </td>

                            <td>
                                {{ $p['panjang_produk'] }}
                            </td>
                            <td>
                                {{ $p['jumlah'] }}
                            </td>
                            <td>
                                {{ $p['berat'] }}
                            </td>

                            <td>
                                {{ $p['nama_produk'] }}
                            </td>
                            <td>
                                {{ $p['tebal_produk'] }}
                            </td>
                            <td>
                                {{ $p['lebar_produk'] }}
                            </td>
                            <td>
                                {{ $p['panjang_produk' ]}}
                            </td>
                            <td>
                                {{ $p['jumlah_penawaran'] }}
                            </td>
                            <td>
                                {{ $p['berat_penawaran'] }}
                            </td>
                        </tr>
                        <?php $i++; ?>
                    @endforeach
                </table>


            </div>
        </div>



        <div class="row align-items-center mt-4 ">
            <div class="col ">
                <h5>
                    Issued by,
                    <br><br><br><br>
                    (............)

                </h5>

            </div>
            <div class="col">
                <h5>

                    Delivery,
                    <br><br><br><br>
                    (............)
                </h5>
            </div>
            <div class="col">
                <h5>

                    Customer,
                    <br><br><br><br>
                    (............)
                </h5>
            </div>
        </div>
        <br>
        <br>
        <br>
        <br>
        <br>
        <div class="row">
            <div class="col">
                <a href="{{'delivery'}}" class="btn btn-primary">Next</a>

            </div>
        </div>
    </div>











    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>

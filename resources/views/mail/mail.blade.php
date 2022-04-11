<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <p>Dear <i>{{ $perwakilan }}</i> , <br> <br><br>

        Terima kasih kami sampaikan atas kepercayaan Bapak kepada kami untuk kami dapat mensupport sebagai supplier di
        <br>
        Perusahaan Bapak. <br> <br><br>

        Bersama ini kami informasikan outstanding payment invoice sebesar <i> {{ "Rp".number_format( $total) }} </i> . <br> <br><br>

        Email ini merupakan pemberitahuan kepada Bapak. Mohon abaikan pemberitahuan ini apabila Bapak sudah melakukan
        <br>
        pembayaran. Jika bapak belum melakukan pembayaran harap melakukan pembayaran sebelum tanggal {{$due_date}}<br> <br><br>

        Untuk pertanyaan atau saran, Bapak dapat menghubungi kami dan kami segera menindaklanjuti pertanyaan atau saran
        <br>
        yang disampaikan. <br> <br><br><br>


        Best Regards, <br> <br><br>


        Finance & Accounting <br> <br><br>

        PT IBARAKI KOGYO HANAN INDONESIA <br>
        Add: Jl Antilop VI Blok I - 2 No. 7 Jayamukti Cikarang Pusat, Kabupaten Bekasi 17350 <br>
        Tel: (021) 8932 6362 <br>
        Mobile / What's app: 0821-2308-0975 / 0813-8057-8075 / 0813-1423-2905 <br>
        Email : accounting@ibaraki.co.id; sales@ibaraki.co.id; ahmadsolihin@ibaraki.co.id

    </p>

    {{-- <div class="container">
        <div class="row">
            <div class="col">
                <table class="table table-bordered  boder-5 border-dark text-center fw-bold" id="dataTable" width="100%"
                    cellspacing="0" border="2">
                    <tr>
                        <td colspan="8">INQUIRY</td>
                        <td colspan="8">QUOTATION</td>
                    </tr>
                    <tr>
                        <td>No</td>
                        <td>Date</td>
                        <td>Job Number</td>
                        <td>Grade</td>
                        <td colspan="3">Material Size</td>
                        <td>QTY</td>
                        <td>Grade</td>
                        <td colspan="3">Material Size</td>
                        <td>QTY</td>
                        <td>WEIGHT(KG)</td>



                    </tr>
                    @foreach ($data as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td style="min-width:120px">
                                {{ $p->tgl_pengiriman }}
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
                        </tr>
                    @endforeach
                </table>


            </div>
        </div>
    </div> --}}



</body>

</html>

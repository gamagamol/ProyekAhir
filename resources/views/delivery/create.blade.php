@extends('template.index')
@section('content')
    {{-- @dd($data) --}}
    {{-- @dump($data) --}}
    <div class="container">
        @if (session()->has('failed'))
            <div class="alert alert-danger" role="alert">
                {{ session('failed') }}
            </div>
        @endif
        <div class="card shadow mb-4">
            <div class="card-header py-3 mt-2">
                <h6 class="m-0 font-weight-bold text-primary">Delivery Order</h6>
            </div>
            <form action={{ url('delivery') }} method="post">

                <div class="container">
                    <div class="row">
                        <div class="col-md-3 mt-2">
                            <input type="date" name="tgl_pengiriman" class="form-control"
                                value={{ $data[0]->tgl_penerimaan }}>
                            <input type="text" name="no_penerimaan" value="{{ $data[0]->no_penerimaan }}" hidden>



                        </div>
                        <div class="col-md-3 mt-3 ml-4">
                            <input class="form-check-input" type="checkbox" value="" id="select_all">
                            <label class="form-check-label" for="flexCheckDefault">
                                Select All
                            </label>

                        </div>

                    </div>

                    <div class="row">

                        <div class="col mt-5">
                            <div class="table-responsive text-center">
                                <table class="table table-bordered" cellspacing="0" id="CreateSupplier" hidden>
                                    <tr>
                                        <td style="width: 1%;">No</td>
                                        <td hidden>id produk</td>
                                        <td style="width: 20%;">No Sales</td>
                                        <td style="width: 15%;">Grade</td>
                                        <td style="width: 15%;">QTY</td>
                                    </tr>

                                </table>
                            </div>
                        </div>


                    </div>

                </div>
                <div class="card-body">




                    <div class="table-responsive text-center">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

                            <tr>
                                <td>No</td>
                                <td>Date</td>
                                <td>No Quotation</td>
                                <td>Job number</td>
                                <td>Grade</td>
                                <td colspan="3">Material Size</td>
                                <td class="text-wrap" style="width: 10%">QTY
                                    (Goods ) </td>
                                <td>QTY (Delivery) </td>
                                <td>customer</td>
                                <td>Unit</td>
                            </tr>
                            @csrf
                            <?php $i = 1; ?>
                            <input type="text" name="select_all" id="fill_sa" hidden>

                            @foreach ($data as $d)
                                <tr>

                                    <td>
                                        <input type="checkbox" value={{ (int) $d->id_transaksi }} name="id_transaksi[]"
                                            id="select{{ $loop->iteration }}">
                                    </td>
                                    <td style="min-width:120px">{{ $d->tgl_penerimaan }}</td>
                                    <td>{{ $d->no_penerimaan }}</td>
                                    <td>{{ $d->nomor_pekerjaan }}</td>
                                    <td>{{ $d->nama_produk }}</td>
                                    <td>
                                        <?=  $d->tebal_penawaran ?>
                                    </td>
                                    <td>
                                        {{$d->lebar_penawaran }}
                                    </td>
                                    <td>
                                        {{$d->lebar_penawaran }}
                                    </td>
                                    <td>{{ $d->jumlah_detail_penerimaan }}</td>
                                    <td>{{ $d->jumlah_detail_pengiriman }}</td>
                                    <td>{{ $d->nama_pelanggan }}</td>
                                    <td id={{ "ClickCreateSupplier$i" }}>
                                        <i class="fa fa-plus-circle" aria-hidden="true"
                                            onclick="CreateSupplier('{{ $d->id_produk }}',
                                                    '{{ $d->nama_produk }}',
                                                    '{{ $d->no_penerimaan }}',
                                                    '{{ $d->id_transaksi }}',
                                                    '{{ $d->id_penawaran }}',
                                                    '{{ $d->id_penerimaan_barang }}',
                                                    '{{ $d->tebal_transaksi }}',
                                                    '{{ $d->lebar_transaksi }}',
                                                    '{{ $d->panjang_transaksi }}',
                                                    '{{ $d->bentuk_produk }}',
                                                    '{{ $d->layanan }}',
                                                    '{{ $d->jumlah_detail_penerimaan }}',
                                                    '{{ $d->harga }}',

                                                  
                                                    
                                                    )"
                                            id={{ "IconClickCreateSupplier$i" }}></i>

                                    </td>



                                </tr>
                                <?php $i++; ?>
                            @endforeach
                            <input type="text" value="{{ count($data) }}" hidden id="lenght_data">
                        </table>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col">
                                {{-- <button type=submit name=submit class="btn btn-primary">submit</button> --}}
                                <p href="" class="btn btn-primary mt-3" onclick="MoveCreate()">Submit</p>

                                <a href={{ url('goods') }} class="btn btn-primary">Back</a>
                            </div>
                        </div>
                    </div>
                    {{-- modal --}}
                    <div id="modal" class="modal fade">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Delivery</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure to continue the transaction? please check the details first! </p>
                                    <button type=submit name=submit class="btn btn-primary">submit</button>
                                    <a href="{{ url('goods/detail', str_replace('/', '-', $d->no_penerimaan)) }}"
                                        class="btn btn-info mt-1">
                                        Detail </a>

                                </div>
                            </div>
                        </div>
                    </div>
            </form>
        </div>
    </div>
    </div>




    <script>
        $(document).ready(function() {
            //    cheked
            $('#select_all').click(function(event) {
                let select = $('#lenght_data').val();
                for (let i = 1; i <= select; i++) {
                    if ($('#select' + i).is(":checked")) {
                        $('#select' + i).prop('checked', false);
                        $('#fill_sa').removeAttr('value')

                    } else {

                        $('#select' + i).prop('checked', true);
                        $('#fill_sa').attr('value', 'select_all')

                    }

                }


            });

            // end cheked

        });

        function CreateSupplier(IdProduk, NamaProduk, NoPenerimaan, IdTransaksi, IdPenawaran, IdPenerimaanBarang,
            TebalTransaksi, LebarTransaksi, PanjangTransaksi, BentukProduk, Layanan, JumlahDetailPenerimaan) {
            let click = 1;


            let html = ``
            html += `<tr>`
            html += `<td>${click}</td>`
            html +=
                `<td> <input type="text" name="no_penerimaan" class="form-control" value='${NoPenerimaan}' readonly  style="border-width:0px;background-color:white;"></td>`
            html +=
                `<td> <input type="text" name="nama_produk[]" class="form-control" value='${NamaProduk}' readonly size="3" style="border-width:0px;background-color:white;"></td>`
            html +=
                `<td> <input type="number" name="unit[]" class="form-control" size="3" placeholder="Unit" min='0'></td>`
            html +=
                `<td hidden><input type="text" name="id_produk[]" class="form-control text-center" value='${IdProduk}' readonly  style="border-width:0px;background-color:white;width: 105%;" ></td>`
            html +=
                `<td hidden ><input type="text" name="id_transaksi[]" class="form-control text-center" value='${IdTransaksi}' readonly size="3" style="border-width:0px;background-color:white;" ></td>`
            html +=
                `<td hidden ><input type="text" name="id_penawaran[]" class="form-control text-center" value='${IdPenawaran}' readonly size="3" style="border-width:0px;background-color:white;" ></td>`
            html +=
                `<td hidden ><input type="text" name="id_penerimaan_barang[]" class="form-control text-center" value='${IdPenerimaanBarang}' readonly size="3" style="border-width:0px;background-color:white;" ></td>`
            html +=
                `<td hidden ><input type="text" name="tebal_transaksi[]" class="form-control text-center" value='${TebalTransaksi}' readonly size="3" style="border-width:0px;background-color:white;" ></td>`
            html +=
                `<td hidden ><input type="text" name="lebar_transaksi[]" class="form-control text-center" value='${LebarTransaksi}' readonly size="3" style="border-width:0px;background-color:white;" ></td>`
            html +=
                `<td hidden ><input type="text" name="panjang_transaksi[]" class="form-control text-center" value='${PanjangTransaksi}' readonly size="3" style="border-width:0px;background-color:white;" ></td>`
            html +=
                `<td hidden ><input type="text" name="bentuk_produk[]" class="form-control text-center" value='${BentukProduk}' readonly size="3" style="border-width:0px;background-color:white;" ></td>`
            html +=
                `<td hidden ><input type="text" name="layanan[]" class="form-control text-center" value='${Layanan}' readonly size="3" style="border-width:0px;background-color:white;" ></td>`
            html +=
                `<td hidden ><input type="text" name="jumlah_detail_penerimaan[]" class="form-control text-center" value='${JumlahDetailPenerimaan}' readonly size="3" style="border-width:0px;background-color:white;" ></td>`



            html += `</tr>`

            $('#CreateSupplier').append(html)

            $('#CreateSupplier').removeAttr('hidden');

            click++
            console.log(click);
            if (click > 2) {
                $('#ClickCreateSupplier').click(
                    () => {
                        console.log(click);
                        $('#ClickCreateSupplier1').attr('hidden', true)
                    }

                )
            }
        }


        function MoveCreate() {
            $('#modal').modal('show');
        }
    </script>
@endsection()

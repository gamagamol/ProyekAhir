@extends('template.index')
@section('content')
    <div class="container">
        @if (session()->has('failed'))
            <div class="alert alert-danger" role="alert">
                {{ session('failed') }}
            </div>
        @endif
        <div class="card shadow mb-4">
            <div class="card-header py-3 mt-2">
                <h6 class="m-0 font-weight-bold text-primary">Create Goods Receipt</h6>
            </div>
            <form action={{ url('goods') }} method="post">
                @csrf
                <div class="container">
                    <div class="row">
                        <div class="col-md-3 mt-2">
                            <input type="date" name="tgl_penerimaan" class="form-control"
                                value={{ $data[0]->tgl_pembelian }}>
                            <input type="text" value="{{ $data[0]->kode_transaksi }}" name="kode_transaksi" hidden>
                            <input type="text" value="{{ $data[0]->no_pembelian }}" name="no_pembelian" hidden>

                        </div>
                        {{-- <div class="col-md-3 mt-3" id="MultiSupplier">
                            <i class="fa fa-plus-circle" aria-hidden="true" onclick="MultiSupplier()"></i>
                        </div> --}}
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
                                <td colspan="8">Quotation</td>
                                <td colspan="13">Purchase</td>
                            </tr>
                            <tr>
                                <td>Date</td>
                                <td>No Transaction</td>
                                <td>Job number</td>
                                <td>Grade</td>
                                <td colspan="3">Material Size</td>
                                <td>QTY</td>
                                <td>Grade</td>
                                <td colspan="3">Material Size</td>
                                <td>QTY</td>
                                <td>Weight(Kg)</td>
                                <td>Unit Price</td>
                                <td>Amount</td>
                                <td>VAT 11%</td>
                                <td>Total Amount</td>
                                <td>Processing</td>
                                <td>Custumor</td>
                                <td>Supplier</td>
                                {{-- <td >Unit</td> --}}

                            </tr>

                            @foreach ($data as $p)
                                <?php
                                
                                $jumlah = $p->jumlah_detail_pembelian;
                                $berat = $p->berat;
                                $subtotal = $p->subtotal;
                                $ppn = $p->ppn;
                                $total = $p->total;
                                
                                ?>

                                <tr>

                                    <td style="min-width:120px">
                                        {{ $p->tgl_pembelian }}
                                    </td>
                                    <td>
                                        {{ $p->no_pembelian }}
                                    </td>
                                    <td>
                                        {{ $p->nomor_pekerjaan }}
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
                                        {{ $jumlah }}
                                    </td>

                                    <td>
                                        {{ $p->nama_produk }}
                                    </td>
                                    <td>
                                        <?= $p->tebal_detail_pembelian ? $p->tebal_detail_pembelian : $p->tebal_penawaran ?>
                                    </td>
                                    <td>
                                        {{ $p->lebar_detail_pembelian ? $p->lebar_detail_pembelian : $p->lebar_penawaran }}
                                    </td>
                                    <td>
                                        {{ $p->lebar_detail_pembelian ? $p->lebar_detail_pembelian : $p->lebar_penawaran }}
                                    </td>
                                    <td>
                                        {{ $jumlah }}
                                    </td>
                                    <td>
                                        {{ $p->berat_detail_pembelian }}
                                    </td>
                                    <td>
                                        {{ 'Rp' . number_format($p->harga_detail_pembelian) }}
                                    </td>

                                    <td>
                                        {{ 'Rp' . number_format($p->subtotal_detail_pembelian) }}
                                    </td>
                                    <td>
                                        {{ 'Rp' . number_format($p->ppn_detail_pembelian) }}

                                    </td>
                                    <td>
                                        {{ 'Rp' . number_format($p->total_detail_pembelian) }}
                                    </td>
                                    <td>
                                        {{ $p->layanan }}
                                    </td>
                                    <td>
                                        {{ $p->nama_pelanggan }}
                                    </td>
                                    <td>
                                        {{ $p->nama_pemasok }}
                                    </td>




                                </tr>
                            @endforeach

                        </table>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col">
                                {{-- <button type=submit name=submit class="btn btn-primary">submit</button> --}}
                                <p href="" class="btn btn-primary mt-3" onclick="MoveCreate()">Submit</p>
                                <a href="{{ url('purchase') }}" class="btn btn-primary">back</a>
                            </div>
                        </div>
                    </div>
                    {{-- modal --}}
                    <div id="modal" class="modal fade">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Goods Receipt</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure to continue the transaction? please check the details first! </p>
                                    <button type=submit name=submit class="btn btn-primary">submit</button>
                                    <a href="{{ url('purchase/detail', str_replace('/', '-', $p->no_pembelian)) }}"
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
        let click = 1

        function CreateSupplier(IdProduk, NamaProduk, NoPembelian, IdTransaksi) {


            let html = ``
            html += `<tr>`
            html += `<td>${click}</td>`
            html +=
                `<td> <input type="text" name="no_pembelian" class="form-control" value='${NoPembelian}' readonly  style="border-width:0px;background-color:white;"></td>`
            html +=
                `<td> <input type="text" name="nama_produk[]" class="form-control" value='${NamaProduk}' readonly size="3" style="border-width:0px;background-color:white;"></td>`
            html +=
                `<td> <input type="number" name="unit[]" class="form-control" size="3" placeholder="Unit" min='0'></td>`
            html +=
                `<td hidden><input type="text" name="id_produk[]" class="form-control text-center" value='${IdProduk}' readonly  style="border-width:0px;background-color:white;width: 105%;" ></td>`
            html +=
                `<td hidden ><input type="text" name="id_transaksi[]" class="form-control text-center" value='${IdTransaksi}' readonly size="3" style="border-width:0px;background-color:white;" ></td>`



            html += `</tr>`

            $('#CreateSupplier').append(html)

            $('#CreateSupplier').removeAttr('hidden');

            click++
        }

        function MoveCreate() {
            $('#modal').modal('show');
        }

        // function MultiSupplier() {
        //     $('#CTS').removeAttr('hidden');
        //     $('#RTS').removeAttr('hidden');
        //     // $('#MultiSupplier').addattr('hidden');
        //     // $('#MultiSupplier').attr('hidden', 'true');




        // }
    </script>
@endsection()

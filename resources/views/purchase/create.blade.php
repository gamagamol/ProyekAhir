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
                <h6 class="m-0 font-weight-bold text-primary">Create Purchase Order</h6>
            </div>
            <form action={{ url('purchase') }} method="post">

                <div class="container">
                    <div class="row">
                        <div class="col-md-3 mt-2">
                            <input type="date" name="tgl_pembelian" class="form-control"
                                value={{ $data[0]->tgl_penjualan }}>
                            <input type="text" value="{{ $data[0]->kode_transaksi }}" name="kode_transaksi" hidden>

                        </div>
                        {{-- jangan hapus dulu penting --}}
                        <div class="col-md-3 mt-2">
                            <select class="form-control @error('id_pemasok') is-invalid @enderror" id="id_pemasok"
                                name="id_pemasok" onchange="drop()" value="{{ old('id_pemasok') }}">


                                <option value={{ null }}>Select Your Supplier</option>
                                @foreach ($supplier as $p)
                                    <option value='{{ $p->id_pemasok }}'>
                                        {{ $p->nama_pemasok }}</option>
                                @endforeach
                            </select>
                            @error('id_pemasok')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>
                        <div class="col-md-3 mt-3" id="MultiSupplier">
                            <i class="fa fa-plus-circle" aria-hidden="true" onclick="MultiSupplier()"></i>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col mt-5">
                            <div class="table-responsive text-center">
                                <table class="table table-bordered" width="100%" cellspacing="0" id="CreateSupplier" hidden>
                                    <tr>
                                        <td>No</td>
                                        <td hidden>id produk</td>
                                        <td>No Sales</td>
                                        <td>Grade</td>
                                        <td colspan="3">Material Size</td>
                                        <td>Price</td>
                                        <td>QTY</td>
                                        <td>supplier</td>
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
                                <td>No Sales</td>
                                <td>Job number</td>
                                <td>Grade</td>
                                <td colspan="3">Material Size</td>
                                <td>QTY</td>
                                <td>Weight(Kg)</td>
                                <td>Unit Price</td>
                                <td>Shipment</td>
                                <td>Amount</td>
                                <td>VAT 10%</td>
                                <td>Total Amount</td>
                                <td>Processing</td>
                                <td>customer</td>
                                <td hidden id="RTS">Supplier</td>



                            </tr>
                            @csrf
                            <?php $i = 1; ?>
                            @foreach ($data as $d)
                                <tr>

                                    <td>{{ $loop->iteration }}</td>
                                    <td style="min-width:120px">{{ $d->tgl_penjualan }}</td>
                                    <td>{{ $d->no_penjualan }}</td>
                                    <td>{{ $d->nomor_pekerjaan }}</td>
                                    <td>{{ $d->nama_produk }}</td>
                                    <td>{{ $d->tebal_transaksi }}</td>
                                    <td>{{ $d->lebar_transaksi }}</td>
                                    <td>{{ $d->panjang_transaksi }}</td>
                                    <td>{{ $d->jumlah }}</td>
                                    <td>{{ $d->berat }}</td>
                                    <td>{{ 'Rp.' . number_format($d->harga) }}</td>
                                    <td>{{ 'Rp.' . number_format($d->ongkir) }}</td>
                                    <td>{{ 'Rp.' . number_format($d->subtotal) }}</td>
                                    <td>{{ 'Rp.' . number_format($d->ppn) }}</td>
                                    <td>{{ 'Rp.' . number_format($d->total) }}</td>
                                    <td>{{ $d->layanan }}</td>
                                    <td>{{ $d->nama_pelanggan }}</td>
                                    <td hidden id="CTS">
                                        <i class="fa fa-plus-circle" aria-hidden="true"
                                            onclick="CreateSupplier('{{ $d->id_produk }}','{{ $d->nama_produk }}','{{ $d->no_penjualan }}','{{ $d->id_transaksi }}','{{ $d->tebal_transaksi }}','{{ $d->lebar_transaksi }}','{{ $d->panjang_transaksi }}','{{ $d->bentuk_produk }}','{{ $d->layanan }}')"></i>

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

                                <a href=" {{ url('sales') }}" class="btn btn-primary">back</a>
                            </div>
                        </div>
                    </div>
                    {{-- modal --}}
                    <div id="modal" class="modal fade">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Sales</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure to continue the transaction? please check the details first! </p>
                                    <button type=submit name=submit class="btn btn-primary ">submit</button>
                                    <a href="{{ url('sales/detail', str_replace('/', '-', $d->no_penjualan)) }}"
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

        function CreateSupplier(IdProduk, NamaProduk, NoPenjualan, IdTransaksi, TebalTransaksi, LebarTransaksi,
            PanjangTransaksi, BentukProduk, Layanan) {
            let Supplier = {!! json_encode($supplier->toArray(), JSON_HEX_TAG) !!}


            let html = ``
            html += `<tr>`
            html += `<td>${click}</td>`
            html +=
                `<td> <input type="text" name="no_penjualan[]" class="form-control" value='${NoPenjualan}' readonly size="3" style="border-width:0px;background-color:white;"></td>`
            html +=
                `<td> <input type="text" name="nama_produk[]" class="form-control" value='${NamaProduk}' readonly size="3" style="border-width:0px;background-color:white;"></td>`
            html +=
                `<td> <input type="text" name="tebal_transaksi[]" class="form-control" value='${TebalTransaksi}' readonly size="3" style="border-width:0px;background-color:white;"></td>`
            html +=
                `<td> <input type="text" name="lebar_transaksi[]" class="form-control" value='${LebarTransaksi}' readonly size="3" style="border-width:0px;background-color:white;"></td>`
            html +=
                `<td> <input type="text" name="panjang_transaksi[]" class="form-control text-center" value='${PanjangTransaksi}' readonly size="3" style="border-width:0px;background-color:white;"></td>`
            html +=
                `<td> <input type="number" name="harga[]" class="form-control" size="4" placeholder="Price" min='0'></td>`
            html +=
                `<td> <input type="number" name="unit[]" class="form-control" size="3" placeholder="Unit" min='0'></td>`

            html += `<td> <select class='form-control ' id='id_pemasok' name='id_pemasok[]'>`
            html += ` <option value=null>Select Your Supplier</option>`

            for (let i = 0; i < Supplier.length; i++) {
                html += ` <option value=${Supplier[i].id_pemasok} size="1">${Supplier[i].nama_pemasok}</option>`
            }
            html += `</select></td>`

            html +=
                `<td hidden><input type="text" name="id_produk[]" class="form-control text-center" value='${IdProduk}' readonly size="3" style="border-width:0px;background-color:white;" ></td>`
            html +=
                `<td hidden ><input type="text" name="id_transaksi[]" class="form-control text-center" value='${IdTransaksi}' readonly size="3" style="border-width:0px;background-color:white;" ></td>`
            html +=
                `<td hidden ><input type="text" name="bentuk_produk[]" class="form-control text-center" value='${BentukProduk}' readonly size="3" style="border-width:0px;background-color:white;" ></td>`
            html +=
                `<td hidden ><input type="text" name="layanan[]" class="form-control text-center" value='${Layanan}' readonly size="3" style="border-width:0px;background-color:white;" ></td>`

            html += `</tr>`

            $('#CreateSupplier').append(html)

            $('#CreateSupplier').removeAttr('hidden');

            click++
        }

        function MoveCreate() {
            $('#modal').modal('show');
        }

        function MultiSupplier(){
            $('#CTS').removeAttr('hidden');
            $('#RTS').removeAttr('hidden');
            // $('#MultiSupplier').addattr('hidden');
            $('#MultiSupplier').attr('hidden', 'true');
            



        }
    </script>
@endsection()

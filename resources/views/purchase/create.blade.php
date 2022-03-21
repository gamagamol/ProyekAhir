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
                        {{-- <div class="col-md-3 mt-2">
                            <select
                                class="form-control @error('id_pemasok')
                                    is-invalid  
                                @enderror"
                                id="id_pemasok" name="id_pemasok" onchange="drop()" value="{{ old('id_pemasok') }}">


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

                        </div> --}}
                    </div>

                    <div class="row" id="CreateSupplier">




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
                                <td>Supplier</td>



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
                                    <td>
                                        <i class="fa fa-plus-circle" aria-hidden="true"
                                            onclick="CreateSupplier('{{ $d->id_produk }}','{{ $d->nama_produk }}','{{ $d->no_penjualan }}')"></i>

                                    </td>



                                </tr>
                            @endforeach
                        </table>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col">
                                <button type=submit name=submit class="btn btn-primary">submit</button>
                                <a href="{{ url('sales') }}" class="btn btn-primary">back</a>
                            </div>
                        </div>
                    </div>
            </form>
        </div>
    </div>
    </div>


    <script>
        function CreateSupplier(IdProduk, NamaProduk, NoPenjualan) {
            let Supplier = {!! json_encode($supplier->toArray(), JSON_HEX_TAG) !!}
            let html = ``
            html += `<div class='col-md-3 mt-3'>
        <select class='form-control ' id='id_pemasok' name='id_pemasok[]'>`
            html += ` <option value=null>Select Your Supplier</option>`

            for (let i = 0; i < Supplier.length; i++) {
                html += ` <option value=${Supplier[i].id_pemasok}>${Supplier[i].nama_pemasok}</option>`
            }
            html += `</select></div>`
            $('#CreateSupplier').append(`
             
            <div class="col-md-2">
                            <input type="text" name="no_penjualan[]" class="form-control mt-3" value='${NoPenjualan}' readonly>
            </div>
             <div class="col-md-2">
                            <input type="text" name="nama_produk[]" class="form-control mt-3" placeholder="Unit" value='${NamaProduk}' readonly>
            </div>
 
            </div>
        <div class="col-md-1">
             <input type="text" name="unit[]" class="form-control mt-3" placeholder="Unit">
            </div>

        ${html}
<div class="col-sm-3">
                            <input type="text" name="id_produk[]" class="form-control mt-3" value='${IdProduk}' hidden>
            </div>
                        `);


        }
    </script>
@endsection()

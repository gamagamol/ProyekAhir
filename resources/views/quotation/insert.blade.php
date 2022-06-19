@extends('template.index')
@section('content')
    @if (session()->has('failed'))
        <div class="alert alert-danger" role="alert">
            {{ session('failed') }}
        </div>
    @endif
    {{-- @dd($pembantu) --}}
    <div class="container  ">
        <div class="card shadow mb-4 ml-4 mr-4">
            <div class="card-header py-3 mb-2 ">
                <h6 class="m-0 font-weight-bold text-primary">Add Quotation</h6>
            </div>

            <div class="container mt-2">
                <div id="table_atas">
                    <form action="{{ url('quotation') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-3 ">
                                <div class="form-group mt-2 rounded">
                                    <label for="example1" class="mt-2">Transaction Date </label>
                                    <input type="date" class="form-control @error('tgl_penawaran') is-invalid @enderror"
                                        name="tgl_penawaran" id="tgl_penawaran"
                                        @if (count($pembantu) > 0) readonly value={{ $pembantu[0]->tgl_pembantu }} @else value={{ date('Y-m-d') }} @endif>

                                    @error('tgl_penawaran')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3 ">
                                <div class="form-group mt-2 rounded">
                                    <label for="example1" class="mt-2">Transaction Code </label>
                                    <input type="text" class="form-control @error('kode_transaksi') is-invalid @enderror"
                                        name="kode_transaksi" id="kode_transaksi" value={{ $kode_transaksi }} readonly
                                        @if (count($pembantu) > 0) readonly value={{ $pembantu[0]->kode_transaksi }} @endif>

                                    @error('kode_transaksi')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-md-3 ">
                                <div class="form-group mt-2 rounded">
                                    <label for="example1" class="mt-2">Job number</label>
                                    <input type="text" class="form-control @error('nomor_pekerjaan') is-invalid @enderror"
                                        name="nomor_pekerjaan" id="nomor_pekerjaan" value={{ old('nomor_pekerjaan') }}
                                        @if (count($pembantu) > 0) {{ $pembantu[0]->nomor_pekerjaan }} readonly @endif>

                                    @error('nomor_pekerjaan')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3 mt-3">
                                <div class="form-group">
                                    <label for="id_pelanggan">Customer</label>
                                    <select class="form-control @error('id_pelanggan') is-invalid @enderror"
                                        id="id_pelanggan" name="id_pelanggan" value="{{ old('id_pelanggan') }}">
                                        @if (count($pembantu) > 0)
                                            <option value={{ $pembantu[0]->id_pelanggan }}>{{ $nama_pelanggan }}
                                            </option>
                                        @else
                                            <option value={{ null }}>Select Your Customer</option>

                                            @foreach ($pelanggan as $p)
                                                <option value="{{ $p->id_pelanggan }}">{{ $p->nama_pelanggan }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>

                                    @error('id_pelanggan')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>



                        </div>

                        <div class="row">

                            <div class="col-md-3 mt-3">
                                <div class="form-group">
                                    <label for="id_produk">Product</label>
                                    <select class="form-control @error('id_produk') is-invalid @enderror" id="id_produk"
                                        name="id_produk" onchange="drop()" value="{{ old('id_produk') }}">


                                        <option value={{ null }}>Select Your product</option>
                                        @foreach ($produk as $p)
                                            <option value='{{ "$p->nama_produk|$p->bentuk_produk" }}'>
                                                {{ $p->nama_produk }}</option>
                                        @endforeach
                                    </select>
                                    @error('id_produk')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mt-2 rounded">
                                    <label for="example1" class="mt-2" id="tebal_label">Inquiry
                                        thick/Diameter</label>
                                    <input type="number" class="form-control @error('tebal_transaksi') is-invalid @enderror"
                                        name="tebal_transaksi" id="tebal_transaksi" value="{{ old('tebal_transaksi') }}"
                                        min="0">
                                    @error('tebal_transaksi')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3 ">
                                <div class="form-group mt-2 rounded" id="lebar">
                                    <label for="example1" class="mt-2">Inquiry Widht</label>
                                    <input type="number" class="form-control @error('lebar_transaksi') is-invalid @enderror"
                                        name="lebar_transaksi" id="lebar_transaksi" value="{{ old('lebar_transaksi') }}"
                                        min="0">
                                    @error('lebar_transaksi')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mt-2 rounded">
                                    <label for="example1" class="mt-2"> Inquiry Length</label>
                                    <input type="number"
                                        class="form-control @error('panjang_transaksi') is-invalid @enderror"
                                        name="panjang_transaksi" id="panjang_transaksi"
                                        value="{{ old('panjang_transaksi') }}" min="0">
                                    @error('panjang_transaksi')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>


                        </div>

                        <div class="row">
                            <div class="col-md-3 ">
                                <div class="form-group mt-2 rounded">
                                    <label for="example1" class="mt-2">Inquiry QTY</label>
                                    <input type="number" class="form-control @error('jumlah') is-invalid @enderror"
                                        name="jumlah" id="jumlah" value="{{ old('jumlah') }}" min="0">
                                    @error('jumlah')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3 mt-3">
                                <div class="form-group">
                                    <label for="layanan">Processing</label>
                                    <select class="form-control @error('layanan') is-invalid @enderror" id="layanan"
                                        name="layanan">

                                        @foreach ($services as $service)
                                            <option value= {{strtoupper($service->nama_layanan)}}>
                                            {{$service->nama_layanan}}
                                            </option>
                                        @endforeach


                                    </select>
                                    @error('layanan')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>



                            <div class="col-md-3 ">
                                <div class="form-group mt-2 rounded">
                                    <label for="example1" class="mt-2">Unit Price</label>
                                    <input type="text" class="form-control @error('harga') is-invalid @enderror"
                                        name="harga" id="harga" value="{{ old('harga') }}" min="0">
                                    @error('harga')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group mt-2 rounded">
                                    <label for="example1" class="mt-2">Shipment</label>
                                    <input type="text" class="form-control @error('ongkir') is-invalid @enderror"
                                        name="ongkir" id="ongkir"   
                                         @if (count($pembantu) > 0) value= {{$pembantu[0]->ongkir_pembantu }} readonly @endif
                                        value="{{ old('ongkir') }}" min="0"
                                       >
                                    @error('ongkir')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>




                        </div>
                        <div class="row">
                            <input type="text" name="id" id="id" value="{{ Auth::user()->id }}" hidden>

                            <div class="col-md-3 " style="margin-top:35px">
                                <div class="form-group mt-2 rounded">
                                    <button type=submit name=tambah class="btn btn-primary" id="tambah">Add Item</button>

                                    <a class="btn btn-primary ml-3" id="selesai" onclick="selesai()">finished</a>




                    </form>
                </div>
            </div>

        </div>
    </div>



    <h4 class="text-start mt-2 mb-2">Data Inputan</h4>

    <div class="table-responsive text-center mt-2">
        <form action={{ url('quotation_insert') }} method="POST">
            @csrf

            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

                <tr>
                    <td>No</td>
                    <td>Date</td>
                    <td>Job number</td>
                    <td>Grade</td>
                    <td colspan="3">Material Size</td>
                    <td>QTY</td>
                    <td>Grade</td>
                    <td colspan="3">Material Size</td>
                    <td>QTY</td>
                    <td>Weight(Kg)</td>
                    <td>Unit Price</td>
                    <td>Shipment</td>
                    <td>Amount</td>
                    <td>VAT 11%</td>
                    <td>Total Amount</td>
                    <td>Processing</td>
                    <td>Customer</td>
                    <td>Delete</td>

                </tr>
                @if ($pembantu)
                    <?php $i = 1;
                    $total = 0; ?>
                    @foreach ($pembantu as $p)
                        <tr>

                            <input type="text"
                                value="{{ "$p->kode_transaksi|$p->tgl_pembantu|$p->nomor_pekerjaan|$p->nama_produk|$p->tebal_pembantu |$p->lebar_pembantu| $p->panjang_pembantu|$p->jumlah_pembantu|$p->nama_produk|$p->tebal_penawaran|$p->lebar_penawaran|$p->panjang_penawaran|$p->jumlah_pembantu|$p->berat_pembantu|$p->harga_pembantu|$p->ongkir_pembantu|$p->subtotal|$p->ppn|$p->total|$p->layanan_pembantu|$p->id_pelanggan|$p->id_user" }}"
                                name={{ "elemen$i" }} hidden>

                            <td>
                                {{ $loop->iteration }}
                            </td>
                            <td style="min-width:120px">
                                {{ $p->tgl_pembantu }}
                            </td>
                            <td>
                                {{ $p->nomor_pekerjaan }}
                            </td>
                            <td>
                                {{ $p->nama_produk }}
                            </td>
                            <td>
                                {{ $p->tebal_pembantu }}
                            </td>
                            <td>
                                {{ $p->lebar_pembantu }}
                            </td>

                            <td>
                                {{ $p->panjang_pembantu }}
                            </td>
                            <td>
                                {{ $p->jumlah_pembantu }}
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
                                {{ $p->jumlah_pembantu }}
                            </td>
                            <td>
                                {{ $p->berat_pembantu }}
                            </td>
                            <td>
                                {{ 'Rp' . number_format($p->harga_pembantu) }}
                            </td>
                            <td>
                                {{ 'Rp' . number_format($p->ongkir_pembantu) }}
                            </td>
                            <td>
                                {{ 'Rp' . number_format($p->subtotal) }}
                            </td>
                            <td>
                                {{ 'Rp' . number_format($p->ppn) }}

                            </td>
                            <td>
                                {{ 'Rp' . number_format($p->total) }}
                            </td>
                            <td>
                                {{ $p->layanan_pembantu }}
                            </td>
                            <td>
                                {{ $nama_pelanggan }}
                            </td>

                            <td>
                                <a href={{ url('deleteq', $p->id_pembantu) }} class="btn btn-danger">Delete</a>
                            </td>



                        </tr>
                        <?php $i++; ?>
                        <?php $total = $total + $p->total; ?>
                    @endforeach
                    @if (count($pembantu)>0)
                        <?php $total+=$pembantu[0]->ongkir_pembantu?>
                    @endif
             
                    <tr>
                        <td colspan='18'>Total Quotation</td>
                        <td>{{ 'Rp' . number_format($total) }}</td>
                    </tr>
                @endif

            </table>
    </div>
    <button class="btn btn-primary text-start mt-2" name="submit" id="submit" hidden data-toggle="modal"
        data-target="#sales">submit</button>

    </form>
    <a href="{{ url('quotation') }}" class="btn btn-primary  mb-4 ml-3" style="margin-top: 30px">Back</a>






    <h4 class="text-start mt-3">History Customer</h4>
    <div class="container mt-2">
        <div>
            <form action="" method="get">
                @csrf

                <div class="row">
                    <div class="col-md-3 mt-3">
                        <div class="form-group">
                            <select class="form-control @error('id_pelanggan') is-invalid @enderror" id="id_pelanggan"
                                name="id_pelanggan" value="{{ old('id_pelanggan') }}">
                                @foreach ($pelanggan as $p)
                                    <option value="{{ $p->id_pelanggan }}">{{ $p->nama_pelanggan }}
                                    </option>
                                @endforeach
                            </select>

                            @error('id_pelanggan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>


                    <div class="col-md-3 mt-3">
                        <button type=submit name=submit class="btn btn-primary">submit</button>
                    </div>





                </div>



            </form>
        </div>
    </div>
    <div class="table-responsive text-center mb-3">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

            <tr>
                <td>No</td>
                <td>Date</td>
                <td>No Quotation</td>
                <td>Job number</td>
                <td>Grade</td>
                <td colspan="3">Material Size</td>
                <td>QTY</td>
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

                @if (is_countable($history) > 0)
                    @foreach ($history as $h)
            <tr>
                <td> {{ $loop->iteration }}</td>
                <td style="min-width:120px">{{ $h->tgl_penawaran }}</td>
                <td>{{ $h->no_penawaran }}</td>
                <td>{{ $h->nomor_pekerjaan }}</td>
                <td>{{ $h->nama_produk }}</td>
                <td>{{ $h->tebal_transaksi }}</td>
                <td>{{ $h->lebar_transaksi }}</td>
                <td>{{ $h->panjang_transaksi }}</td>
                <td>{{ $h->jumlah }}</td>
                <td>{{ $h->nama_produk }}</td>
                <td>{{ $h->tebal_penawaran }}</td>
                <td>{{ $h->lebar_penawaran }}</td>
                <td>{{ $h->panjang_penawaran }}</td>
                <td>{{ $h->jumlah }}</td>
                <td>{{ $h->berat }}</td>
                <td>{{ 'Rp.' . number_format($h->harga) }}</td>
                <td>{{ 'Rp.' . number_format($h->ongkir) }}</td>
                <td>{{ 'Rp.' . number_format($h->subtotal) }}</td>
                <td>{{ 'Rp.' . number_format($h->ppn) }}</td>
                <td>{{ 'Rp.' . number_format($h->total) }}</td>
                <td>{{ $h->layanan }}</td>

            </tr>
            @endforeach
            @endif


        </table>

    </div>
    </form>
    </div>
    </div>
    </div>

    <script>
        $('#harga').mask('000.000.000.000.000', {
            reverse: true
        });
        $('#ongkir').mask('000.000.000.000.000', {
            reverse: true
        });


        function selesai() {
            var table_atas = document.getElementById("table_atas");
            table_atas.setAttribute("hidden", true);
            var submit = document.getElementById("submit");
            submit.removeAttribute("hidden");

        }

        function drop() {
            // bikin form nya
            let id_produk = document.getElementById('id_produk').value.split('|');

            let bentuk_produk = id_produk[1];

            let lebar = document.getElementById('lebar_transaksi');
            if (bentuk_produk == "CYLINDER") {

                lebar.setAttribute('value', 0);
                lebar.setAttribute('readonly', true);
            } else {
                lebar.removeAttribute('readonly');
            }



        }
    </script>
@endsection

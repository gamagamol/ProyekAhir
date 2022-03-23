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

                <div class="container">
                    <div class="row">
                        <div class="col-md-3 mt-2">
                            <input type="date" name="tgl_penerimaan" class="form-control"
                                value={{ $data[0]->tgl_pembelian }}>
                            <input type="text" value="{{ $data[0]->kode_transaksi }}" name="kode_transaksi" hidden>

                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <div class="table-responsive text-center">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

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
                                <td>VAT 10%</td>
                                <td>Total Amount</td>
                                <td>Processing</td>
                                <td>Custumor</td>
                                <td>Supplier</td>

                            </tr>

                            @foreach ($data as $p)
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
                                        {{ $p->tebal_transaksi }}
                                    </td>
                                    <td>
                                        {{ $p->lebar_transaksi }}
                                    </td>

                                    <td>
                                        {{ $p->panjang_transaksi }}
                                    </td>
                                    <td>
                                        {{ $p->jumlah_detail_pembelian }}
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
                                <button type=submit name=submit class="btn btn-primary">submit</button>
                                <a href="{{ url('goods') }}" class="btn btn-primary">back</a>
                            </div>
                        </div>
                    </div>
            </form>
        </div>
    </div>
    </div>
@endsection()

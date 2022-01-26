@extends('template.index')
@section('content')
    @if (session()->has('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif
    <div class="container">
        <div class="card shadow mb-4">
            <div class="card-header py-3 mt-2">
                <h6 class="m-0 font-weight-bold text-primary">Bill Payment</h6>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-md-4 mt-3">
                        <form action={{ url('bill') }} method="GET" id="serch-form">
                            <select class="form-control form-select" aria-label="Default select example" name='serch'
                                id="serch">
                                @foreach ($deta as $d)
                                    <option value={{ $d->no_tagihan }}>{{ $d->no_tagihan }}</option>
                                @endforeach
                            </select>
                            <button type=submit name=submit class="btn btn-primary mt-3" id="serch-button">submit</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">

                <div class="table-responsive text-center">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

                        <tr>
                            <td>No</td>
                            <td>customer</td>
                            <td>Date</td>
                            <td>Due Date</td>
                            <td>No Bill Payment</td>
                            <td>Weight (Kg) </td>
                            <td>Total Amount</td>
                            <td>Processing</td>
                            <td>Prepared</td>
                            <td>Action</td>
                            <td>Document</td>



                        </tr>
                        <?php $i = 1; ?>
                        @foreach ($data as $d)


                            <tr>
                                <td> {{ $loop->iteration }}</td>
                                <td>{{ $d->nama_pelanggan }}</td>
                                <td style="min-width:120px">{{ $d->tgl_tagihan }}</td>
                                <td style="min-width:120px">{{ $d->DUE_DATE }}</td>
                                <td>{{ $d->no_tagihan }}</td>

                                <td>{{ $d->berat }}</td>

                                <td>{{ 'Rp.' . number_format($d->total) }}</td>
                                <td>{{ $d->layanan }}</td>
                                <td>{{ $d->nama_pengguna }}</td>
                                <td>


                                    <a href="{{ url('payment/show', ['kode' => str_replace('/', '-', $d->no_tagihan), 'tgl' => $d->kode_transaksi]) }}"
                                        class="btn btn-primary mt-1"> Payment</a>

                                    <a href="{{ url('bill/detail', str_replace('/', '-', $d->no_tagihan)) }}"
                                        class="btn btn-info mt-2">Detail</a>


                                </td>

                                <td>
                                    <a href="{{ url('bill/print', str_replace('/', '-', $d->no_tagihan)) }}"
                                        class="btn btn-info mt-2">print</a>
                                </td>
                            </tr>







                        @endforeach
                    </table>
                    {{ $data->links() }}

                </div>
            </div>
        </div>
    </div>
@endsection()

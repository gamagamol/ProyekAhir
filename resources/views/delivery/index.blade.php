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
                <h6 class="m-0 font-weight-bold text-primary">Delivery Order</h6>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-md-4 mt-3">
                        <form action={{ url('delivery') }} method="GET" id="serch-form">
                            <select class="form-control form-select" aria-label="Default select example" name='serch'
                                id="serch">
                                  <option value='All'>All</option>
                                @foreach ($deta as $d)
                                    <option value={{ $d->no_pengiriman }}>{{ $d->no_pengiriman }}</option>
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
                            <td>Date</td>
                            <td>No Delivery</td>
                            <td>Job number</td>
                            <td>Customer</td>
                            <td>Prepared</td>
                            <td>Action</td>
                            <td>Document</td>



                        </tr>
                        <?php $i = 1; ?>
                        @foreach ($data as $d)
                            <tr>
                                <td> {{ $loop->iteration }}</td>
                                <td style="min-width:120px">{{ $d->tgl_pengiriman }}</td>
                                <td>{{ $d->no_pengiriman }}</td>
                                <td>{{ $d->nomor_pekerjaan }}</td>
                                <td>{{ $d->nama_pelanggan }}</td>
                                <td>{{ $d->nama_pengguna }}</td>
                                <td>
                                  


                                    
                                <a href="{{ url('show', str_replace('/', '-', $d->no_penerimaan)) }}"
                                    class="btn btn-primary mt-1"  @if ($d->status_transaksi == 'bill')
                                       {{'hidden'}}
                                   @endif>
                                    Bill Payment </a>

                                    <a href="{{ url('delivery/detail', str_replace('/', '-', $d->no_pengiriman)) }}"
                                        class="btn btn-info mt-1">
                                        Detail </a>
                                </td>
                                <td>
                                  
                                    @if ($d->jumlah_detail_penerimaan == $d->jumlah_detail_pengiriman)
                                        <a href={{ url('delivery/print', str_replace('/', '-', $d->no_pengiriman)) }}
                                            class="btn btn-primary" target='_blank'>Print </a>
                                    @else
                                        <i class="fas fa-clock fs-1 mt-2"></i>
                                    @endif


                                </td>
                            </tr>
                        @endforeach
                    </table>
                    {{-- {{ $data->links() }} --}}

                </div>
            </div>
        </div>
    </div>
@endsection()

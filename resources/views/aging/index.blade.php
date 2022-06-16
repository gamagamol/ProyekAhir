@extends('template.index')
@section('content')
    <div class="container">
        <div class="card shadow mb-4">
            <div class="card-header py-3 mt-2">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fa fa-bars" aria-hidden="true" id='legenda'></i>
                    Aging Schedule
                </h6>

            </div>

            <form action="" method="get">
                <div class="form-group col-md-6 ml-2 mt-2">
                    <select name="id_pelanggan" id="" class="form-control">
                        <option value="All"> All</option>
                        @foreach ($pelanggan as $p)
                            <option value={{ $p->id_pelanggan }}>{{ $p->nama_pelanggan }}</option>
                        @endforeach
                    </select>
                </div>
                <button type=submit name=submit class="btn btn-primary ml-4">submit</button>
            </form>



            <div class="card-body">


                <a href="{{ url('export') }}" class="btn btn-success my-3" style="margin-left: 90%"> <i
                        class="fas fa-file-excel"></i>
                    Excel</a>
                <div class="table-responsive text-center">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <tr>
                            <td rowspan="2">Customer</td>
                            <td rowspan="2">No Transaction</td>
                            <td rowspan="2">Date</td>
                            <td rowspan="2">Due Date</td>
                            <td rowspan="2">Total Receivable</td>
                            <td colspan="3">Number Days Of Outstanding</td>
                        </tr>

                        <tr>
                            <td style="text-align: center">0-30</td>
                            <td style="text-align: center">31-60</td>
                            <td style="text-align: center">61-90</td>

                        </tr>

                        @foreach ($data as $d)
                            @if ($d->selisih >= 0 && $d->selisih < 15)
                                <?php $BackgroundColor = 'class=bg-success style=color:white '; ?>
                            @elseif ($d->selisih >= 15 && $d->selisih <= 31)
                                <?php $BackgroundColor = 'class=bg-warning style=color:white '; ?>
                            @elseif ($d->selisih >= 31 && $d->selisih <= 90)
                                <?php $BackgroundColor = 'class=bg-danger style=color:white '; ?>
                            @endif




                            <tr {{ $BackgroundColor }}>
                                <td>{{ $d->nama_pelanggan }}</td>
                                <td>{{ $d->no_tagihan }}</td>
                                <td style="min-width:120px">{{ $d->tgl_tagihan }}</td>
                                <td style="min-width:120px">{{ $d->DUE_DATE }}</td>
                                <td>{{ 'Rp.' . number_format($d->total, 2, ',', '.') }}</td>


                                @if ($d->selisih >= 0 && $d->selisih < 30)
                                    <td>{{ 'Rp.' . number_format($d->total_selisih, 2, ',', '.') }}</td>
                                    <td>{{ ' ' }}</td>
                                    <td>{{ ' ' }}</td>
                                @elseif ($d->selisih >= 31 && $d->selisih <= 60)
                                    <td>{{ ' ' }}</td>
                                    <td>{{ 'Rp.' . number_format($d->total_selisih, 2, ',', '.') }}</td>
                                    <td>{{ ' ' }}</td>
                                @elseif ($d->selisih >= 61 && $d->selisih <= 90)
                                    <td>{{ ' ' }}</td>
                                    <td>{{ ' ' }}</td>
                                    <td>{{ 'Rp.' . number_format($d->total_selisih, 2, ',', '.') }}</td>
                                @endif

                            </tr>
                        @endforeach
                    </table>
                    {{-- {{ $data->links() }}S --}}
                </div>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" id="modal-legenda">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">legenda</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table ">
                        <thead>
                            <tr>
                                <th>Color</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-success">Green</td>
                                <th>
                                    If the row in your aging schedule table is green, it means that the age of your
                                    receivables transaction ranges from 0 to 15 days</th>
                            </tr>
                            <tr>
                                <th class="text-warning">Yellow</th>
                                <th>
                                    If the row in your aging schedule table is yellow, which means your receivables transaction age ranges from 15 to 30 days, then you can send an email to the customer by pressing the letter icon on the billing menu as a form of reminding your customer.
                                </th>
                            </tr>
                            <tr>
                                <th class="text-danger">Red</th>
                                <th>
                                    If the row in your aging schedule table is red, which means the age of your receivable transaction is within 30 to 90 days, then you can send an email to the customer by pressing the letter icon on the billing menu as a form of reminding your customer.
                                </th>
                            </tr>
                        </tbody>
                    </table>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#legenda').click(() => {
            $('#modal-legenda').modal('show')
        })
    </script>
@endsection()

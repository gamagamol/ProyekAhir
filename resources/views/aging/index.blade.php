@extends('template.index')
@section('content')
    <div class="container">
        <div class="card shadow mb-4">
            <div class="card-header py-3 mt-2">
                <h6 class="m-0 font-weight-bold text-primary">Aging Schedule</h6>
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
                            @if ($d->selisih >= 0 && $d->selisih < 30)
<<<<<<< HEAD
                                <?php $BackgroundColor = 'class=bg-success style=color:white '; ?>
=======
                                <?php $BackgroundColor = 'class=bg-success'; ?>
>>>>>>> wandi
                            @elseif ($d->selisih >= 31 && $d->selisih <= 60)
                                <?php $BackgroundColor = 'class=bg-warning style=color:white '; ?>
                            @elseif ($d->selisih >= 61 && $d->selisih <= 90)
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
@endsection()

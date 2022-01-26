@extends('template.index')
@section('content')

    <!-- Begin Page Content -->
    <div class="container">


        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">General Ladger</h6>
            </div>
            <div class="card-body">
                <?php $tgl = explode('-', date('Y-m-d')); ?>
                <h3 class="text-center">PT.Ibaraki Kogyo Hanan Indonesia</h3>
                <h4 class="text-center">General Ladger</h4>
                <h5 class="text-center">{{ "Periode $tgl[0] Month $tgl[1]" }}</h5>
                <br>

                <div class="table-responsive">

                    <a href="{{ url('ledger/create') }}" class="btn btn-primary " style="margin-left: 90%"> <i class="fas fa-filter"></i> Filter</a>

                    <table class="table table-bordered text-center mx-auto" id="dataTable" width="100%" cellspacing="0">

                        <tr>
                            <td class="bg-info text-white">Account : {{ $kas[0]->nama_akun }}</td>
                        </tr>
                        <tr aria-rowspan="2">
                            <td rowspan="2">
                                <p class="mx-auto">Date</p>
                            </td>
                            <td rowspan="2">Account</td>
                            <td rowspan="2">Ref</td>
                            <td rowspan="2">Debit</td>
                            <td rowspan="2">Credit</td>
                            <td colspan="2" class="text-center">saldo</td>

                        </tr>
                        <tr>
                            <td>Debit</td>
                            <td>Credit</td>
                        </tr>
                        <?php $total_kas = 0; ?>
                        @foreach ($kas as $k)
                            <tr>
                                <td>{{ $k->tgl_jurnal }}</td>
                                <td>{{ $k->nama_akun }}</td>
                                <td>{{ $k->kode_akun }}</td>
                                @if ($k->posisi_db_cr == 'debit')
                                    <td>{{ 'Rp.' . number_format($k->nominal, 2, ',', '.') }}</td>
                                    <td>{{ '' }}</td>

                                    <?php $total_kas = $total_kas + $k->nominal; ?>

                                    <td>{{ 'Rp.' . number_format($total_kas, 2, ',', '.') }}</td>
                                    <td>{{ '' }}</td>
                                @else
                                    <td>{{ '' }}</td>
                                    <td>{{ 'Rp.' . number_format($k->nominal, 2, ',', '.') }}</td>


                                    <?php $total_kas = $total_kas - $k->nominal; ?>

                                    <td>{{ '' }}</td>
                                    <td>{{ 'Rp.' . number_format($total_kas, 2, ',', '.') }}</td>
                                @endif
                            </tr>
                        @endforeach





                    </table>

                <a href={{url("ledger")}} class="btn btn-primary"> Back</a>

                





                </div>



            </div>

        </div>
    </div>
@endsection()

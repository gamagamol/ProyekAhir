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

                    <a href="{{ url('ledger/create') }}" class="btn btn-primary " style="margin-left: 90%"> <i
                            class="fas fa-filter"></i> Filter</a>

                    <table class="table table-bordered text-center mx-auto" id="dataTable" width="100%" cellspacing="0">

                        <tr>
                            <td class="bg-info text-white">Account : {{ $kode_akun }}</td>
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

                        <?php
                      
                       
                       $beginning_balance=explode('-',$tanggal[0]);
                        $beginning_balance="31-$beginning_balance[1]-$beginning_balance[0]";

                           ?>

                        <tr>
                            <td>{{$beginning_balance}}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            @if ($kode_akun == 411)
                                @if ($saldo_awal)
                                    <td></td>
                                    <td>{{ 'Rp.' . ' ' . number_format($saldo_awal) }}</td>
                                @else
                                    <td></td>
                                    <td>Rp. 0</td>
                                @endif
                            @else
                                @if ($saldo_awal)
                                    <td>{{ 'Rp.' . ' ' . number_format($saldo_awal) }}</td>
                                    <td></td>
                                @else
                                    <td>Rp. 0</td>
                                    <td></td>
                                @endif
                            @endif

                        </tr>
                        <?php $total = 0; ?>
                        @foreach ($kas as $k)
                            <tr>
                                <td>{{ $k->tgl_jurnal }}</td>
                                <td>{{ $k->nama_akun }}</td>
                                <td>{{ $k->kode_akun }}</td>
                                {{-- check kode _akun --}}
                                @if ($kode_akun == 411)
                                    {{-- check posisi akun --}}
                                    @if ($k->posisi_db_cr == 'debit')
                                        {{-- ceheck kode akun --}}
                                        <td>{{ 'Rp.' . ' ' . number_format($k->nominal) }}</td>
                                        <td>{{ ' ' }}</td>
                                        {{-- check perhitung --}}
                                        <?php
                                        if ($saldo_awal) {
                                            $total = $saldo_awal - $k->nominal;
                                        } else {
                                            $total -= $k->nominal;
                                        }
                                        ?>
                                        <td>{{ 'Rp.' . ' ' . number_format($k->nominal) }}</td>
                                        <td>{{ ' ' }}</td>
                                    @else
                                        <td>{{ ' ' }}</td>
                                        <td>{{ 'Rp.' . ' ' . number_format($k->nominal) }}</td>


                                        {{-- check perhitung --}}
                                        <?php
                                        if ($saldo_awal) {
                                            $total = $saldo_awal + $k->nominal;
                                        } else {
                                            $total += $k->nominal;
                                        }
                                        ?>
                                        <td>{{ ' ' }}</td>
                                        <td>{{ 'Rp.' . ' ' . number_format($k->nominal) }}</td>
                                    @endif
                                @else
                                    {{-- check posisi akun --}}
                                    @if ($k->posisi_db_cr == 'debit')
                                        {{-- ceheck kode akun --}}
                                        <td>{{ 'Rp.' . ' ' . number_format($k->nominal) }}</td>
                                        <td>{{ ' ' }}</td>
                                        {{-- check perhitung --}}
                                        <?php
                                        if ($saldo_awal) {
                                            $total = $saldo_awal + $k->nominal;
                                        } else {
                                            $total += $k->nominal;
                                        }
                                        ?>
                                        <td>{{ 'Rp.' . ' ' . number_format($total) }}</td>
                                        <td>{{ ' ' }}</td>
                                    @else
                                        <td>{{ ' ' }}</td>
                                        <td>{{ 'Rp.' . ' ' . number_format($k->nominal) }}</td>


                                        {{-- check perhitung --}}
                                        <?php
                                        if ($saldo_awal) {
                                            $total = $saldo_awal - $k->nominal;
                                        } else {
                                            $total = $total - $k->nominal;
                                        }
                                        ?>
                                        <td>{{ ' ' }}</td>
                                        <td>{{ 'Rp.' . ' ' . number_format($total) }}</td>
                                    @endif
                                @endif
                            </tr>
                        @endforeach





                    </table>

                    <a href={{ url('ledger') }} class="btn btn-primary"> Back</a>







                </div>



            </div>

        </div>
    </div>
@endsection()

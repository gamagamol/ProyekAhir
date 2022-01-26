@extends('template.index')
@section('content')
    <!-- Begin Page Content -->
    <div class="container">


        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Journal</h6>
            </div>
            <div class="card-body">
                <?php $tgl = explode('-', date('Y-m-d')); ?>
                <h3 class="text-center">PT.Ibaraki Kogyo Hanan Indonesia</h3>
                <h4 class="text-center">General Journal</h4>
                <h5 class="text-center">{{ "Periode $tgl[0] Month $tgl[1]" }}</h5>
                <br>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <tr>
                            <td>Date</td>
                            <td>Account</td>
                            <td>Ref</td>
                            <td>Debit</td>
                            <td>Credit</td>

                        </tr>
                        <?php 
                            $total_debit=0;
                            $total_kredit=0;
                        ?>
                        @foreach ($data as $d)
                            @if ($d->posisi_db_cr == 'debit')
                                <tr>
                                    <td>{{ $d->tgl_jurnal }}</td>
                                    <td>{{ $d->nama_akun }}</td>
                                    <td style="text-align: center">{{ $d->kode_akun }}</td>
                                    <td style="text-align: right">{{ 'Rp.' . number_format($d->nominal) }}</td>
                                    <td>{{ ' ' }}</td>
                                </tr>
                                <?php $total_debit=$total_debit+$d->nominal; ?>
                            @else
                                <tr>
                                    <td>{{ $d->tgl_jurnal }}</td>
                                    <td class="text-right">{{ " " . $d->nama_akun ."    "}}</td>
                                    <td style="text-align: center">{{ $d->kode_akun }}</td>
                                    <td>{{ ' ' }}</td>
                                    <td style="text-align: right">{{ 'Rp.' . number_format($d->nominal) }}</td>
                                </tr>
                                <?php $total_kredit=$total_kredit+$d->nominal; ?>

                            @endif
                        @endforeach
                        
                            <tr>
                                <td colspan="3"></td>
                                <td class="text-center">{{ 'Rp.' . number_format($total_kredit) }}</td>
                                <td class="text-center">{{ 'Rp.' . number_format($total_debit) }}</td>
                            </tr>

                    </table>
                </div>

            </div>

        </div>
    </div>
@endsection()

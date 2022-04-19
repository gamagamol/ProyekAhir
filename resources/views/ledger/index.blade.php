@extends('template.index')
@section('content')
    <!-- Begin Page Content -->
    <div class="container">


        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">General Ledger</h6>
            </div>
            <div class="card-body">
                <?php $tgl = explode('-', date('Y-m-d')); ?>
                <h3 class="text-center">PT.Ibaraki Kogyo Hanan Indonesia</h3>
                <h4 class="text-center">General Ledger</h4>
                <h5 class="text-center">{{ "Periode $tgl[0] Month $tgl[1]" }}</h5>
                <br>

                <div class="table-responsive">

                    <a href="{{ url('ledger/create') }}" class="btn btn-primary " style="margin-left: 90%"> <i
                            class="fas fa-filter"></i> Filter</a>

                    <table class="table table-bordered text-center mx-auto" id="dataTable" width="100%" cellspacing="0">

                        <tr>
                            <td class="bg-info text-white">Account : Cash </td>
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


                        <tr>
                            <td>{{ date('Y') . '-01-01' }}</td>
                            <td>Beginning Balance</td>
                            <td>111</td>
                            <td>{{ ' ' }} </td>
                            <td>{{ ' ' }} </td>
                            @if ($saldo_awal_kas > 0)
                                <td> {{ 'Rp.' . number_format($saldo_awal_kas, 2, ',', '.') }} </td>
                                <td> {{ ' ' }} </td>
                            @else
                                <td> {{ ' ' }} </td>
                                <td> {{ 'Rp.' . number_format($saldo_awal_kas, 2, ',', '.') }} </td>
                            @endif
                        </tr>

                        @foreach ($kas as $k)
                            <tr>
                                <td>{{ $k->tgl_jurnal }}</td>
                                <td>{{ $k->nama_akun }}</td>
                                <td>{{ $k->kode_akun }}</td>
                                @if ($k->posisi_db_cr == 'debit')
                                    <td>{{ 'Rp.' . number_format($k->nominal, 2, ',', '.') }}</td>
                                    <td>{{ '' }}</td>

                                    <?php $total_kas = $total_kas + $k->nominal + $saldo_awal_kas; ?>

                                    <td>{{ 'Rp.' . number_format($total_kas, 2, ',', '.') }}</td>
                                    <td>{{ '' }}</td>
                                @else
                                    <td>{{ '' }}</td>
                                    <td>{{ 'Rp.' . number_format($k->nominal, 2, ',', '.') }}</td>

                                    @if ($saldo_awal_kas > 0)
                                        <?php $total_kas = $saldo_awal_kas - $k->nominal; ?>
                                    @else
                                        <?php $total_kas = $total_kas - $k->nominal; ?>
                                    @endif

                                    <td>{{ '' }}</td>
                                    <td>{{ 'Rp.' . number_format($total_kas, 2, ',', '.') }}</td>
                                @endif
                            </tr>
                        @endforeach





                    </table>

                    <br><br>
                    <table class="table table-bordered text-center mx-auto" id="dataTable" width="100%" cellspacing="0">

                        <tr>
                            <td class="bg-info text-white">Account : Account Recivable</td>
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


                        <tr>
                            <td>{{ date('Y') . '-01-01' }}</td>
                            <td>Beginning Balance</td>
                            <td>112</td>
                            <td>{{ ' ' }} </td>
                            <td>{{ ' ' }} </td>
                            @if ($saldo_awal_piutang > 0)
                                <td> {{ 'Rp.' . number_format($saldo_awal_piutang, 2, ',', '.') }} </td>
                                <td> {{ ' ' }} </td>
                            @else
                                <td> {{ ' ' }} </td>
                                <td> {{ 'Rp.' . number_format($saldo_awal_piutang, 2, ',', '.') }} </td>
                            @endif
                        </tr>

                        <?php $total_piutang = 0; ?>

                        @foreach ($piutang as $k)
                            <tr>
                                <td>{{ $k->tgl_jurnal }}</td>
                                <td>{{ $k->nama_akun }}</td>
                                <td>{{ $k->kode_akun }}</td>
                                @if ($k->posisi_db_cr == 'debit')
                                    <td>{{ 'Rp.' . number_format($k->nominal, 2, ',', '.') }}</td>
                                    <td>{{ '' }}</td>

                                    <?php $total_piutang += $saldo_awal_piutang + $k->nominal; ?>


                                    <td>{{ 'Rp.' . number_format($total_piutang, 2, ',', '.') }}</td>
                                    <td>{{ '' }}</td>
                                @else
                                    <td>{{ '' }}</td>
                                    <td>{{ 'Rp.' . number_format($k->nominal, 2, ',', '.') }}</td>

                                    @if ($saldo_awal_piutang > 0)
                                        <?php $total_piutang -= $saldo_awal_piutang - $k->nominal; ?>
                                    @else
                                        <?php $total_piutang -= $k->nominal; ?>
                                    @endif

                                    <td>{{ '' }}</td>
                                    <td>{{ 'Rp.' . number_format($total_piutang, 2, ',', '.') }}</td>
                                @endif
                            </tr>
                        @endforeach





                    </table>
                    <br><br>

                    <table class="table table-bordered text-center mx-auto" id="dataTable" width="100%" cellspacing="0">

                        <tr>
                            <td class="bg-info text-white">Account : Payable </td>
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


                        <?php $total_utang = 0; ?>


                        <tr>
                            <td>{{ date('Y') . '-01-01' }}</td>
                            <td>Beginning Balance</td>
                            <td>200</td>
                            <td>{{ ' ' }} </td>
                            <td>{{ ' ' }} </td>
                            @if ($saldo_awal_utang > 0)
                                <td> {{ 'Rp.' . number_format($saldo_awal_utang, 2, ',', '.') }} </td>
                                <td> {{ ' ' }} </td>
                            @else
                                <td> {{ ' ' }} </td>
                                <td> {{ 'Rp.' . number_format($saldo_awal_utang, 2, ',', '.') }} </td>
                            @endif
                        </tr>

                        @foreach ($payable as $k)
                            <tr>
                                <td>{{ $k->tgl_jurnal }}</td>
                                <td>{{ $k->nama_akun }}</td>
                                <td>{{ $k->kode_akun }}</td>
                                @if ($k->posisi_db_cr == 'kredit')
                                    <td>{{ '' }}</td>
                                    <td>{{ 'Rp.' . number_format($k->nominal, 2, ',', '.') }}</td>

                                    <?php $total_utang = $total_utang + $k->nominal + $saldo_awal_utang; ?>

                                    <td>{{ 'Rp.' . number_format($total_utang, 2, ',', '.') }}</td>
                                    <td>{{ '' }}</td>
                                @else
                                    <td>{{ 'Rp.' . number_format($k->nominal, 2, ',', '.') }}</td>
                                    <td>{{ '' }}</td>

                                    <?php if($saldo_awal_utang>0): ?>

                                    <?php $total_utang = $saldo_awal_utang - $k->nominal; ?>
                                    <?php else: ?>
                                    <?php $total_utang = $total_utang - $k->nominal; ?>

                                    <?php endif; ?>


                                    <td>{{ '' }}</td>
                                    <td>{{ 'Rp.' . number_format($total_utang, 2, ',', '.') }}</td>
                                @endif
                            </tr>
                        @endforeach





                    </table>

                    <br><br>

                    <table class="table table-bordered text-center mx-auto" id="dataTable" width="100%" cellspacing="0">

                        <tr>
                            <td class="bg-info text-white">Account : Vat Debt </td>
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


                        <?php $total_ppn = 0; ?>
                        <tr>
                            <td>{{ date('Y') . '-01-01' }}</td>
                            <td>Beginning Balance</td>
                            <td>211</td>
                            <td>{{ ' ' }} </td>
                            <td>{{ ' ' }} </td>
                            @if ($saldo_awal_ppn > 0)
                                <td> {{ ' ' }} </td>
                                <td> {{ 'Rp.' . number_format($saldo_awal_ppn, 2, ',', '.') }} </td>
                            @else
                                <td> {{ ' ' }} </td>
                                <td> {{ 'Rp.' . number_format($saldo_awal_ppn, 2, ',', '.') }} </td>
                            @endif
                        </tr>
                        @foreach ($ppn as $k)
                            <tr>
                                <td>{{ $k->tgl_jurnal }}</td>
                                <td>{{ $k->nama_akun }}</td>
                                <td>{{ $k->kode_akun }}</td>
                                @if ($k->posisi_db_cr == 'kredit')
                                    <td>{{ '' }}</td>
                                    <td>{{ 'Rp.' . number_format($k->nominal, 2, ',', '.') }}</td>

                                    <?php $total_ppn += $saldo_awal_ppn + $k->nominal; ?>

                                    <td>{{ '' }}</td>
                                    <td>{{ 'Rp.' . number_format($total_ppn, 2, ',', '.') }}</td>
                                @else
                                    <td>{{ 'Rp.' . number_format($k->nominal, 2, ',', '.') }}</td>
                                    <td>{{ '' }}</td>


                                    <?php $total_ppn -= $k->nominal; ?>

                                    <td>{{ 'Rp.' . number_format($total_ppn, 2, ',', '.') }}</td>
                                    <td>{{ '' }}</td>
                                @endif
                            </tr>
                        @endforeach





                    </table>

                    <br><br>





                    <table class="table table-bordered text-center mx-auto" id="dataTable" width="100%" cellspacing="0">

                        <tr>
                            <td class="bg-info text-white">Account : Carrying Load Debt </td>
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

                        <?php $total_ongkir = 0; ?>

                        <tr>
                            <td>{{ date('Y') . '-01-01' }}</td>
                            <td>Beginning Balance</td>
                            <td>212</td>
                            <td>{{ ' ' }} </td>
                            <td>{{ ' ' }} </td>
                            @if ($saldo_awal_ongkir > 0)
                                <td> {{ ' ' }} </td>
                                <td> {{ 'Rp.' . number_format($saldo_awal_ongkir, 2, ',', '.') }} </td>
                            @else
                                <td> {{ ' ' }} </td>
                                <td> {{ 'Rp.' . number_format($saldo_awal_ongkir, 2, ',', '.') }} </td>
                            @endif
                        </tr>
                        @foreach ($ongkir as $k)
                            <tr>
                                <td>{{ $k->tgl_jurnal }}</td>
                                <td>{{ $k->nama_akun }}</td>
                                <td>{{ $k->kode_akun }}</td>
                                @if ($k->posisi_db_cr == 'kredit')
                                    <td>{{ '' }}</td>
                                    <td>{{ 'Rp.' . number_format($k->nominal, 2, ',', '.') }}</td>


                                    <?php $total_ongkir += $saldo_awal_ongkir + $k->nominal; ?>

                                    <td>{{ '' }}</td>
                                    <td>{{ 'Rp.' . number_format($total_ongkir, 2, ',', '.') }}</td>
                                @else
                                    <td>{{ 'Rp.' . number_format($k->nominal, 2, ',', '.') }}</td>
                                    <td>{{ '' }}</td>


                                    <?php $total_ongkir -= $k->nominal; ?>

                                    <td>{{ 'Rp.' . number_format($total_ongkir, 2, ',', '.') }}</td>
                                    <td>{{ '' }}</td>
                                @endif
                            </tr>
                        @endforeach





                    </table>
                    <br><br>


                    <table class="table table-bordered text-center mx-auto" id="dataTable" width="100%" cellspacing="0">

                        <tr>
                            <td class="bg-info text-white">Account : Sales </td>
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

                        <?php $total = 0; ?>
                        <tr>
                            <td>{{ date('Y') . '-01-01' }}</td>
                            <td>Beginning Balance</td>
                            <td>411</td>
                            <td>{{ ' ' }} </td>
                            <td>{{ ' ' }} </td>
                            @if ($saldo_awal_penjualan > 0)
                                <td> {{ ' ' }} </td>
                                <td> {{ 'Rp.' . number_format($saldo_awal_penjualan, 2, ',', '.') }} </td>
                            @else
                                <td> {{ 'Rp.' . number_format($saldo_awal_penjualan, 2, ',', '.') }} </td>
                                <td> {{ ' ' }} </td>
                            @endif
                        </tr>
                        @foreach ($revenue as $k)
                            <tr>
                                <td>{{ $k->tgl_jurnal }}</td>
                                <td>{{ $k->nama_akun }}</td>
                                <td>{{ $k->kode_akun }}</td>
                                @if ($k->posisi_db_cr == 'debit')
                                    <td>{{ 'Rp.' . number_format($k->nominal, 2, ',', '.') }}</td>

                                    <td>{{ '' }}</td>

                                    <?php $total = $saldo_awal_penjualan + $k->nominal; ?>

                                    <td>{{ 'Rp.' . number_format($total, 2, ',', '.') }}</td>
                                    <td>{{ '' }}</td>
                                @else
                                    <td>{{ '' }}</td>
                                    <td>{{ 'Rp.' . number_format($k->nominal, 2, ',', '.') }}</td>


                                    <?php $total += $k->nominal + $saldo_awal_penjualan; ?>

                                    <td>{{ '' }}</td>
                                    <td>{{ 'Rp.' . number_format($total, 2, ',', '.') }}</td>
                                @endif
                            </tr>
                        @endforeach
                    </table>
                    <br><br>

                    <table class="table table-bordered text-center mx-auto" id="dataTable" width="100%" cellspacing="0">

                        <tr>
                            <td class="bg-info text-white">Account : Purhcase </td>
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


                        <?php $total_pembelian = 0; ?>


                        <tr>
                            <td>{{ date('Y') . '-01-01' }}</td>
                            <td>Beginning Balance</td>
                            <td>111</td>
                            <td>{{ ' ' }} </td>
                            <td>{{ ' ' }} </td>
                            @if ($saldo_awal_pembelian > 0)
                                <td> {{ 'Rp.' . number_format($saldo_awal_pembelian, 2, ',', '.') }} </td>
                                <td> {{ ' ' }} </td>
                            @else
                                <td> {{ ' ' }} </td>
                                <td> {{ 'Rp.' . number_format($saldo_awal_pembelian, 2, ',', '.') }} </td>
                            @endif
                        </tr>

                        @foreach ($purchase as $k)
                            <tr>
                                <td>{{ $k->tgl_jurnal }}</td>
                                <td>{{ $k->nama_akun }}</td>
                                <td>{{ $k->kode_akun }}</td>
                                @if ($k->posisi_db_cr == 'debit')
                                    <td>{{ 'Rp.' . number_format($k->nominal, 2, ',', '.') }}</td>
                                    <td>{{ '' }}</td>

                                    <?php $total_pembelian = $saldo_awal_pembelian + $k->nominal; ?>

                                    <td>{{ 'Rp.' . number_format($total_pembelian, 2, ',', '.') }}</td>
                                    <td>{{ '' }}</td>
                                @else
                                    <td>{{ '' }}</td>
                                    <td>{{ 'Rp.' . number_format($k->nominal, 2, ',', '.') }}</td>


                                    <?php $total_pembelian = $saldo_awal_pembelian - $k->nominal; ?>

                                    <td>{{ '' }}</td>
                                    <td>{{ 'Rp.' . number_format($total_pembelian, 2, ',', '.') }}</td>
                                @endif
                            </tr>
                        @endforeach





                    </table>

                    <br><br>



                </div>



            </div>

        </div>
    </div>
@endsection()

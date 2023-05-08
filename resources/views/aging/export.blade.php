<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <tr>
        <td rowspan="2">Custumor</td>
        <td rowspan="2">No Transaction</td>
        <td rowspan="2">Date</td>
        <td rowspan="2">Due Date</td>
        <td rowspan="2">Total Receivable</td>
        <td colspan="3">Number Days Of Outstanding</td>
    </tr>

    <tr>
        <td>0-30</td>
        <td>3-60</td>
        <td>6-90</td>

    </tr>
    @foreach ($data as $d)
        <tr>
            <td>{{ $d->nama_pelanggan }}</td>
            <td>{{ $d->no_tagihan }}</td>
            <td style="min-width:120px">{{ $d->tgl_tagihan }}</td>
            <td style="min-width:120px">{{ $d->DUE_DATE }}</td>
            <td>{{ 'Rp.' . number_format($d->total, 2, ',', '.') }}</td>


            @if ($d->selisih >= 0 && $d->selisih < 30)
                <td>{{ 'Rp.' . number_format($d->total_selisih, 2, ',', '.') }}</td>
                <td>{{ ' ' }}</td>
                <td>{{ ' ' }}</td>
            @elseif ($d->selisih > 30 && $d->selisih <= 60) <td>
                <td>{{ ' ' }}</td>
                <td>{{ 'Rp.' . number_format($d->total_selisih, 2, ',', '.') }}</td>
                <td>{{ ' ' }}</td>
            @elseif ($d->selisih > 60 && $d->selisih <= 90)
                <td>{{ ' ' }}</td>
                <td>{{ ' ' }}</td>
                <td>{{ 'Rp.' . number_format($d->total_selisih, 2, ',', '.') }}</td>
            @endif

        </tr>
    @endforeach
</table>

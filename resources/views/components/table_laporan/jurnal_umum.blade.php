@props(['datas' => null, 'jurnalUmum' => null, 'route' => '', 'route_excel' => ''])


@if($datas)
<div class="row">

    <div class="d-flex justify-content-end mb-4 gap-2">
        <a href="{{ route($route, [
            'start_date' => request('start_date'),
            'end_date' => request('end_date'),
        ]) }}">

        <button class="btn btn-danger text-white">Export PDF</button></a>
        <a href="{{ route($route_excel, [
            'start_date' => request('start_date'),
            'end_date' => request('end_date'),
        ]) }}">
        <button class="btn btn-success text-white">Export Excel</button></a>
    </div>

    <table class="table table-striped" id="table1">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kode</th>
                <th>Akun</th>
                <th>Debit</th>
                <th>Kredit</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $latestHeader = null
            @endphp
            @forelse ($jurnalUmum['data'] as $data)
                <tr>
                    @if($latestHeader != $data->jurnal_header->id)
                        <td>{{ $no++ }}</td>
                    @else
                        <td></td>
                    @endif
                    @if($latestHeader != $data->jurnal_header->id)
                        <td>{{ $data->jurnal_header->tanggal }}</td>
                    @else
                        <td></td>
                    @endif
                    <td>{{ $data->akun->kode }}</td>
                    <td>{{ $data->akun->nama }}</td>
                    <td>Rp.{{ number_format($data->nominal_debit, 2, ',', '.') }}</td>
                    <td>Rp.{{ number_format($data->nominal_kredit, 2, ',', '.') }}</td>
                </tr>
                @php
                    $latestHeader = $data->jurnal_header->id;
                @endphp
            @empty
                <tr>
                    <td colspan="6">Data Tidak ada</td>
                </tr>
            @endforelse
        </tbody>
        <tbody>
            <tr>
                <td colspan="4">Total</td>
                <td>Rp.{{ number_format($jurnalUmum['total_debit'], 2, ',', '.') }}</td>
                <td>Rp.{{ number_format($jurnalUmum['total_kredit'], 2, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</div>
@else
<p class="text-center text-muted">Silakan pilih laporan untuk menampilkan data.</p>
@endif



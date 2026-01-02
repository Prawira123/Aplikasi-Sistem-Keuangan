@props(['datas' => null, 'bukuBesar' => null, 'route' => '', 'route_excel' => ''])

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
        @foreach($bukuBesar as $akun => $entries)
        <thead>
            <tr>
                <th colspan="6"><h6>{{ $akun }}</h6></th>
            </tr>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kode</th>
                <th>Debit</th>
                <th>Kredit</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
            @endphp
            @forelse ($entries['transaksi'] as $data)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $data['tanggal'] }}</td>
                    <td>{{ $data['kode'] }}</td>
                    <td>Rp.{{ number_format($data['nominal_debit'], 2, ',', '.') }}</td>
                    <td>Rp.{{ number_format($data['nominal_kredit'], 2, ',', '.') }}</td>
                    @if($data['normal_post'] == 'Debit')
                        <td>Rp.{{ number_format($data['saldo_debit'], 2, ',', '.') }}</td>
                    @elseif($entries['normal_post'] == 'Kredit')
                        <td>Rp.{{ number_format($data['saldo_kredit'], 2, ',', '.') }}</td>
                    @endif
                </tr>
                <tr>
            @empty
                <tr>
                    <td colspan="5">Data Tidak ada</td>
                </tr>
            @endforelse
        </tbody>
        @endforeach
    </table>
</div>
@else
<p class="text-center text-muted">Silakan pilih laporan untuk menampilkan data.</p>
@endif



@props(['datas' => null,'pendapatan' => null, 'beban' => null, 'route' => '', 'route_excel' => ''])

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
                <th>Aset Tetap</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
            @endphp
            @forelse ($pendapatan['data'] as $data)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $data['tanggal'][0] ?? '-' }}</td>
                    <td>{{ $data['kode'] }}</td>
                    <td>{{ $data['nama'] }}</td>
                    <td>Rp.{{ number_format($data['total'], 2, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Data Tidak ada</td>
                </tr>
            @endforelse
            <tr>
                <td colspan="4">Total Pendapatan</td>
                <td>Rp.{{ number_format($pendapatan['total'], 2, ',', '.') }}</td>
            </tr>
        </tbody>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kode</th>
                <th>Aset Lancar</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
            @endphp
            @forelse ($beban['data'] as $data)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $data['tanggal'][0] ?? '-' }}</td>               
                <td>{{ $data['kode'] }}</td>
                <td>{{ $data['nama'] }}</td>
                <td>Rp.{{ number_format($data['total'], 2, ',', '.') }}</td>
            </tr>
             @empty
                <tr>
                    <td colspan="5">Data Tidak ada</td>
                </tr>
            @endforelse
            <tr>
                <td colspan="4">HPP</td>
                <td>Rp.{{ number_format($beban['hpp'], 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="4">Total Beban</td>
                <td>Rp.{{ number_format($beban['total'] + $beban['hpp'], 2, ',', '.') }}</td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td colspan="4"><h6>Laba/Rugi</h6></td>
                <td>Rp.{{ number_format($pendapatan['total']  - ($beban['total'] + $beban['hpp']), 2, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</div>
@else
<p class="text-center text-muted">Silakan pilih laporan untuk menampilkan data.</p>
@endif



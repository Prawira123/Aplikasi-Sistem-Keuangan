@props(['datas' => null, 'transaksiMasuk' => null, 'route' => '', 'route_excel' => ''])

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
                <th>Barang</th>
                <th>Jasa</th>
                <th>Paket</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
            @endphp
            @forelse ($transaksiMasuk['data'] as $data)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $data->tanggal }}</td>
                    <td>{{ $data->product->nama ?? '-' }}</td>
                    <td>{{ $data->jasa->nama ?? '-' }}</td>
                    <td>{{ $data->paket->nama ?? '-' }}</td>
                    <td>Rp.{{ number_format($data->harga_total, 2, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Data Tidak ada</td>
                </tr>
            @endforelse
            <tr>
                <td colspan="5">Total Transaksi Masuk</td>
                <td>Rp.{{ number_format($transaksiMasuk['total'], 2, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</div>
@else
<p class="text-center text-muted">Silakan pilih laporan untuk menampilkan data.</p>
@endif



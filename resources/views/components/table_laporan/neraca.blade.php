    @props(['datas' => null, 'asetTetap' => null, 'asetLancar' => null, 'kewajiban' => null, 'ekuitas' => null, 'route' => '', 'route_excel' => ''])

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
                @forelse ($asetTetap['data'] as $data)
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
                    <td colspan="4">Total Aset Tetap</td>
                    <td>Rp.{{ number_format($asetTetap['total'], 2, ',', '.') }}</td>
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
                @forelse ($asetLancar['data'] as $data)
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
                    <td colspan="4">Total Aset Lancar</td>
                    <td>Rp.{{ number_format($asetLancar['total'], 2, ',', '.') }}</td>
                </tr>
            </tbody>
            <tbody>
                <tr>
                    <td colspan="4"><h6>Total Aktiva</h6></td>
                    <td>Rp.{{ number_format($asetTetap['total'] + $asetLancar['total'], 2, ',', '.') }}</td>
                </tr>
            </tbody>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Kode</th>
                    <th>Kewajiban</th>
                    <th>Saldo</th>
                </tr>
            </thead>
            <tbody>
                @forelse ( $kewajiban['data'] as $data )
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $data['tanggal'][0] ?? '-' }}</td>
                        <td>{{ $data['kode'] }}</td>
                        <td>{{ $data['nama'] }}</td>
                        <td>{{ number_format($data['total'], 2,',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Data Tidak ada</td>
                    </tr>
                @endforelse
                <tr>
                    <td colspan="4">Total Kewajiban Lancar</td>
                    <td class="">Rp.{{ number_format($kewajiban['total'], 2, ',', '.')}}</td>
                </tr>
            </tbody>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Kode</th>
                    <th>Ekuitas</th>
                    <th>Saldo</th>
                </tr>
            </thead>
            <tbody>
                @forelse ( $ekuitas['data'] as $data )
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
                    <td colspan="4">Total Ekuitas</td>
                    <td class="">Rp.{{ number_format($ekuitas['total'], 2, ',', '.') }}</td>
                </tr>
            </tbody>
            <tbody>
                <tr>
                    <td colspan="4"><h6>Total Pasiva</h6></td>
                    <td class=""> Rp.{{ number_format($kewajiban['total'] + $ekuitas['total'], 2, ',','.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    @else
    <p class="text-center text-muted">Silakan pilih laporan untuk menampilkan data.</p>
    @endif



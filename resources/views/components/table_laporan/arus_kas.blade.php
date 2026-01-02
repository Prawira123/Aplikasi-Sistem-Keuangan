@props(['datas' => null, 'arusKas' => null, 'route' => '', 'route_excel' => ''])

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
                <th colspan="3"><h6>Saldo Awal</h6></th>
            </tr>
            <tr>
                <th>Akun</th>
                <th colspan="2">Saldo</th>
            </tr>
        </thead>
        <tbody>
                @if($arusKas['saldo_awal'])    
                <tr>
                    <td>{{ $arusKas['akun_kas']->nama}}</td>
                    <td colspan="2">Rp.{{ number_format($arusKas['saldo_awal'], 2, ',', '.') }}</td>
                </tr>
                <tr>
                @else
                <tr>
                    <td>KAS</td>
                    <td colspan="2">Rp.{{ number_format(0, 2, ',', '.') }}</td>                    
                </tr>
                @endif
        </tbody>
        <thead>
            <tr>
                <th colspan="3"><h6>Kas Operasional</h6></th>
            </tr>
            <tr>
                <th>No</th>
                <th>Akun</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
            @endphp
            @forelse ($arusKas['kas_operasional'] as $data)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $data->akun}}</td>
                    <td>Rp.{{ number_format($data->total, 2, ',', '.') }}</td>
                </tr>
                <tr>
             @empty
                <tr>
                    <td colspan="3">Data Tidak ada</td>
                </tr>
            @endforelse
                <tr>
                    <td colspan="2">Total</td>
                    <td>Rp.{{ number_format($arusKas['total_kas_operasional'], 2, ',', '.') }}</td>   
                </tr> 
        </tbody>
        <thead>
            <tr>
                <th colspan="3"><h6>Kas Investasi</h6></th>
            </tr>
            <tr>
                <th>No</th>
                <th>Akun</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
            @endphp
            @forelse ($arusKas['kas_investasi'] as $data)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $data->akun}}</td>
                    <td>Rp.{{ number_format($data->total, 2, ',', '.') }}</td>
                </tr>
                <tr>
             @empty
                <tr>
                    <td colspan="3">Data Tidak ada</td>
                </tr>
            @endforelse
                <tr>
                    <td colspan="2">Total</td>
                    <td>Rp.{{ number_format($arusKas['total_kas_investasi'], 2, ',', '.') }}</td>   
                </tr> 
        </tbody>
        <thead>
            <tr>
                <th colspan="3"><h6>Kas Pendanaan</h6></th>
            </tr>
            <tr>
                <th>No</th>
                <th>Akun</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
            @endphp
            @forelse ($arusKas['kas_pendanaan'] as $data)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $data->akun}}</td>
                    <td>Rp.{{ number_format($data->total, 2, ',', '.') }}</td>
                </tr>
                <tr>
             @empty
                <tr>
                    <td colspan="3">Data Tidak ada</td>
                </tr>
            @endforelse
                <tr>
                    <td colspan="2">Total</td>
                    <td>Rp.{{ number_format($arusKas['total_kas_pendanaan'], 2, ',', '.') }}</td>   
                </tr> 
        </tbody>
         <thead>
            <tr>
                <th colspan="3"><h6>Pergerakan Laba Bersih</h6></th>
            </tr>
            <tr>
                <th colspan="3">Total</th>
            </tr>
        </thead>
        <tbody>
                {{-- @if($arusKas['pergerakan_bersih'])     --}}
                <tr>
                    <td colspan="3">Rp.{{ number_format($arusKas['pergerakan_bersih'], 2, ',', '.') }}</td>
                </tr>
                {{-- @else
                    <tr>
                        <td colspan="3">Data Tidak ada</td>
                    </tr>
                @endif --}}
        </tbody>
         <thead>
            <tr>
                <th colspan="3"><h6>Saldo Akhir</h6></th>
            </tr>
            <tr>
                <th colspan="3">Total</th>
            </tr>
        </thead>
        <tbody>
                @if($arusKas['saldo_akhir'])    
                <tr>
                    <td colspan="3">Rp.{{ number_format($arusKas['saldo_akhir'], 2, ',', '.') }}</td>
                </tr>
                @else
                    <tr>
                        <td colspan="3">Data Tidak ada</td>
                    </tr>
                @endif
        </tbody>
    </table>
</div>
@else
<p class="text-center text-muted">Silakan pilih laporan untuk menampilkan data.</p>
@endif



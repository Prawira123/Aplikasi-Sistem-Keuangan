<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Buku Besar</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .header h1 {
            font-size: 16px;
            margin: 0;
            font-weight: bold;
        }

        .header h3 {
            font-size: 13px;
            margin: 3px 0;
        }

        .header p {
            font-size: 11px;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }

        .akun-title {
            background-color: #e6e6e6;
            font-weight: bold;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total-row {
            font-weight: bold;
            background-color: #fafafa;
        }
    </style>
</head>
<body>

<div class="header">
<img src="{{ public_path('images/fulllogo.png') }}"
         width="150"
         style="display:block; margin:0 auto 8px auto;">    <h1>LAPORAN BUKU BESAR</h1>
    <h3>Bengkel Kembang Motor</h3>
    <p>Periode {{ request('start_date') }} – {{ request('end_date') }}</p>
</div>

@if($bukuBesar)

@foreach($bukuBesar as $akun => $entries)

<table>
    <thead>
        <tr>
            <th colspan="6" class="akun-title">
                {{ $akun }}
            </th>
        </tr>
        <tr>
            <th width="5%">No</th>
            <th width="15%">Tanggal</th>
            <th width="15%">Kode</th>
            <th width="20%">Debit (Rp)</th>
            <th width="20%">Kredit (Rp)</th>
            <th width="25%">Saldo (Rp)</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1; @endphp

        @forelse ($entries['transaksi'] as $data)
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td class="text-center">{{ $data['tanggal'] }}</td>
                <td class="text-center">{{ $data['kode'] }}</td>
                <td class="text-right">
                    {{ number_format($data['nominal_debit'], 2, ',', '.') }}
                </td>
                <td class="text-right">
                    {{ number_format($data['nominal_kredit'], 2, ',', '.') }}
                </td>
                <td class="text-right">
                    @if($data['normal_post'] == 'Debit')
                        {{ number_format($data['saldo_debit'], 2, ',', '.') }}
                    @else
                        {{ number_format($data['saldo_kredit'], 2, ',', '.') }}
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">
                    Data tidak tersedia
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

@endforeach

@else
<p style="text-align:center;">Silakan pilih laporan untuk menampilkan data.</p>
@endif

</body>
</html>

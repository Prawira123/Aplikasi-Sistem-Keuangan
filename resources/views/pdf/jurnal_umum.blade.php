<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Jurnal Umum</title>

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

        .header img {
            height: 60px;
            margin-bottom: 8px;
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
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background-color: #f0f0f0;
            text-align: center;
            font-weight: bold;
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

        .grand-total {
            font-weight: bold;
            background-color: #ddd;
        }
    </style>
</head>
<body>

<div class="header">
    <img src="{{ public_path('images/fulllogo.png') }}" alt="Logo">
    <h1>LAPORAN JURNAL UMUM</h1>
    <h3>Bengkel Kembang Motor</h3>
    <p>Periode {{ request('start_date') }} – {{ request('end_date') }}</p>
</div>

@if($jurnalUmum['data'] && count($jurnalUmum['data']) > 0)

<table>
    <tr>
        <th>No</th>
        <th>Tanggal</th>
        <th>Kode</th>
        <th>Akun</th>
        <th>Debit (Rp)</th>
        <th>Kredit (Rp)</th>
    </tr>

    @php
        $no = 1;
        $latestHeader = null;
    @endphp
    @forelse ($jurnalUmum['data'] as $data)
        <tr>
            @if($latestHeader != $data->jurnal_header->id)
                <td class="text-center">{{ $no++ }}</td>
                <td class="text-center">{{ $data->jurnal_header->tanggal }}</td>
            @else
                <td></td>
                <td></td>
            @endif
            <td class="text-center">{{ $data->akun->kode }}</td>
            <td>{{ $data->akun->nama }}</td>
            <td class="text-right">{{ number_format($data->nominal_debit, 2, ',', '.') }}</td>
            <td class="text-right">{{ number_format($data->nominal_kredit, 2, ',', '.') }}</td>
        </tr>
        @php $latestHeader = $data->jurnal_header->id; @endphp
    @empty
        <tr>
            <td colspan="6" class="text-center">Data tidak tersedia</td>
        </tr>
    @endforelse

    <tr class="total-row">
        <td colspan="4" class="text-center">Total</td>
        <td class="text-right">{{ number_format($jurnalUmum['total_debit'], 2, ',', '.') }}</td>
        <td class="text-right">{{ number_format($jurnalUmum['total_kredit'], 2, ',', '.') }}</td>
    </tr>
</table>

@else
<p class="text-center" style="margin-top: 20px;">Silakan pilih laporan untuk menampilkan data.</p>
@endif

</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Laba Rugi</title>

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

        td {
            vertical-align: top;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .section-title {
            background-color: #e6e6e6;
            font-weight: bold;
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
    <h1>LAPORAN LABA RUGI</h1>
    <h3>Bengkel Kembang Motor</h3>
    <p>Periode {{ $start_date }} – {{ $end_date }}</p>
</div>

@if($pendapatan && $beban)

<table>
    <thead>
        <tr>
            <th width="5%">No</th>
            <th width="15%">Tanggal</th>
            <th width="10%">Kode</th>
            <th>Nama Akun</th>
            <th width="20%">Jumlah (Rp)</th>
        </tr>
    </thead>

    <tbody>
        {{-- ================= PENDAPATAN ================= --}}
        <tr class="section-title">
            <td colspan="5">PENDAPATAN</td>
        </tr>

        @php $no = 1; @endphp
        @forelse ($pendapatan['data'] as $data)
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td class="text-center">{{ $data['tanggal'][0] ?? '-' }}</td>
                <td class="text-center">{{ $data['kode'] }}</td>
                <td>{{ $data['nama'] }}</td>
                <td class="text-right">{{ number_format($data['total'], 2, ',', '.') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">Data tidak tersedia</td>
            </tr>
        @endforelse

        <tr class="total-row">
            <td colspan="4">Total Pendapatan</td>
            <td class="text-right">
                {{ number_format($pendapatan['total'], 2, ',', '.') }}
            </td>
        </tr>

        {{-- ================= BEBAN ================= --}}
        <tr class="section-title">
            <td colspan="5">BEBAN</td>
        </tr>

        @php $no = 1; @endphp
        @forelse ($beban['data'] as $data)
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td class="text-center">{{ $data['tanggal'][0] ?? '-' }}</td>
                <td class="text-center">{{ $data['kode'] }}</td>
                <td>{{ $data['nama'] }}</td>
                <td class="text-right">{{ number_format($data['total'], 2, ',', '.') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">Data tidak tersedia</td>
            </tr>
        @endforelse
        <tr class="total-row">
            <td colspan="4">HPP</td>
            <td class="text-right">
                {{ number_format($beban['hpp'], 2, ',', '.') }}
            </td>
        </tr>

        <tr class="total-row">
            <td colspan="4">Total Beban</td>
            <td class="text-right">
                {{ number_format($beban['total'] + $beban['hpp'], 2, ',', '.') }}
            </td>
        </tr>

        {{-- ================= LABA / RUGI ================= --}}
        <tr class="grand-total">
            <td colspan="4">
                {{ ($pendapatan['total'] - ($beban['total'] + $beban['hpp'])) >= 0 ? 'LABA BERSIH' : 'RUGI BERSIH' }}
            </td>
            <td class="text-right">
                {{ number_format($pendapatan['total'] - ($beban['total'] + $beban['hpp']), 2, ',', '.') }}
            </td>
        </tr>

    </tbody>
</table>

@else
<p style="text-align:center;">Silakan pilih laporan untuk menampilkan data.</p>
@endif

</body>
</html>

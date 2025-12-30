<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Neraca</title>

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

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
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
    <h1>LAPORAN NERACA</h1>
    <h3>Bengkel Kembang Motor</h3>
    <p>Periode {{ $start_date }} – {{ $end_date }}</p>
</div>

@if($asetLancar && $asetTetap && $kewajiban && $ekuitas)

<table>
    <thead>
        <tr>
            <th width="5%">No</th>
            <th width="15%">Tanggal</th>
            <th width="10%">Kode</th>
            <th>Nama Akun</th>
            <th width="20%">Saldo (Rp)</th>
        </tr>
    </thead>

    <tbody>
        {{-- ================= ASET TETAP ================= --}}
        <tr class="section-title">
            <td colspan="5">ASET TETAP</td>
        </tr>

        @php $no = 1; @endphp
        @forelse ($asetTetap['data'] as $data)
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
            <td colspan="4">Total Aset Tetap</td>
            <td class="text-right">{{ number_format($asetTetap['total'], 2, ',', '.') }}</td>
        </tr>

        {{-- ================= ASET LANCAR ================= --}}
        <tr class="section-title">
            <td colspan="5">ASET LANCAR</td>
        </tr>

        @php $no = 1; @endphp
        @forelse ($asetLancar['data'] as $data)
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
            <td colspan="4">Total Aset Lancar</td>
            <td class="text-right">{{ number_format($asetLancar['total'], 2, ',', '.') }}</td>
        </tr>

        <tr class="grand-total">
            <td colspan="4">TOTAL AKTIVA</td>
            <td class="text-right">
                {{ number_format($asetTetap['total'] + $asetLancar['total'], 2, ',', '.') }}
            </td>
        </tr>

        {{-- ================= KEWAJIBAN ================= --}}
        <tr class="section-title">
            <td colspan="5">KEWAJIBAN</td>
        </tr>

        @php $no = 1; @endphp
        @forelse ($kewajiban['data'] as $data)
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
            <td colspan="4">Total Kewajiban</td>
            <td class="text-right">{{ number_format($kewajiban['total'], 2, ',', '.') }}</td>
        </tr>

        {{-- ================= EKUITAS ================= --}}
        <tr class="section-title">
            <td colspan="5">EKUITAS</td>
        </tr>

        @php $no = 1; @endphp
        @forelse ($ekuitas['data'] as $data)
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
            <td colspan="4">Total Ekuitas</td>
            <td class="text-right">{{ number_format($ekuitas['total'], 2, ',', '.') }}</td>
        </tr>

        <tr class="grand-total">
            <td colspan="4">TOTAL PASIVA</td>
            <td class="text-right">
                {{ number_format($kewajiban['total'] + $ekuitas['total'], 2, ',', '.') }}
            </td>
        </tr>

    </tbody>
</table>

@else
<p style="text-align:center;">Silakan pilih laporan untuk menampilkan data.</p>
@endif

</body>
</html>

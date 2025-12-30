<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Arus Kas</title>

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

        .section-title {
            background-color: #e6e6e6;
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
    <h1>LAPORAN ARUS KAS</h1>
    <h3>Bengkel Kembang Motor</h3>
    <p>Periode {{ request('start_date') }} – {{ request('end_date') }}</p>
</div>

@if($arusKas)

<table>
    {{-- ================= SALDO AWAL ================= --}}
    <tr class="section-title">
        <td colspan="3">SALDO AWAL KAS</td>
    </tr>
    <tr>
        <th>Akun</th>
        <th colspan="2">Saldo (Rp)</th>
    </tr>
    <tr>
        <td>{{ $arusKas['akun_kas']->nama ?? 'KAS' }}</td>
        <td colspan="2" class="text-right">
            {{ number_format($arusKas['saldo_awal'] ?? 0, 2, ',', '.') }}
        </td>
    </tr>

    {{-- ================= OPERASIONAL ================= --}}
    <tr class="section-title">
        <td colspan="3">ARUS KAS DARI AKTIVITAS OPERASIONAL</td>
    </tr>
    <tr>
        <th width="10%">No</th>
        <th>Akun</th>
        <th width="25%">Jumlah (Rp)</th>
    </tr>

    @php $no = 1; @endphp
    @forelse ($arusKas['kas_operasional'] as $data)
        <tr>
            <td class="text-center">{{ $no++ }}</td>
            <td>{{ $data->akun }}</td>
            <td class="text-right">{{ number_format($data->total, 2, ',', '.') }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="3" class="text-center">Data tidak tersedia</td>
        </tr>
    @endforelse

    <tr class="total-row">
        <td colspan="2">Total Kas Operasional</td>
        <td class="text-right">
            {{ number_format($arusKas['total_kas_operasional'], 2, ',', '.') }}
        </td>
    </tr>

    {{-- ================= INVESTASI ================= --}}
    <tr class="section-title">
        <td colspan="3">ARUS KAS DARI AKTIVITAS INVESTASI</td>
    </tr>

    @php $no = 1; @endphp
    @forelse ($arusKas['kas_investasi'] as $data)
        <tr>
            <td class="text-center">{{ $no++ }}</td>
            <td>{{ $data->akun }}</td>
            <td class="text-right">{{ number_format($data->total, 2, ',', '.') }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="3" class="text-center">Data tidak tersedia</td>
        </tr>
    @endforelse

    <tr class="total-row">
        <td colspan="2">Total Kas Investasi</td>
        <td class="text-right">
            {{ number_format($arusKas['total_kas_investasi'], 2, ',', '.') }}
        </td>
    </tr>

    {{-- ================= PENDANAAN ================= --}}
    <tr class="section-title">
        <td colspan="3">ARUS KAS DARI AKTIVITAS PENDANAAN</td>
    </tr>

    @php $no = 1; @endphp
    @forelse ($arusKas['kas_pendanaan'] as $data)
        <tr>
            <td class="text-center">{{ $no++ }}</td>
            <td>{{ $data->akun }}</td>
            <td class="text-right">{{ number_format($data->total, 2, ',', '.') }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="3" class="text-center">Data tidak tersedia</td>
        </tr>
    @endforelse

    <tr class="total-row">
        <td colspan="2">Total Kas Pendanaan</td>
        <td class="text-right">
            {{ number_format($arusKas['total_kas_pendanaan'], 2, ',', '.') }}
        </td>
    </tr>

    {{-- ================= PERGERAKAN ================= --}}
    <tr class="grand-total">
        <td colspan="2">KENAIKAN / (PENURUNAN) KAS</td>
        <td class="text-right">
            {{ number_format($arusKas['pergerakan_bersih'], 2, ',', '.') }}
        </td>
    </tr>

    {{-- ================= SALDO AKHIR ================= --}}
    <tr class="grand-total">
        <td colspan="2">SALDO AKHIR KAS</td>
        <td class="text-right">
            {{ number_format($arusKas['saldo_akhir'], 2, ',', '.') }}
        </td>
    </tr>

</table>

@else
<p style="text-align:center;">Silakan pilih laporan untuk menampilkan data.</p>
@endif

</body>
</html>

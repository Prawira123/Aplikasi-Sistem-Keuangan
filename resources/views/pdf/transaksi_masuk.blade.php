<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi Masuk</title>

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
    </style>
</head>
<body>

<div class="header">
    <img src="{{ public_path('images/fulllogo.png') }}" alt="Logo">
    <h1>LAPORAN PENJUALAN</h1>
    <h3>Bengkel Kembang Motor</h3>
    <p>Periode {{ request('start_date') }} – {{ request('end_date') }}</p>
</div>

@if($transaksiMasuk['data'] && count($transaksiMasuk['data']) > 0)

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Barang</th>
            <th>Jasa</th>
            <th>Paket</th>
            <th>Saldo (Rp)</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1; @endphp
        @forelse ($transaksiMasuk['data'] as $data)
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td class="text-center">{{ $data->tanggal }}</td>
                <td>{{ $data->product->nama ?? '-' }}</td>
                <td>{{ $data->jasa->nama ?? '-' }}</td>
                <td>{{ $data->paket->nama ?? '-' }}</td>
                <td class="text-right">Rp.{{ number_format($data->harga_total, 2, ',', '.') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">Data tidak tersedia</td>
            </tr>
        @endforelse

        <tr class="total-row">
            <td colspan="5" class="text-center">Total Transaksi Masuk</td>
            <td class="text-right">Rp.{{ number_format($transaksiMasuk['total'], 2, ',', '.') }}</td>
        </tr>
    </tbody>
</table>

@else
<p class="text-center" style="margin-top: 20px;">Silakan pilih laporan untuk menampilkan data.</p>
@endif

</body>
</html>

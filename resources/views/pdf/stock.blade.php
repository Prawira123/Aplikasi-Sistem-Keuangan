<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kartu Stock</title>

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
            margin-bottom: 20px;
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

        .section-title {
            background-color: #e6e6e6;
            font-weight: bold;
            text-align: left;
            padding-left: 6px;
        }
    </style>
</head>
<body>

<div class="header">
    <img src="{{ public_path('images/fulllogo.png') }}" alt="Logo">
    <h1>LAPORAN KARTU STOCK</h1>
    <h3>Bengkel Kembang Motor</h3>
    <p>Periode {{ request('start_date') }} – {{ request('end_date') }}</p>
</div>

@if($laporanStock && count($laporanStock) > 0)

@foreach($laporanStock as $product => $entries)
<table>
    <thead>
        <tr>
            <th colspan="6" class="section-title">{{ $product }}</th>
        </tr>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Stock Awal</th>
            <th>Stock Masuk</th>
            <th>Stock Keluar</th>
            <th>Stock Akhir</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1; @endphp
        @forelse ($entries['kartu_stok'] as $data)
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td class="text-center">{{ $data['tanggal'] }}</td>
                <td class="text-center">{{ $data['stok_awal'] }}</td>
                <td class="text-center">{{ $data['masuk'] }}</td>
                <td class="text-center">{{ $data['keluar'] }}</td>
                <td class="text-center">{{ $data['stok_akhir'] }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">Data tidak tersedia</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endforeach

@else
<p class="text-center" style="margin-top: 20px;">Silakan pilih laporan untuk menampilkan data.</p>
@endif

</body>
</html>

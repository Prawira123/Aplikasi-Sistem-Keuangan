<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice Transaksi</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
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

        .info {
            margin-bottom: 15px;
        }

        .info table {
            width: 100%;
        }

        .info td {
            padding: 4px 0;
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

        .footer {
            margin-top: 30px;
            text-align: right;
        }
    </style>
</head>
<body>

<div class="header">
    <img src="{{ public_path('images/fulllogo.png') }}" alt="Logo">
    <h1>INVOICE</h1>
    <h3>Bengkel Kembang Motor</h3>
    <p>Kode Transaksi : {{ $transaksi_masuk->kode }}</p>
</div>

<div class="info">
    <table>
        <tr>
            <td width="20%">Tanggal</td>
            <td width="30%">: {{ $transaksi_masuk->tanggal }}</td>
            <td width="20%">Karyawan</td>
            <td width="30%">: {{ $transaksi_masuk->karyawan->fullname }}</td>
        </tr>
        <tr>
            <td>Pelanggan</td>
            <td>: {{ $transaksi_masuk->pelanggan->nama }}</td>
            <td></td>
            <td></td>
        </tr>
    </table>
</div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Item</th>
            <th>Jenis</th>
            <th>Qty</th>
            <th>Harga Satuan (Rp)</th>
            <th>Total (Rp)</th>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 1;
            $total = 0;
        @endphp

        {{-- BARANG --}}
        @if($transaksi_masuk->product)
            @php
                $qty = $transaksi_masuk->qty;
                $subtotal = $qty * $transaksi_masuk->harga_satuan;
                $total += $subtotal;
            @endphp
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td>{{ $transaksi_masuk->product->nama }}</td>
                <td class="text-center">Barang</td>
                <td class="text-center">{{ $qty }}</td>
                <td class="text-right">{{ number_format($transaksi_masuk->harga_satuan, 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($subtotal, 2, ',', '.') }}</td>
            </tr>
        @endif

        @if($transaksi_masuk->jasa)
            @php
                $subtotal = $transaksi_masuk->harga_total;
                $total += $subtotal;
            @endphp
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td>{{ $transaksi_masuk->jasa->nama }}</td>
                <td class="text-center">Jasa</td>
                <td class="text-center">1</td>
                <td class="text-right">{{ number_format($transaksi_masuk->harga_total, 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($subtotal, 2, ',', '.') }}</td>
            </tr>
        @endif
        
        @if($transaksi_masuk->paket)
            @php
                $subtotal = $transaksi_masuk->harga_total;
                $total += $subtotal;
            @endphp
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td>{{ $transaksi_masuk->paket->nama }}</td>
                <td class="text-center">Paket</td>
                <td class="text-center">1</td>
                <td class="text-right">{{ number_format($transaksi_masuk->harga_total, 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($subtotal, 2, ',', '.') }}</td>
            </tr>
        @endif

        <tr class="total-row">
            <td colspan="5" class="text-center">TOTAL BAYAR</td>
            <td class="text-right">Rp {{ number_format($total, 2, ',', '.') }}</td>
        </tr>
    </tbody>
</table>

<div class="footer">
    <p>Dicetak pada {{ now()->format('d-m-Y H:i') }}</p>
</div>

</body>
</html>

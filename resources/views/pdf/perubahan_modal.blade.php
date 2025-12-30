<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Perubahan Modal</title>

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
    <h1>LAPORAN PERUBAHAN MODAL</h1>
    <h3>Bengkel Kembang Motor</h3>
    <p>Periode {{ request('start_date') }} – {{ request('end_date') }}</p>
</div>

@if($perubahanModal)

<table>
    <tr>
        <td>Modal Awal</td>
        <td class="text-right">Rp.{{ number_format($perubahanModal['modal_awal'], 2, ',', '.') }}</td>
    </tr>
    <tr>
        <td>Laba Bersih</td>
        <td class="text-right">Rp.{{ number_format($perubahanModal['laba_bersih'], 2, ',', '.') }}</td>
    </tr>
    <tr>
        <td>Penambahan Modal</td>
        <td class="text-right">Rp.{{ number_format($perubahanModal['penambahan_modal'], 2, ',', '.') }}</td>
    </tr>
    <tr>
        <td>Pengambilan Pribadi (Prive)</td>
        <td class="text-right">Rp.{{ number_format($perubahanModal['prive'], 2, ',', '.') }}</td>
    </tr>
    <tr class="total-row">
        <td>Modal Akhir</td>
        <td class="text-right">Rp.{{ number_format($perubahanModal['modal_akhir'], 2, ',', '.') }}</td>
    </tr>
</table>

@else
<p class="text-center" style="margin-top: 20px;">Silakan pilih laporan untuk menampilkan data.</p>
@endif

</body>
</html>

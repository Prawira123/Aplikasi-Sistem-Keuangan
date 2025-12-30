@props(['datas' => null,'perubahanModal' => null,  'route' => '', 'route_excel' => ''])

<div class="row" class="mt-4">

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
        <tbody>
            <tr>
                <td>Modal Awal</td>
                <td>Rp.{{ number_format($perubahanModal['modal_awal'], 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Penambahan Modal</td>
                <td>Rp.{{ number_format($perubahanModal['penambahan_modal'], 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Pengambilan Pribadi (Prive)</td>
                <td>- Rp.{{ number_format($perubahanModal['prive'], 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Laba Bersih</td>
                <td>Rp.{{ number_format($perubahanModal['laba_bersih'],  2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Modal Akhir</td>
                <td>Rp.{{ number_format($perubahanModal['modal_akhir'],  2, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</div>
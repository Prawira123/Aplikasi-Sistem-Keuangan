@extends('layouts.dashboard')

@section('content')
<header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
        <i class="bi bi-justify fs-3"></i>
    </a>
</header>
            
<div class="page-heading">
    <h3>Halaman Laporan</h3>
</div> 

<div id="main w-full">  
    <div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tabel Laporan</h3>
                <p class="text-subtitle text-muted">Mengelola Laporan</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="">Laporan</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Index</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-10" id="title">
                    Data Laporan
                </h5>
                <form action="{{ route('laporans.index') }}" method="GET" class="mb-4">
                    <div class="col-md-12">
                        <h6>Laporan</h6>
                        <fieldset class="form-group">
                            <select class="form-select" id="laporan_list" name="laporan">
                                <option value="">Pilih Laporan</option>
                                <option value="neraca">Neraca</option>
                                <option value="laba_rugi">Laba Rugi</option>
                                <option value="arus_kas">Arus Kas</option>
                                <option value="buku_besar">Buku Besar</option>
                                <option value="jurnal_umum">Jurnal Umum</option>
                                <option value="perubahan_modal">Perubahan Modal</option>
                                <option value="transaksi_masuk">Transaksi Masuk</option>
                                <option value="transaksi_keluar">Transaksi keluar</option>
                                <option value="laporan_stock">Laporan Stock</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <input type="date" name="start_date" class="form-control date" placeholder="start date"
                                value="{{ request('start_date') }}">
                        </div>

                        <div class="col-md-4">
                            <input type="date" name="end_date" class="form-control date" placeholder="end date"
                                value="{{ request('end_date') }}">
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-primary w-100" id="submit">Submit</button>
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <a href="{{ route('laporans.index') }}" class="btn btn-secondary w-100">
                                Reset
                            </a>    
                        </div>
                    </div>
                </form>
            </div>
            
             @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            <div class="card-body">
                @if (request('laporan') == 'jurnal_umum')
                    @php
                        if (is_array($datas)) {
                            extract($datas);
                        }
                    @endphp
                    <div class="card {{ request('laporan') == 'jurnal_umum' ? '' : 'd-none' }}" id="jurnal_umum">
                        <x-table_laporan.jurnal_umum :datas="$datas" :jurnalUmum="$jurnalUmum ?? null" :route="'laporans.export.jurnal_umum'" :route_excel="'laporans.export.excel.jurnal_umum'" ></x-table_laporan.jurnal_umum>
                    </div>
                @endif
                @if (request('laporan') == 'neraca')
                    @php
                        if (is_array($datas)) {
                            extract($datas);
                        }
                    @endphp
                    <div class="card" id="neraca">
                    <x-table_laporan.neraca :datas="$datas" :asetTetap="$asetTetap ?? null" :asetLancar="$asetLancar ?? null" :kewajiban="$kewajiban ?? null" :ekuitas="$ekuitas ?? null" :route="'laporans.export.neraca'" :route_excel="'laporans.export.excel.neraca'"></x-table_laporan.neraca>
                </div>
                @endif
                @if (request('laporan') == 'buku_besar')
                    @php
                        if (is_array($datas)) {
                            extract($datas);
                        }
                    @endphp
                    <div class="card" id="buku_besar">
                        <x-table_laporan.buku_besar :datas="$datas" :bukuBesar="$bukuBesar ?? null" :route="'laporans.export.buku_besar'" :route_excel="'laporans.export.excel.buku_besar'"></x-table_laporan.buku_besar>
                    </div>
                @endif
                @if (request('laporan') == 'laba_rugi')
                    @php
                        if (is_array($datas)) {
                            extract($datas);
                        }
                    @endphp
                    <div class="card {{ request('laporan') == 'laba_rugi' ? '' : 'd-none' }}" id="laba_rugi">
                        <x-table_laporan.laba_rugi :datas="$datas" :pendapatan="$pendapatan ?? null" :beban="$beban ?? null" :route="'laporans.export.laba_rugi'" :route_excel="'laporans.export.excel.laba_rugi'"></x-table_laporan.laba_rugi>
                    </div>
                @endif
                @if (request('laporan') == 'arus_kas')
                    @php
                        if (is_array($datas)) {
                            extract($datas);
                        }
                    @endphp
                    <div class="card {{ request('laporan') == 'arus_kas' ? '' : 'd-none' }}" id="arus_kas">
                        <x-table_laporan.arus_kas :datas="$datas" :arusKas="$arusKas ?? null" :route="'laporans.export.arus_kas'" :route_excel="'laporans.export.excel.arus_kas'" ></x-table_laporan.arus_kas>
                    </div>
                @endif
                @if (request('laporan') == 'perubahan_modal')
                    @php
                        if (is_array($datas)) {
                            extract($datas);
                        }
                    @endphp
                    <div class="card {{ request('laporan') == 'perubahan_modal' ? '' : 'd-none' }}" id="perubahan_modal">
                        <x-table_laporan.perubahan_modal :datas="$datas" :perubahanModal="$perubahanModal ?? null" :route="'laporans.export.perubahan_modal'" :route_excel="'laporans.export.excel.perubahan_modal'" ></x-table_laporan.perubahan_modal>
                    </div>
                @endif
                @if (request('laporan') == 'transaksi_masuk')
                    @php
                        if (is_array($datas)) {
                            extract($datas);
                        }
                    @endphp
                    <div class="card {{ request('laporan') == 'transaksi_masuk' ? '' : 'd-none' }}" id="transaksi_masuk">
                        <x-table_laporan.transaksi_masuk :datas="$datas" :transaksiMasuk="$transaksiMasuk ?? null" :route="'laporans.export.transaksi_masuk'" :route_excel="'laporans.export.excel.transaksi_masuk'"></x-table_laporan.transaksi_masuk>
                    </div>
                @endif
                @if (request('laporan') == 'transaksi_keluar')
                    @php
                        if (is_array($datas)) {
                            extract($datas);
                        }
                    @endphp
                    <div class="card {{ request('laporan') == 'transaksi_keluar' ? '' : 'd-none' }}" id="transaksi_keluar">
                        <x-table_laporan.transaksi_keluar :datas="$datas" :transaksiKeluar="$transaksiKeluar ?? null" :route="'laporans.export.transaksi_keluar'" :route_excel="'laporans.export.excel.transaksi_keluar'"></x-table_laporan.transaksi_keluar>
                    </div>
                @endif
                @if (request('laporan') == 'laporan_stock')
                    @php
                        if (is_array($datas)) {
                            extract($datas);
                        }
                    @endphp
                    <div class="card {{ request('laporan') == 'laporan_stock' ? '' : 'd-none' }}" id="laporan_stock">
                        <x-table_laporan.laporan_stock :datas="$datas" :laporanStock="$laporanStock ?? null" :route="'laporans.export.stock'" :route_excel="'laporans.export.excel.laporan_stock'"></x-table_laporan.laporan_stock>
                    </div>
                @endif
            </div>
        </div>
    </section>
    </div>
</div>

<script>
    const laporanList = document.getElementById('laporan_list');

    // Saat halaman dibuka ulang (after submit), tampilkan sesuai laporan terpilih
    document.addEventListener('DOMContentLoaded', function() {
        const laporan = "{{ request('laporan') }}";

        // Set select box ke nilai yang dipilih
        laporanList.value = laporan;

        // Update judul berdasarkan laporan (tanpa menyembunyikan div, karena Blade sudah menangani)
        if (laporan) {
            document.getElementById('title').innerHTML = "Data Laporan " + laporan.replace('_', ' ');
        }
    });

    // Ketika user pilih laporan → langsung submit form
    laporanList.addEventListener('change', function () {
        this.form.submit();
    });
</script>

@endsection
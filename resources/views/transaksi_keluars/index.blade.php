@extends('layouts.dashboard')

@section('content')
<header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
        <i class="bi bi-justify fs-3"></i>
    </a>
</header>
            
<div class="page-heading">
    <h3>Halaman Pembelian</h3>
</div> 

<div id="main w-full"> 
    <div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12">
                <div class="row">
                     <div class="col-6 col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                        <div class="stats-icon purple mb-2">
                                            <i class="iconly-boldShow"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Pengeluaran</h6>
                                        <h6 class="font-extrabold mb-0 text-sm">Rp.{{ number_format($transaksi_keluars->sum('harga_total'), 0, ',', '.') }}</h6>
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-4 col-md-6">
                        <div class="card"> 
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                        <div class="stats-icon blue mb-2">
                                            <i class="iconly-boldProfile"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Pembelian</h6>
                                        <h6 class="font-extrabold mb-0">{{ $transaksi_keluars->count() }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                        <div class="stats-icon green mb-2">
                                            <i class="iconly-boldAdd-User"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Total Barang</h6>
                                        <h6 class="font-extrabold mb-0">{{ $transaksi_keluars->sum('qty') }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tabel Pembelian</h3>
                <p class="text-subtitle text-muted">Mengelola Pembelian</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="">Pembelian</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Index</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Data Pembelian
                </h5>
            </div>
            <div class="card p-4">
                <div class="row g-3">
                    <form action="{{ route('transaksi_keluars.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label>Bulan</label>
                            <select id="filter-month" class="form-control" name="bulan">
                                <option value="">Pilih Bulan</option>
                                @foreach([
                                    1 => 'Januari',
                                    2 => 'Februari',
                                    3 => 'Maret',
                                    4 => 'April',
                                    5 => 'Mei',
                                    6 => 'Juni',
                                    7 => 'Juli',
                                    8 => 'Agustus',
                                    9 => 'September',
                                    10 => 'Oktober',
                                    11 => 'November',
                                    12 => 'Desember'
                                ] as $num => $bulan)
                                    <option value="{{ $num }}" @if(old('bulan', request('bulan')) == $num) selected @endif>{{ $bulan }}</option>
                                @endforeach
                            </select> 
                        </div>
                        <div class="col-md-3">
                            <label>Tahun</label>
                            <select id="filter-year" class="form-control" name="tahun">
                                <option value="">Pilih Tahun</option>
                                @for($i = date('Y'); $i >= 2000; $i--)
                                    <option value="{{ $i }}" @if(old('tahun', request('tahun')) == $i) selected @endif>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button id="btn-filter" class="btn btn-primary w-100" type="submit">Filter</button>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <a href="{{ route('transaksi_keluars.index') }}" id="btn-reset" class="btn btn-secondary w-100">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
             @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            <div class="card-body">
                <div class="d-flex">
                    <a href="{{ route('transaksi_keluars.create') }}" class="btn btn-primary mb-3 ms-auto">Tambah Transaksi Pembelian</a>
                </div>
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Barang</th>
                            <th>Supplier</th>
                            <th>Qty</th>
                            <th>Tanggal</th>
                            <th>Harga Satuan</th>
                            <th>Harga Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaksi_keluars as $transaksi_keluar )
                            <tr class="align-middle">
                            <td >{{ $transaksi_keluar->kode }}</td>
                            <td >{{ $transaksi_keluar->product->nama }}</td>
                            <td >{{ $transaksi_keluar->supplier->nama }}</td>
                            <td >{{ $transaksi_keluar->qty }}</td>
                            <td >{{ \Carbon\Carbon::parse($transaksi_keluar->tanggal)->format('d F Y') }}</td>
                            <td >{{ 'Rp. ' . number_format($transaksi_keluar->harga_satuan, 2, ',', '.') }}</td>
                            <td >{{ 'Rp. ' . number_format($transaksi_keluar->harga_total, 2, ',', '.') }}</td>
                            <td class="d-flex gap-2 h-100 d-flex-wrap">
                                <a href="{{ route('transaksi_keluars.show', $transaksi_keluar->id) }}" class="btn btn-info btn-sm">Detail</a>
                                <a href="{{ route('transaksi_keluars.edit', $transaksi_keluar->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('transaksi_keluars.destroy', $transaksi_keluar->id) }}" method="post" class="d-flex">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Yakin untuk menghapus data Pembelian ini?')" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    </div>
</div>

@endsection


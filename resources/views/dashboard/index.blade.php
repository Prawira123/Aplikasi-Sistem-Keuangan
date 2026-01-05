@extends('layouts.dashboard')

@section('content')
<header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
        <i class="bi bi-justify fs-3"></i>
    </a>
</header>
            
<div class="page-heading">
    <h3>Dashboard</h3>
</div> 
<div class="page-content"> 
    <section class="row">
        <div class="col-12 col-lg-9">
            <div class="row">
                <div class="col-6 col-lg-4 col-md-6">
                    <a href="{{ route('transaksi_masuks.index') }}">
                        <div class="card bg-success">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-12">
                                        <h6 class=" font-semibold text-white">Total Pemasukan</h6>
                                        <h6 class="font-extrabold mb-0 text-white">Rp.{{ number_format($transaksi_masuks, 0, ',', '.' ) }}</h6>
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-lg-4 col-md-6">
                    <a href="{{ route('transaksi_keluars.index') }}">
                        <div class="card bg-danger"> 
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-12">
                                        <h6 class=" font-semibold text-white">Total Pengeluaran</h6>
                                        <h6 class="font-extrabold mb-0 text-white">Rp.{{ number_format($transaksi_keluars, 0, ',', '.' ) }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-lg-4 col-md-6">
                    <div class="card bg-warning">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-12">
                                    <h6 class=" font-semibold text-white">Total Likuiditas</h6>
                                    <h6 class="font-extrabold mb-0 text-white">Rp.{{ number_format($likuiditas, 0, ',', '.' ) }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Pendapatan Per Bulan</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="chart-perdapatan-perbulan"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-xl-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Banyak Item</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-7">
                                    <div class="d-flex align-items-center">
                                        <svg class="bi text-primary" width="32" height="32" fill="blue"
                                            style="width:10px">
                                            <use
                                                xlink:href="assets/static/images/bootstrap-icons.svg#circle-fill" />
                                        </svg>
                                        <h5 class="mb-2 ms-3">Barang</h5>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <h5 class="mb-0 text-end text-primary">{{ $products }}</h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-7">
                                    <div class="d-flex align-items-center">
                                        <svg class="bi text-success" width="32" height="32" fill="blue"
                                            style="width:10px">
                                            <use
                                                xlink:href="assets/static/images/bootstrap-icons.svg#circle-fill" />
                                        </svg>
                                        <h5 class="mb-2 ms-3">Jasa</h5>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <h5 class="mb-0 text-end text-primary">{{$jasas}}</h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-7">
                                    <div class="d-flex align-items-center">
                                        <svg class="bi text-danger" width="32" height="32" fill="blue"
                                            style="width:10px">
                                            <use
                                                xlink:href="assets/static/images/bootstrap-icons.svg#circle-fill" />
                                        </svg>
                                        <h5 class="mb-2 ms-3">Paket</h5>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <h5 class="mb-0 text-end text-primary">{{ $pakets }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xl-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>Pembelian Terbaru</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-lg">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Barang</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pembelian as $transaksi )
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('Y-m-d') }}</td>
                                                <td>{{ $transaksi->product->nama }}</td>
                                                <td>Rp.{{ number_format($transaksi->harga_total, 2,',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-3">
            <div class="card">
                <div class="card-body py-4 px-4">
                    <div class="d-flex align-items-center">
                        <div class="ms-3 name">
                            <h1 class=" font-bold">{{ $user->role }}</h5>
                            <h6 class="font-bold">{{ '@'.$user->name }}</h6>
                            <p class="text-sm">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header bg-primary">
                    <h4 class="text-white">Daftar Pelanggan</h4>
                </div>
                <div class="card-content pb-4">
                    @foreach ($pelanggans as $pelanggan)
                        <div class="recent-message d-flex px-4 py-3">
                            <div class="name ms-4">
                                <h2 class="text-sm">{{ $pelanggan->nama }}</h2>
                                <p class="text-muted text-sm mb-0">{{ $pelanggan->transaksi->count() }} - Transaksi</p>
                            </div>
                        </div>
                    @endforeach
                    <div class="px-4">
                        <a href="{{ route('transaksi_masuks.index') }}"><button class='btn btn-block btn-xl btn-outline-primary font-bold mt-3'>Lihat Transaksi</button></a>
                    </div>
                </div>
            </div> 
            <div class="card">
                <div class="card-header">
                    <h4>Penjualan Total Detail</h4>
                </div>
                <div class="card-body">
                    <canvas id="chart-donut-penjualan"></canvas>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection


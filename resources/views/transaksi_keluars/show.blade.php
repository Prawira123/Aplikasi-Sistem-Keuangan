@extends('layouts.dashboard')

@section('content')
<header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
        <i class="bi bi-justify fs-3"></i>
    </a>
</header>
            
<div class="page-heading">
    <h3>Transaksi {{ \Carbon\Carbon::parse($transaksi_keluar->tanggal)->format('d F Y') }}</h3>
</div> 

<div id="main w-full">   
    <div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>{{ $transaksi_keluar->product->nama }}</h3>
                <p class="text-subtitle text-muted">Detail Transaksi Keluar</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="">Transaksi Keluar</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header d-flex gap-4">
                <h4 class="card-title">{{ $transaksi_keluar->supplier->nama }}</h4>
                <p>|</p>
                <p class="text-info"> <span class="text-white">Kode Transaksi -</span> {{ $transaksi_keluar->kode}}</p>
            </div>
            <div class="card-body d-flex justify-content-between">
                <p>Debit : {{ $transaksi_keluar->akun_debit->nama }}</p>
            </div>
            <div class="card-body d-flex justify-content-between">
                <p>Kredit : {{ $transaksi_keluar->akun_kredit->nama }}</p>
            </div>
            <div class="card-body d-flex justify-content-between">
                <p>Qty : {{ $transaksi_keluar->qty }}</p>
            </div>
            <div class="card-body d-flex justify-content-between">
                <p>Harga satuan : Rp. {{number_format($transaksi_keluar->harga_satuan, 2, ',', '.')}}</p>
            </div>
            <div class="card-body d-flex justify-content-between">
                <h6>Total : <span class="text-info">{{ 'Rp. ' . number_format($transaksi_keluar->harga_total, 2, ',', '.') }}</span></h6>
            </div>

            <div class="card-footer">
                <a href="{{ route('transaksi_keluars.index') }}" class="btn btn-primary">Kembali</a>
            </div>
        </div>
    </section>
    </div>
</div>

@endsection



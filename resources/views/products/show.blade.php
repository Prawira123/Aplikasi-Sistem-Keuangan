@extends('layouts.dashboard')

@section('content')
<header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
        <i class="bi bi-justify fs-3"></i>
    </a>
</header>
            
<div class="page-heading">
    <h3>Halaman Product</h3>
</div> 

<div id="main w-full">   
    <div class="page-heading">
    <div class="page-title">
        <div class="row">

            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tabel Product</h3>
                <p class="text-subtitle text-muted">Mengelola Product</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="">Product</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header d-flex gap-4">
                <h6>Nama : <span class="text-info">{{ $product->nama }}</span></h6>
            </div>
            <div class="card-body d-flex justify-content-between">
                <h6>Kategori : <span class="text-info">{{ $product->kategori }}</span></h6>
            </div>
            <div class="card-body d-flex justify-content-between">
                <h6>Harga Awal : <span class="text-info">Rp. {{ number_format($product->harga_awal ?? 0, 2, ',', '.') }}</span></h6>
            </div>
            <div class="card-body d-flex justify-content-between">
                <h6>Harga Jual : <span class="text-info">Rp. {{ number_format($product->harga, 2, ',', '.') }}</span></h6>
            </div>
            <div class="card-body d-flex justify-content-between">
                <h6>Stock : <span class="text-info">{{ $product->stock > 0 ? $product->stock . ' pcs' : '0 pcs' }}</span></h6>
            </div>
            <div class="card-body d-flex justify-content-between">
                <h6>Tanggal dibuat : <span class="text-info">{{ \Carbon\Carbon::parse($product->created_at)->format('d F Y') }}</span></h6>
            </div>
            <div class="card-footer">
                <a href="{{ route('products.index') }}" class="btn btn-primary">Kembali</a>
            </div>
        </div>
</section>
</div>
</div>

@endsection

               
               
               
               

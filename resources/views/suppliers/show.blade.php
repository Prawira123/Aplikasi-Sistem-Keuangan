@extends('layouts.dashboard')

@section('content')
<header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
        <i class="bi bi-justify fs-3"></i>
    </a>
</header>
            
<div class="page-heading">
    <h3>Halaman Supplier</h3>
</div> 

<div id="main w-full">  
    <div class="page-heading">
    <div class="page-title">
        <div class="row">

            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tabel Supplier</h3>
                <p class="text-subtitle text-muted">Mengelola Supplier</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="">Supplier</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header d-flex gap-4">
                <h6>Nama : <span class="text-info">{{ $supplier->nama }}</span></h6>
            </div>
            <div class="card-body d-flex justify-content-between">
                <h6>No Telp : <span class="text-info">{{ $supplier->no_tlp }}</span></h6>
            </div>
            <div class="card-body d-flex justify-content-between">
                <h6>Alamat : <span class="text-info">{{ $supplier->alamat }}</span></h6>
            </div>
            <div class="card-body d-flex justify-content-between">
                <h6>Tanggal dibuat : <span class="text-info">{{ \Carbon\Carbon::parse($supplier->created_at)->format('d F Y') }}</span></h6>
            </div>
            <div class="card-footer">
                <a href="{{ route('suppliers.index') }}" class="btn btn-primary">Kembali</a>
            </div>
        </div>
</section>
</div>
</div>

@endsection

               
               
               
               

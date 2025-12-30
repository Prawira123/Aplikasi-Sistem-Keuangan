@extends('layouts.dashboard')

@section('content')
<header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
        <i class="bi bi-justify fs-3"></i>
    </a>
</header>
            
<div class="page-heading">
    <h3>{{ $karyawan->fullname }}</h3>
</div> 

<div id="main w-full">  
    <div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>{{ $karyawan->name }}</h3>
                <p class="text-subtitle text-muted">Detail Karyawan</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="">Karyawan</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header d-flex gap-4">
                <h4 class="card-title">{{ $karyawan->fullname }}</h4>
                <p>|</p>
                <p class="text-info"> <span class="text-white">Diterima -</span> {{ \Carbon\Carbon::parse($karyawan->hire_date)->format('d F Y') }}</p>
            </div>
            <div class="card-body d-flex justify-content-between">
                <p>{{ $karyawan->address}}</p>
            </div>
            <div class="card-body d-flex justify-content-between">
                <p>Ho HP : {{$karyawan->phone_number}}</p>
                <p>email: {{ $karyawan->email}}</p>
            </div>

            <div class="card-body d-flex justify-content-between">
                <h6>Gaji Per Hari : <span class="text-info">{{ 'Rp. ' . number_format($karyawan->salary, 2, ',', '.') }}</span></h6>
            </div>

            <div class="card-footer">
                <a href="{{ route('karyawans.index') }}" class="btn btn-primary">Kembali</a>
            </div>
        </div>
    </section>
    </div>
</div>

@endsection



@extends('layouts.dashboard')

@section('content')
<header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
        <i class="bi bi-justify fs-3"></i>
    </a>
</header>
            
<div class="page-heading">
    <h3>Halaman Gaji Karyawan</h3>
</div> 

<div id="main w-full">   
    <div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-6 col-lg-6 col-md-6">
                        <div class="card"> 
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                        <div class="stats-icon blue mb-2">
                                            <i class="iconly-boldProfile"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Total Gaji Karyawan</h6>
                                        <h6 class="font-extrabold mb-0">Rp.{{ number_format($gaji_karyawans->sum('total_gaji'), 2, ',', '.') }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tabel Gaji Karyawan</h3>
                <p class="text-subtitle text-muted">Mengelola Gaji Karyawan</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="">Gaji Karyawan</a></li>
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
                    Data Gaji Karyawan
                </h5>
            </div>
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            <div class="card-body">
                <div class="d-flex">
                    <a href="{{ route('gaji_karyawans.create') }}" class="btn btn-primary mb-3 ms-auto"> Tambah Gaji Karyawan</a>
                    <a href="{{ route('gaji_karyawans.jurnal_create') }}" class="btn btn-success mb-3 ms-2"> Pembayaran Gaji Karyawan</a>
                </div>
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>Nama Karyawan</th>
                            <th>Kehadiran</th>
                            <th>Gaji Harian</th>
                            <th>Total Gaji</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($gaji_karyawans as $gaji_karyawan )
                            <tr>
                            <td >{{ $gaji_karyawan->karyawan->fullname }}</td>
                            <td >{{ $gaji_karyawan->kehadiran }}</td>
                            <td >Rp.{{ number_format($gaji_karyawan->karyawan->salary, 2, ',', '.') }}</td>
                            <td >Rp.{{ number_format($gaji_karyawan->total_gaji, 2, ',', '.') }}</td>
                            <td class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('gaji_karyawans.show', $gaji_karyawan->id) }}" class="btn btn-info btn-sm">Detail</a>
                                <a href="{{ route('gaji_karyawans.edit', $gaji_karyawan->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('gaji_karyawans.destroy', $gaji_karyawan->id) }}" method="post" class="d-flex">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Yakin untuk menghapus data Gaji Karyawan ini?')" class="btn btn-danger btn-sm">Hapus</button>
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



       



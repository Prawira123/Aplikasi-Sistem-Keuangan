@extends('layouts.dashboard')

@section('content')
<header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
        <i class="bi bi-justify fs-3"></i>
    </a>
</header>
            
<div class="page-heading">
    <h3>Halaman Akun</h3>
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
                                        <h6 class="text-muted font-semibold">Banyak Akun</h6>
                                        <h6 class="font-extrabold mb-0">{{ $akuns->count() }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-6 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                        <div class="stats-icon green mb-2">
                                            <i class="iconly-boldAdd-akun"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Total Saldo Awal</h6>
                                        <h6 class="font-extrabold mb-0">Rp.{{ number_format($akuns->sum('saldo_awal'), 2, ',', '.') }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tabel Akun</h3>
                <p class="text-subtitle text-muted">Mengelola Akun</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="">Akun</a></li>
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
                    Data Akun
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
                    <a href="{{ route('akuns.create') }}" class="btn btn-primary mb-3 ms-auto"> Tambah Akun</a>
                </div>
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>Kode Akun</th>
                            <th>Nama Akun</th>
                            <th>Saldo Awal</th>
                            <th>Normal Post</th>
                            <th>Kategori Akun</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($akuns as $akun )
                            <tr>
                            <td >{{ $akun->kode }}</td>
                            <td >{{ $akun->nama }}</td>
                            <td >{{ number_format($akun->saldo_awal, 2, ',', '.') }}</td>
                            <td >{{ $akun->normal_post }}</td>
                            <td >{{ $akun->kategori_akun->nama }}</td>
                            <td class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('akuns.show', $akun->id) }}" class="btn btn-info btn-sm">Detail</a>
                                <a href="{{ route('akuns.edit', $akun->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('akuns.destroy', $akun->id) }}" method="post" class="d-flex">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Yakin untuk menghapus data Akun ini?')" class="btn btn-danger btn-sm">Hapus</button>
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



       



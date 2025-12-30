@extends('layouts.dashboard')

@section('content')
<header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
        <i class="bi bi-justify fs-3"></i>
    </a>
</header>
            
<div class="page-heading">
    <h3>Halaman Paket</h3>
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
                                        <h6 class="text-muted font-semibold">Banyak Paket</h6>
                                        <h6 class="font-extrabold mb-0">{{ $pakets->count() }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tabel Paket</h3>
                <p class="text-subtitle text-muted">Mengelola Paket</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="">Paket</a></li>
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
                    Data Paket
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
                    <a href="{{ route('pakets.create') }}" class="btn btn-primary mb-3 ms-auto"> Tambah paket</a>
                </div>
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Product</th>
                            <th>Jasa</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pakets as $paket )
                            <tr>
                            <td >{{ $paket->nama }}</td>
                            <td >{{ $paket->product->nama }}</td>
                            <td >{{ $paket->jasa->nama }}</td>
                            <td >Rp.{{ number_format($paket->harga, 2, ',', '.') }}</td>
                            <td class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('pakets.show', $paket->id) }}" class="btn btn-info btn-sm">Detail</a>
                                <a href="{{ route('pakets.edit', $paket->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('pakets.destroy', $paket->id) }}" method="post" class="d-flex">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Yakin untuk menghapus data Paket ini?')" class="btn btn-danger btn-sm">Hapus</button>
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



       



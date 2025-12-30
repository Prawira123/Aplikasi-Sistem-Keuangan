@extends('layouts.dashboard')

@section('content')
<header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
        <i class="bi bi-justify fs-3"></i>
    </a>
</header>
            
<div class="page-heading">
    <h3>Halaman Jurnal Entry</h3>
</div> 

<div id="main w-full">  
    <div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tabel Jurnal Entry</h3>
                <p class="text-subtitle text-muted">Mengelola Jurnal Entry</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="">Jurnal Entry</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Index</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-10">
                    Data Jurnal Entry
                </h5>
                <form action="{{ route('jurnal_entries.index') }}" method="GET" class="mb-4">
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
                            <button class="btn btn-primary w-100">Filter</button>
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <a href="{{ route('jurnal_entries.index') }}" class="btn btn-secondary w-100">
                                Lihat Semua
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
                <div class="d-flex">
                    <a href="{{ route('jurnal_entries.create') }}" class="btn btn-primary mb-3 ms-auto">Tambah Transaksi Jurnal Entry</a>
                </div>
                @php
                    $latestHeader = null;
                    $number = 1;
                @endphp
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Akun</th>
                            <th>Debit</th>
                            <th>Kredit</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jurnal_datas as $jurnal_data )
                            <tr class="align-middle">
                            @if($latestHeader !== $jurnal_data->jurnal_header_id)
                            <td >{{ $number++ }}</td>
                            @else
                            <td ></td>
                            @endif
                            @if ($latestHeader !== $jurnal_data->jurnal_header_id)   
                            <td >{{ \Carbon\Carbon::parse($jurnal_data->jurnal_header->tanggal)->format('d F Y') }}</td>
                            @else
                            <td ></td>
                            @endif
                            <td >{{ $jurnal_data->akun->nama }}</td>
                            <td >Rp.{{ number_format($jurnal_data->nominal_debit, 2, ',','.')  }}</td>
                            <td >Rp.{{ number_format($jurnal_data->nominal_kredit, 2, ',','.')  }}</td>
                            @if($latestHeader !== $jurnal_data->jurnal_header_id)
                            <td class="d-flex gap-2 h-100 d-flex-wrap">
                                <a href="{{ route('jurnal_entries.show', $jurnal_data->jurnal_header_id) }}" class="btn btn-info btn-sm">Detail</a>
                                <a href="{{ route('jurnal_entries.edit', $jurnal_data->jurnal_header_id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('jurnal_entries.destroy', $jurnal_data->jurnal_header_id) }}" method="post" class="d-flex">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Yakin untuk menghapus data Jurnal Entry ini?')" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                            @else
                            <td ></td>
                            @endif
                        </tr>
                        @php
                            $latestHeader = $jurnal_data->jurnal_header_id;
                        @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    </div>
</div>

@endsection


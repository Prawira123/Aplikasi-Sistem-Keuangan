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

            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tabel Gaji Karyawan</h3>
                <p class="text-subtitle text-muted">Mengelola Gaji Karyawan</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="">Gaji Karyawan</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Pembayaran Gaji Karyawan</li>
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
            <div class="card-body">
                 <form action="{{ route('gaji_karyawans.jurnal_store') }}" method="post">
                    @csrf
                    <div class="card">
                        <div class="d-flex">
                            <a href="{{ route('gaji_karyawans.index') }}" class="btn btn-primary mb-3 ms-auto">Kembali</a>
                        </div>
                        <div class="card-header">
                            <h4 class="card-title">Pembayaran Gaji Karyawan</h4>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="form-group">
                                    <label for="helperText"><h6>Tanggal Pembayaran</h6></label>
                                    <input type="date" id="helperText" class="form-control date" placeholder="Due date" name="tanggal">
                                    @error('tanggal')
                                    <div class="text-danger mt-2">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="">
                                    <h6>Akun Debit</h6>
                                    <fieldset class="form-group">
                                        <select class="form-select" id="akun_kredit" name="akun_debit">
                                            <option value="" selected disabled>Pilih Akun Debit</option>
                                            @foreach ($akuns as $akun)
                                                <option value="{{ $akun->id }}">{{ $akun->nama }}</option>                                             
                                            @endforeach
                                        </select>
                                    </fieldset>
                                    @error('akun_debit')
                                    <div class="text-danger mt-2">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="">
                                    <h6>Akun Kredit</h6>
                                    <fieldset class="form-group">
                                        <select class="form-select" id="akun_kredit" name="akun_kredit">
                                            <option value="" selected disabled>Pilih Akun Kredit</option>
                                            @foreach ($akuns as $akun)
                                                <option value="{{ $akun->id }}">{{ $akun->nama }}</option>                                             
                                            @endforeach
                                        </select>
                                    </fieldset>
                                    @error('akun_kredit')
                                    <div class="text-danger mt-2">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-md-12">                                                                         
                                    <div class="form-group">
                                        <label for="helperText"><h6>Total Gaji</h6></label>
                                        <input type="text" id="total_gaji" class="form-control" name="total_gaji" value="{{$total_gaji}}" readonly>
                                    </div>   
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success btn-md w-[200px]">Kirim</button>
                    </div>
                </form>
                
            </div>
        </div>
    </section>
</div>
</div>

@endsection

               
               
               
               

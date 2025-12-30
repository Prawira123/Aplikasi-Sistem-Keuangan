@extends('layouts.dashboard')

@section('content')
<header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
        <i class="bi bi-justify fs-3"></i>
    </a>
</header>
            
<div class="page-heading">
    <h3>Halaman Karyawans</h3>
</div> 

<div id="main w-full">  
    <div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tabel Karyawan</h3>
                <p class="text-subtitle text-muted">Mengelola Karyawans</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="">Karyawan</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tambah Karyawan</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Data Karyawan
                </h5>
            </div>
            <div class="card-body">

                <form action="{{ route('karyawans.store') }}" method="post">
                    @csrf
                    <div class="card">
                        <div class="d-flex">
                                <a href="{{ route('karyawans.index') }}" class="btn btn-primary mb-3 ms-auto">Kirim</a>
                            </div>
                        <div class="card-header">
                            <h4 class="card-title">Tambah Karyawan</h4>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="helperText"><h6>Nama Karyawan</h6></label>
                                        <input type="text" id="helperText" class="form-control" name="fullname" value="{{ old('fullname') }}">
                                        @error('fullname')
                                        <div class="text-danger mt-2">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="helperText"><h6>Email</h6></label>
                                        <input type="email" id="helperText" class="form-control" name="email" value="{{ old('email') }}">
                                        @error('email')
                                        <div class="text-danger mt-2">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="helperText"><h6>No HP</h6></label>
                                        <span class="input-group-text">+62</span>
                                        <input type="text" id="helperText" class="form-control" name="phone_number" value="{{ old('phone_number') }}">
                                        @error('phone_number')
                                        <div class="text-danger mt-2">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="helperText"><h6>Tanggal Lahir</h6></label>
                                        <input type="date" id="helperText" class="form-control" placeholder="Due date" name="birth_date">
                                        @error('birth_date')
                                        <div class="text-danger mt-2">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="helperText"><h6>Tanggal Diterima</h6></label>
                                        <input type="date" id="helperText" class="form-control" placeholder="Due date" name="hire_date">
                                        @error('hire_date')
                                        <div class="text-danger mt-2">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="helperText"><h6>Gaji Per Hari</h6></label>
                                        <input type="number" id="helperText" class="form-control" name="salary" value="{{ old('salary') }}">
                                        @error('salary')
                                        <div class="text-danger mt-2">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>  
                                    <h6>
                                        Alamat
                                    </h6>
                                    <div class="form-group with-title">
                                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="address">{{ old('address') }}</textarea>
                                        <label>Penjelasan Alamat</label>
                                        @error('address')
                                        <div class="text-danger mt-2">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>                       
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2 card">
                            <button type="submit" name="action" class="btn btn-success btn-md w-[200px]">Kirim</button>
                            <button type="submit" name="action" value="save_next" class="btn btn-secondary">
                                    Kirim & Tambah Karyawan Lagi
                            </button>
                        </div>                    
                    </div>
                </form>
                
            </div>
        </div>
    </section>
    </div>
</div>

@endsection


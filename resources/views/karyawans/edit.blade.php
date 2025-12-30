@extends('layouts.dashboard')

@section('content')
<header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
        <i class="bi bi-justify fs-3"></i>
    </a>
</header>
            
<div class="page-heading">
    <h3>Halaman Karyawan</h3>
</div> 

<div id="main w-full"> 
    <div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3> Tabel Karyawan</h3>
                <p class="text-subtitle text-muted">Mengelola Karyawan</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="">Karyawan</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Karyawan</li>
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

                <form action="{{ route('karyawans.update', $karyawan->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="card">
                        <div class="d-flex">
                                <a href="{{ route('karyawans.index') }}" class="btn btn-primary mb-3 ms-auto">Kembali</a>
                            </div>
                        <div class="card-header">
                            <h4 class="card-title">Perbarui Karyawan</h4>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="helperText"><h6>Nama Karyawan</h6></label>
                                        <input type="text" id="helperText" class="form-control" name="fullname" value="{{ old('fullname', $karyawan->fullname) }}">
                                        @error('fullname')
                                        <div class="text-danger mt-2">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="helperText"><h6>Email</h6></label>
                                        <input type="text" id="helperText" class="form-control" name="email" value="{{ old('email', $karyawan->email) }}">
                                        @error('email')
                                        <div class="text-danger mt-2">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="helperText"><h6>No HP</h6></label>
                                        <span class="input-group-text">+62</span>
                                        <input type="text" id="helperText" class="form-control" name="phone_number" value="{{ old('phone_number', $karyawan->phone_number) }}">
                                        @error('phone_number')
                                        <div class="text-danger mt-2">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="helperText"><h6>Tanggal Lahir</h6></label>
                                        <input type="datetime-local" id="helperText" class="form-control" placeholder="Due date" name="birth_date" value="{{ old('birth_date', \Carbon\Carbon::parse($karyawan->birth_date)->format('Y-m-d\TH:i')) }}">
                                        @error('birth_date')
                                        <div class="text-danger mt-2">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="helperText"><h6>Tanggal Diterima</h6></label>
                                        <input type="datetime-local" id="helperText" class="form-control" placeholder="Due date" name="hire_date"value="{{ old('birth_date', \Carbon\Carbon::parse($karyawan->hire_date)->format('Y-m-d\TH:i')) }}">
                                        @error('hire_date')
                                        <div class="text-danger mt-2">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="helperText"><h6>Gaji Per Hari</h6></label>
                                        <input type="number" id="helperText" class="form-control" name="salary" value="{{ old('salary', $karyawan->salary) }}">
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
                                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="address">{{ old('address', $karyawan->address) }}</textarea>
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
                        <button type="submit" class="btn btn-success btn-md w-[200px]">Kirim</button>
                    </div>
                </form>
                
            </div>
        </div>
    </section>
    </div>
</div>

@endsection


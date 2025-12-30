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
                        <li class="breadcrumb-item active" aria-current="page">Tambah</li>
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
                 <form action="{{ route('gaji_karyawans.store') }}" method="post">
                    @csrf
                    <div class="card">
                        <div class="d-flex">
                            <a href="{{ route('gaji_karyawans.index') }}" class="btn btn-primary mb-3 ms-auto">Kembali</a>
                        </div>
                        <div class="card-header">
                            <h4 class="card-title">Gaji Karyawan Input</h4>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col_barang form_barang">
                                    <h6>Nama Karyawan</h6>
                                    <fieldset class="form-group">
                                        <select class="form-select" id="karyawan" name="karyawan_id">
                                            <option value="" selected disabled>Pilih Karyawan</option>
                                            @foreach ($karyawans as $karyawan)
                                                <option value="{{ $karyawan->id }}" data-gaji="{{ $karyawan->salary }}">{{ $karyawan->fullname }}</option>                                             
                                            @endforeach
                                        </select>
                                    </fieldset>
                                    @error('karyawan_id')
                                    <div class="text-danger mt-2">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-md-12">                                                                         
                                    <div class="form-group">
                                        <label for="helperText"><h6>Kehadiran</h6></label>
                                        <input type="number" id="kehadiran" class="form-control" placeholder="" name="kehadiran" value="{{ old('kehadiran') }}">
                                        @error('harga')
                                        <div class="text-danger mt-2">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>   
                                </div>
                                <div class="col-md-12">                                                                         
                                    <div class="form-group">
                                        <label for="helperText"><h6>Gaji Per hari</h6></label>
                                        <input type="number" id="salary" class="form-control" placeholder="" name="harga" value="">
                                        @error('harga')
                                        <div class="text-danger mt-2">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>   
                                </div>
                                <div class="col-md-12">                                                                         
                                    <div class="form-group">
                                        <label for="helperText"><h6>Total Gaji</h6></label>
                                        <input type="number" id="total_gaji" class="form-control" placeholder=""  value="{{ old('total_gaji')}}">
                                        @error('harga')
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
                                    Kirim & Tambah Gaji Karyawan Lagi
                            </button>
                        </div>                    
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
</div>

<script>
    const karyawan = document.querySelector('#karyawan');
    const kehadiran = document.querySelector('#kehadiran');
    const salary = document.querySelector('#salary');
    const total_gaji = document.querySelector('#total_gaji');

    karyawan.addEventListener('change', function () {
        const gaji = karyawan.options[karyawan.selectedIndex].getAttribute('data-gaji');
        salary.value = gaji;
        total_gaji.value = gaji * kehadiran.value || 0;
    });

    kehadiran.addEventListener('input', function () {
        total_gaji.value = salary.value * kehadiran.value || 0;
    });


</script>
@endsection

               
               
               
               

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
                        <li class="breadcrumb-item active" aria-current="page">Tambah Jurnal Entry</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Data Jurnal Entry
                </h5>
            </div>
            <div class="card-body">

                <form action="{{ route('jurnal_entries.store') }}" method="post">
                    @csrf
                    <div class="card">
                        <div class="d-flex">
                                <a href="{{ route('jurnal_entries.index') }}" class="btn btn-primary mb-3 ms-auto">Kembali</a>
                            </div>
                        <div class="card-header">
                            <h4 class="card-title">Tambah Jurnal Entry</h4>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                     <div class="form-group">
                                        <label for="helperText"><h6>Tanggal Transaksi</h6></label>
                                        <input type="datetime-local" id="helperText" class="form-control datetime" placeholder="Due date" name="tanggal">
                                        @error('tanggal')
                                        <div class="text-danger mt-2">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-12">
                                        <h6>Akun Debit (Opsional)</h6>
                                        <fieldset class="form-group">
                                            <select class="form-select" id="akun" name="akun_debit_id">
                                                <option value="" selected disabled>Pilih Akun</option>
                                                @foreach ($akuns as $akun)
                                                    <option value="{{ $akun->id }}">{{ $akun->nama }}</option>                                                    
                                                @endforeach
                                            </select>
                                        </fieldset>
                                        @error('akun_debit_id')
                                        <div class="text-danger mt-2">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-12">
                                        <h6>Akun Kredit (Opsional)</h6>
                                        <fieldset class="form-group">
                                            <select class="form-select" id="akun" name="akun_kredit_id">
                                                <option value="" selected disabled>Pilih Akun</option>
                                                @foreach ($akuns as $akun)
                                                    <option value="{{ $akun->id }}">{{ $akun->nama }}</option>                                                    
                                                @endforeach
                                            </select>
                                        </fieldset>
                                        @error('akun_kredit_id')
                                        <div class="text-danger mt-2">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                    <label for="helperText"><h6>Nominal</h6></label>
                                        <input type="number" id="harga_total" class="form-control" name="nominal" value="{{ old('nominal') }}">
                                        @error('nominal')
                                        <div class="text-danger mt-2">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div> 
                                    <div class="col-md-12">
                                        <h6>
                                            Keterangan
                                        </h6>
                                        <div class="form-group with-title">
                                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="keterangan">{{ old('keterangan') }}</textarea>
                                            <label>Penjelasan keterangan</label>
                                            @error('keterangan')
                                            <div class="text-danger mt-2">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>  
                                    </div>                    
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2 card">
                            <button type="submit" name="action" class="btn btn-success btn-md w-[200px]">Kirim</button>
                            <button type="submit" name="action" value="save_next" class="btn btn-secondary">
                                    Kirim & Tambah Jurnal Entry Lagi
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


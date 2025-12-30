@extends('layouts.dashboard')

@section('content')
<header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
        <i class="bi bi-justify fs-3"></i>
    </a>
</header>
            
<div class="page-heading">
    <h3>Halaman Kategori</h3>
</div> 

<div id="main w-full">   
    <div class="page-heading">
    <div class="page-title">
        <div class="row">

            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tabel Kategori</h3>
                <p class="text-subtitle text-muted">Mengelola Kategori</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="">Kategori</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Data Kategori
                </h5>
            </div>
            <div class="card-body">
                 <form action="{{ route('kategories.update', $kategori->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="card">
                        <div class="d-flex">
                            <a href="{{ route('kategories.index') }}" class="btn btn-primary mb-3 ms-auto">Back to list</a>
                        </div>
                        <div class="card-header">
                            <h4 class="card-title">Perbarui Kategori</h4>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">                                                                         
                                    <div class="form-group">
                                        <label for="helperText"><h6>Nama Kategori Akun</h6></label>
                                        <input type="text" id="nama" class="form-control" placeholder="" name="nama" value="{{ old('nama', $kategori->nama) }}">
                                        @error('nama')
                                        <div class="text-danger mt-2">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>  
                                </div> 
                                <div class="col-md-12">
                                    <h6>Kelompok</h6>
                                    <fieldset class="form-group">
                                        <select class="form-select" id="basicSelect" name="kelompok_id">
                                            @foreach ($kelompoks as $kelompok )
                                                <option value="{{ $kelompok->id }}" @if(old('kelompok_id',$kategori->kelompok->id) == $kelompok->id) selected @endif>{{ $kelompok->name }}</option>                                               
                                            @endforeach
                                        </select>
                                    </fieldset>
                                    @error('kelompok_id')
                                    <div class="text-danger mt-2">
                                        {{ $message }}
                                    </div>
                                    @enderror
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

               
               
               
               

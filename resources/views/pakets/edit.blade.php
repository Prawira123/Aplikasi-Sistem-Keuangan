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

            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tabel Paket</h3>
                <p class="text-subtitle text-muted">Mengelola Paket</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="">Paket</a></li>
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
                    Data Paket
                </h5>
            </div>
            <div class="card-body">
                 <form action="{{ route('pakets.update', $paket->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="card">
                        <div class="d-flex">
                            <a href="{{ route('pakets.index') }}" class="btn btn-primary mb-3 ms-auto">Kembali</a>
                        </div>
                        <div class="card-header">
                            <h4 class="card-title">Perbarui Paket</h4>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">                                                                         
                                    <div class="form-group">
                                        <label for="helperText"><h6>Nama Paket</h6></label>
                                        <input type="text" id="nama" class="form-control" placeholder="" name="nama" value="{{ old('nama', $paket->nama) }}">
                                        @error('nama')
                                        <div class="text-danger mt-2">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>  
                                </div> 
                               <div class="col_barang form_barang">
                                    <h6>Barang</h6>
                                    <fieldset class="form-group">
                                        <select class="form-select" id="barang" name="product_id">
                                            <option value="" selected disabled>Pilih barang</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}" @if(old('product_id', $paket->product_id) == $product->id) selected @endif>{{ $product->nama }}</option>                                             
                                            @endforeach
                                        </select>
                                    </fieldset>
                                    @error('product_id')
                                    <div class="text-danger mt-2">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col_barang form_barang">
                                    <h6>Jasa</h6>
                                    <fieldset class="form-group">
                                        <select class="form-select" id="barang" name="jasa_id">
                                            <option value="" selected disabled>Pilih jasa</option>
                                            @foreach ($jasas as $jasa)
                                                <option value="{{ $jasa->id }}" @if(old('product_id', $paket->jasa_id) == $jasa->id) selected @endif>{{ $jasa->nama }}</option>                                             
                                            @endforeach
                                        </select>
                                    </fieldset>
                                    @error('jasa_id')
                                    <div class="text-danger mt-2">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-md-12">                                                                         
                                    <div class="form-group">
                                        <label for="helperText"><h6>Harga Barang</h6></label>
                                        <input type="number" id="harga" class="form-control" placeholder="" name="harga" value="{{ old('harga', $paket->harga) }}">
                                        @error('harga')
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

               
               
               
               

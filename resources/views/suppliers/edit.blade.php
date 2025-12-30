@extends('layouts.dashboard')

@section('content')
<header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
        <i class="bi bi-justify fs-3"></i>
    </a>
</header>
            
<div class="page-heading">
    <h3>Halaman Supplier</h3>
</div> 

<div id="main w-full">  
    <div class="page-heading">
    <div class="page-title">
        <div class="row">

            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tabel Supplier</h3>
                <p class="text-subtitle text-muted">Mengelola Supplier</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="">Supplier</a></li>
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
                    Data Supplier
                </h5>
            </div>
            <div class="card-body">
                 <form action="{{ route('suppliers.update', $supplier->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="card">
                        <div class="d-flex">
                            <a href="{{ route('suppliers.index') }}" class="btn btn-primary mb-3 ms-auto">Back to list</a>
                        </div>
                        <div class="card-header">
                            <h4 class="card-title">Perbarui Supplier</h4>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">                                                                         
                                    <div class="form-group">
                                        <label for="helperText"><h6>Nama</h6></label>
                                        <input type="text" id="nama" class="form-control" placeholder="" name="nama" value="{{ old('nama', $supplier->nama) }}">
                                        @error('nama')
                                        <div class="text-danger mt-2">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>   
                                <div class="col-md-12">                                                                         
                                    <div class="form-group">
                                        <label for="helperText"><h6>No Telp</h6></label>
                                        <input type="number" id="no_tlp" class="form-control" placeholder="" name="no_tlp" value="{{ old('no_tlp', $supplier->no_tlp) }}">
                                        @error('no_tlp')
                                        <div class="text-danger mt-2">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>   
                                </div>
                                <h6>
                                    Alamat
                                </h6>
                                <div class="form-group with-title">
                                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="alamat">{{ old('alamat', $supplier->alamat) }}</textarea>
                                    <label>Alamat lengkap</label>
                                    @error('alamat')
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

               
               
               
               

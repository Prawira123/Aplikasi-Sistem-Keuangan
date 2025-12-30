@extends('layouts.dashboard')

@section('content')
<header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
        <i class="bi bi-justify fs-3"></i>
    </a>
</header>
            
<div class="page-heading">
    <h3>Halaman User</h3>
</div> 

<div id="main w-full">   
    <div class="page-heading">
    <div class="page-title">
        <div class="row">

            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tabel User</h3>
                <p class="text-subtitle text-muted">Mengelola User</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="">User</a></li>
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
                    Data User
                </h5>
            </div>
            <div class="card-body">
                 <form action="{{ route('users.update', $user->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="card">
                        <div class="d-flex">
                            <a href="{{ route('users.index') }}" class="btn btn-primary mb-3 ms-auto">Kembali</a>
                        </div>
                        <div class="card-header">
                            <h4 class="card-title">Perbarui User</h4>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">                                                                         
                                    <div class="form-group">
                                        <label for="helperText"><h6>Nama</h6></label>
                                        <input type="text" id="name" class="form-control" placeholder="" name="name" value="{{ old('name', $user->name) }}">
                                        @error('name')
                                        <div class="text-danger mt-2">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>   
                                <div class="col-md-12">                                                                         
                                    <div class="form-group">
                                        <label for="helperText"><h6>email</h6></label>
                                        <input type="email" id="email" class="form-control" placeholder="" name="email" value="{{ old('email', $user->email) }}">
                                        @error('email')
                                        <div class="text-danger mt-2">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div> 
                                </div>
                                <div class="col-md-12">
                                    <h6>Role</h6>
                                    <fieldset class="form-group">
                                        <select class="form-select" id="role" name="role">
                                            <option value="" selected disabled>Pilih Role</option>
                                            <option value="admin" @if(old('role', $user->role) == 'admin') selected @endif>Admin</option>
                                            <option value="owner" @if(old('role', $user->role) == 'owner') selected @endif>Owner</option>
                                        </select>
                                    </fieldset>
                                    @error('role')
                                    <div class="text-danger mt-2">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>  
                                <div class="col-md-12">                                                                         
                                    <div class="form-group">
                                        <label for="helperText"><h6>Password</h6></label>
                                        <input type="password" id="password" class="form-control" placeholder="" name="password" value="{{ old('password', $user->password) }}" readonly>
                                        @error('password')
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

               
               
               
               

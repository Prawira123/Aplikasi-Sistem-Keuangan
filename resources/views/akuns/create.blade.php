@extends('layouts.dashboard')

@section('content')
<header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
        <i class="bi bi-justify fs-3"></i>
    </a>
</header>
            
<div class="page-heading">
    <h3>Halaman Akun</h3>
</div> 

<div id="main w-full">   
    <div class="page-heading">
    <div class="page-title">
        <div class="row">

            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tabel Akun</h3>
                <p class="text-subtitle text-muted">Mengelola Akun</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="">Akun</a></li>
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
                    Data Akun
                </h5>
            </div>
            <div class="card-body">
                 <form action="{{ route('akuns.store') }}" method="post">
                    @csrf
                    <div class="card">
                        <div class="d-flex">
                            <a href="{{ route('akuns.index') }}" class="btn btn-primary mb-3 ms-auto">Kembali</a>
                        </div>
                        <div class="card-header">
                            <h4 class="card-title">Tambah Akun</h4>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">                                                                         
                                    <div class="form-group">
                                        <label for="helperText"><h6>Nama Akun</h6></label>
                                        <input type="text" id="nama" class="form-control" placeholder="" name="nama" value="{{ old('nama') }}">
                                        @error('nama')
                                        <div class="text-danger mt-2">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>  
                                </div> 
                                <div class="col-md-12">
                                    <h6>Normal Post</h6>
                                    <fieldset class="form-group">
                                        <select class="form-select" id="normal_post" name="normal_post">
                                            <option value="" selected disabled>Pilih Normal Post</option>                                               
                                            <option value="Debit">Debit</option>                                               
                                            <option value="Kredit">Kredit</option>                                               
                                        </select>
                                    </fieldset>
                                    @error('normal_post')
                                    <div class="text-danger mt-2">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-md-12 d-none" id="lawan_post">
                                    <h6 id="lawan_post_label"></h6>
                                    <fieldset class="form-group">
                                        <select class="form-select" id="lawan_post_select" name="lawan_post">
                                            <option value="" seelcted disabled>Pilih Akun</option>
                                            <option value="">Tidak ada</option>
                                            @foreach ($lawan_posts as $lawan_post)  
                                                <option value="{{ $lawan_post->id }}">{{ $lawan_post->nama }}</option>                                               
                                            @endforeach
                                        </select>
                                    </fieldset>
                                    @error('lawan_post')
                                    <div class="text-danger mt-2">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="row">
                                <div class="col-md-12">                                                                         
                                    <div class="form-group">
                                        <label for="helperText"><h6>Saldo Awal</h6></label>
                                        <input type="number" id="nama" class="form-control" placeholder="" name="saldo_awal" value="{{ old('saldo_awal') }}">
                                        @error('saldo_awal')
                                        <div class="text-danger mt-2">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>  
                                </div>
                                <div class="col-md-12">
                                    <h6>Kategori akun</h6>
                                    <fieldset class="form-group">
                                        <select class="form-select" id="basicSelect" name="kategori_akun_id">
                                            @foreach ($kategories as $kategori )
                                                <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>                                               
                                            @endforeach
                                        </select>
                                    </fieldset>
                                    @error('kategori_akun_id')
                                    <div class="text-danger mt-2">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <h6>Aktivitas Kas</h6>
                                    <fieldset class="form-group">
                                        <select class="form-select" id="aktivitas_kas" name="aktivitas_kas">
                                            <option value="" selected disabled>Pilih Aktivitas Kas</option>
                                            <option value="">Tidak ada</option>                                               
                                            <option value="Operasional">Operasional</option>                                               
                                            <option value="Investasi">Investasi</option>                                               
                                            <option value="Pendanaan">Pendanaan</option>                                               
                                        </select>
                                    </fieldset>
                                    @error('aktivitas_kas')
                                    <div class="text-danger mt-2">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2 card">
                            <button type="submit" name="action" class="btn btn-success btn-md w-[200px]">Kirim</button>
                            <button type="submit" name="action" value="save_next" class="btn btn-secondary">
                                    Kirim & Tambah Akun Lagi
                            </button>
                        </div>                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
</div>

<script>
    const normalPost = document.getElementById('normal_post');
    const lawanPostWrapper = document.getElementById('lawan_post');
    const lawanPostLabel = document.getElementById('lawan_post_label');

    normalPost.addEventListener('change', function (e) {
        const value = e.target.value;

        if (value === 'Debit') {
            lawanPostLabel.textContent = 'Akun Kredit';
            lawanPostWrapper.classList.remove('d-none');
        } 
        else if (value === 'Kredit') {
            lawanPostLabel.textContent = 'Akun Debit';
            lawanPostWrapper.classList.remove('d-none');
        }
    });
</script>

</script>

@endsection

               
               
               
               

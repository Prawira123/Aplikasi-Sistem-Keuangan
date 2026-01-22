@extends('layouts.dashboard')

@section('content')
<header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
        <i class="bi bi-justify fs-3"></i>
    </a>
</header>
            
<div class="page-heading">
    <h3>Halaman Pembelian</h3>
</div> 

<div id="main w-full"> 
    <div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tabel Pembelian</h3>
                <p class="text-subtitle text-muted">Mengelola Pembelian</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="">Pembelian</a></li>
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
                    Data Pembelian
                </h5>
            </div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                <form action="{{ route('transaksi_keluars.store') }}" method="post">
                    @csrf
                    <div class="card">
                        <div class="d-flex">
                                <a href="{{ route('transaksi_keluars.index') }}" class="btn btn-primary mb-3 ms-auto">Kembali</a>
                            </div>
                        <div class="card-header">
                            <h4 class="card-title">Tambah Pembelian</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-12">
                                        <h6>Supplier</h6>
                                        <fieldset class="form-group">
                                            <select class="form-select" id="basicSelect" name="supplier_id">
                                                <option value="" selected disabled>Pilih Supplier</option>
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}">{{ $supplier->nama }}</option>                                             
                                                @endforeach
                                            </select>
                                        </fieldset>
                                        @error('supplier_id')
                                        <div class="text-danger mt-2">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                     <div class="form-group">
                                        <label for="helperText"><h6>Tanggal Transaksi</h6></label>
                                        <input type="datetime-local" id="helperText" class="form-control datetime" placeholder="Due date" name="tanggal">
                                        @error('tanggal')
                                        <div class="text-danger mt-2">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col_barang form_barang">
                                        <h6>Barang</h6>
                                        <fieldset class="form-group">
                                            <select class="form-select" id="barang" name="product_id">
                                                <option value="" selected disabled>Pilih barang</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}" data-harga="{{ $product->harga }}">{{ $product->nama }}</option>                                             
                                                @endforeach
                                            </select>
                                        </fieldset>
                                        @error('product_id')
                                        <div class="text-danger mt-2">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form_barang">
                                        <label for="helperText"><h6>Quantity</h6></label>
                                        <input type="number" id="qty" class="form-control" name="qty" value="{{ old('qty') }}">
                                        @error('qty')
                                        <div class="text-danger mt-2">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form_barang" >
                                        <label for="helperText"><h6>Harga Satuan</h6></label>
                                        <input type="number" class="form-control" id="harga_product" name="harga_satuan" value="{{ old('harga_satuan') }}">
                                        @error('harga_satuan')
                                        <div class="text-danger mt-2">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="helperText"><h6>Harga Total</h6></label>
                                        <input type="number" id="harga_total" class="form-control" name="harga_total" value="" readonly>
                                        @error('harga_total')
                                        <div class="text-danger mt-2">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div> 
                                    <div class="col-md-12">
                                        <h6>Akun Debit</h6>
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
                                        <h6>Akun Kredit</h6>
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
                                    Kirim & Tambah Transaksi Lagi
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

    const barang = document.querySelector('#barang');
    const hargaInput = document.querySelector('#harga_product');
    const qtyInput = document.querySelector('#qty');
    const hargaTotal = document.querySelector('#harga_total');

    barang.addEventListener('change', function () {
        let harga = this.options[this.selectedIndex].getAttribute('data-harga');
        hargaInput.value = harga;
        hitungTotal(); // supaya total langsung update kalau qty sudah terisi
    });

    qtyInput.addEventListener('input', function () {
        hitungTotal();
    });

    function hitungTotal() {
        let harga = parseInt(hargaInput.value) || 0;
        let qty = parseInt(qtyInput.value) || 0;
        hargaTotal.value = harga * qty;
    }

</script>

@endsection


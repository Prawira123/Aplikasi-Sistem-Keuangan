@extends('layouts.dashboard')

@section('content')
<header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
        <i class="bi bi-justify fs-3"></i>
    </a>
</header>
            
<div class="page-heading">
    <h3>Halaman Penjualan</h3>
</div> 

<div id="main w-full">   
    <div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Penjualan Table</h3>
                <p class="text-subtitle text-muted">Mengelola Penjualan</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="">Penjualan</a></li>
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
                    Data Penjualan
                </h5>
            </div>
            <div class="card-body">

                <form action="{{ route('transaksi_masuks.store') }}" method="post">
                    @csrf
                    <div class="card">
                        <div class="d-flex">
                                <a href="{{ route('transaksi_masuks.index') }}" class="btn btn-primary mb-3 ms-auto">Kembali</a>
                            </div>
                        <div class="card-header">
                            <h4 class="card-title">Tambah Penjualan</h4>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-12">
                                        <h6>Karyawan</h6>
                                        <fieldset class="form-group">
                                            <select class="form-select" id="basicSelect" name="karyawan_id">
                                                @foreach ($karyawans as $karyawan)
                                                    <option value="{{ $karyawan->id }}">{{ $karyawan->fullname }}</option>                                             
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
                                        <h6>Pelanggan</h6>
                                        <fieldset class="form-group">
                                            <select class="form-select" id="pelanggan" name="pelanggan_id">
                                                <option value="" selected disabled>Pilih Pelanggan</option>
                                                @foreach ($pelanggans as $pelanggan)
                                                    <option value="{{ $pelanggan->id }}">{{ $pelanggan->nama }}</option>                                                    
                                                @endforeach
                                            </select>
                                        </fieldset>
                                        @error('pelanggan_id')
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
                                    <div class="col-md-12">
                                        <h6>Tipe</h6>
                                        <fieldset class="form-group">
                                            <select class="form-select" id="tipe" name="tipe">
                                                <option value="" selected disabled>Pilih Tipe</option>
                                                <option value="Barang">Barang</option>
                                                <option value="Jasa">Jasa</option>
                                                <option value="Paket">Paket</option>
                                            </select>
                                        </fieldset>
                                        @error('tipe')
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
                                    <div class="col-md-12 form_jasa">
                                        <h6>Jasa</h6>
                                        <fieldset class="form-group">
                                            <select class="form-select" id="jasa" name="jasa_id">
                                                <option value="" selected disabled>Pilih Jasa</option>
                                                @foreach ($jasas as $jasa)
                                                    <option value="{{ $jasa->id }}" data-harga="{{ $jasa->harga }}" >{{ $jasa->nama }}</option>                                             
                                                @endforeach
                                            </select>
                                        </fieldset>
                                        @error('jasa_id')
                                        <div class="text-danger mt-2">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-12 form_paket">
                                        <h6>Paket</h6>
                                        <fieldset class="form-group">
                                            <select class="form-select" id="paket" name="paket_id">
                                                <option value="" selected disabled>Pilih Paket</option>
                                                @foreach ($pakets as $paket)
                                                    <option value="{{ $paket->id }}" data-harga="{{ $paket->harga }}" >{{ $paket->nama }}</option>                                             
                                                @endforeach
                                            </select>
                                        </fieldset>
                                        @error('paket_id')
                                        <div class="text-danger mt-2">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form_barang">
                                        <label for="helperText"><h6>Quantity</h6></label>
                                        <input type="number" id="qty" class="form-control" name="qty" value="">
                                        @error('qty')
                                        <div class="text-danger mt-2">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form_barang" >
                                        <label for="helperText"><h6>Harga Satuan</h6></label>
                                        <input type="number" class="form-control" id="harga_product" name="harga_satuan" value="" readonly>
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

    const form_barang = document.querySelectorAll('.form_barang');
    const form_jasa = document.querySelector('.form_jasa');
    const form_paket = document.querySelector('.form_paket');
    const barang = document.querySelector('#barang');
    const hargaInput = document.querySelector('#harga_product');
    const qtyInput = document.querySelector('#qty');
    const hargaTotal = document.querySelector('#harga_total');
    const jasa = document.querySelector('#jasa');
    const paket = document.querySelector('#paket');

    form_barang.forEach(el => {
        el.classList.add('d-none');
    });
    form_jasa.classList.add('d-none');
    form_paket.classList.add('d-none');


    document.querySelector('#tipe').addEventListener('change', function() {
        
        let tipe = this.value;

        if (tipe == 'Barang') {
            form_barang.forEach(el => {
                el.classList.remove('d-none')
            });
            form_jasa.classList.add('d-none');
            form_paket.classList.add('d-none');
        } else if(tipe == 'Jasa') {
            form_jasa.classList.remove('d-none');
            form_barang.forEach(el => {
                el.classList.add('d-none')
            });
            form_paket.classList.add('d-none');
        } else if(tipe == 'Paket') {
            form_paket.classList.remove('d-none');
            form_barang.forEach(el => {
                el.classList.add('d-none')
            });
            form_jasa.classList.add('d-none');
        }

    });

    if (barang) {
        barang.addEventListener('change', function () {
            let harga = this.options[this.selectedIndex].getAttribute('data-harga');
            hargaInput.value = harga;
            console.log(harga)
            hitungTotal();
        });
    }
    if (jasa) {
        jasa.addEventListener('change', function () {
            let harga = this.options[this.selectedIndex].getAttribute('data-harga');
            let hargaJasa = parseInt(harga) || 0;
            hargaTotal.value = hargaJasa;
            console.log(harga)
        });
    }
    if (paket) {
        paket.addEventListener('change', function () {
            let harga = this.options[this.selectedIndex].getAttribute('data-harga');
            let hargaPaket = parseInt(harga) || 0;
            hargaTotal.value = hargaPaket;
            console.log(harga)
        });
    }

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


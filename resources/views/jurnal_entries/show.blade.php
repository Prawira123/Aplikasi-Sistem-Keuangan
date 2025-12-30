@extends('layouts.dashboard')

@section('content')
<header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
        <i class="bi bi-justify fs-3"></i>
    </a>
</header>
            
<div class="page-heading">
    <h3>Jurnal Entry {{ \Carbon\Carbon::parse($jurnal_datas->first()->jurnal_header->tanggal)->format('d F Y') }}</h3>
</div> 

<div id="main w-full">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>{{ $jurnal_datas->first()->tipe }}</h3>
                    <p class="text-subtitle text-muted">Detail Jurnal Entry</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="">Jurnal Entry</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">

                {{-- LOOP 2 BARIS (DEBIT & KREDIT) --}}
                @foreach ($jurnal_datas as $row)
                    @if($row->nominal_debit > 0)
                        <div class="card-body d-flex justify-content-between">
                            <p>Debit : {{ $row->akun->nama }}</p>
                        </div>
                        <div class="card-body d-flex justify-content-between">
                            <p>Nominal : Rp {{ number_format($row->nominal_debit, 2, ',', '.') }}</p>
                        </div>
                    @endif

                    @if($row->nominal_kredit > 0)
                        <div class="card-body d-flex justify-content-between">
                            <p>Kredit : {{ $row->akun->nama }}</p>
                        </div>
                        <div class="card-body d-flex justify-content-between">
                            <p>Nominal : Rp {{ number_format($row->nominal_kredit, 2, ',', '.') }}</p>
                        </div>
                    @endif
                @endforeach

                <div class="card-body d-flex justify-content-between">
                    <p>Keterangan : 
                        <span class="text-info">
                            {{ $jurnal_datas->first()->jurnal_header->keterangan }}
                        </span>
                    </p>
                </div>

                <div class="card-footer">
                    <a href="{{ route('jurnal_entries.index') }}" class="btn btn-primary">Kembali</a>
                </div>

            </div>
        </section>
    </div>
</div>

@endsection

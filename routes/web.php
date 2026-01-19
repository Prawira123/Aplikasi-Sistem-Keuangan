<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\JasaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\JurnalEntryController;
use App\Http\Controllers\GajiKaryawanController;
use App\Http\Controllers\KategoriAkunController;
use App\Http\Controllers\TransaksiMasukController;
use App\Http\Controllers\TransaksiKeluarController;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index')->middleware( ['role:owner,admin']);    
    Route::get('dashboard/penjualan_perbulan', [DashboardController::class, 'penjualan_perbulan'])->middleware( ['role:owner,admin']);
    Route::get('dashboard/penjualan_perbulan_detail', [DashboardController::class, 'penjualan_perbulan_detail'])->middleware( ['role:owner,admin']);

    Route::resource('users', UserController::class)->middleware( ['role:owner']);

    Route::resource('suppliers', SupplierController::class)->middleware( ['role:owner,admin']);

    Route::resource('products', ProductController::class)->middleware( ['role:owner,admin']);
    
    Route::resource('jasas', JasaController::class)->middleware( ['role:owner,admin']);

    Route::resource('akuns', AkunController::class)->middleware( ['role:owner,admin']);

    Route::resource('kategories', KategoriAkunController::class)->middleware( ['role:owner,admin']);

    Route::resource('karyawans', KaryawanController::class)->middleware( ['role:owner,admin']);
    Route::get('/karyawans/karyawanStatus/{id}', [KaryawanController::class, 'karyawanStatus'])->name('karyawans.karyawanStatus')->middleware( ['role:owner,admin']);
    
    Route::resource('transaksi_masuks', TransaksiMasukController::class)->middleware( ['role:owner,admin']);
    Route::get('transaksi_masuk/invoice/{id}', [TransaksiMasukController::class, 'exportPDFInvoice'])->name('exportPDFInvoice')->middleware( ['role:owner,admin']);

    Route::resource('transaksi_keluars', TransaksiKeluarController::class)->middleware( ['role:owner,admin']);

    Route::resource('jurnal_entries', JurnalEntryController::class)->middleware( ['role:owner,admin']);

    Route::resource('pelanggans', PelangganController::class)->middleware( ['role:owner,admin']);

    Route::resource('pakets', PaketController::class)->middleware( ['role:owner,admin']);

    Route::get('gaji_karyawans/pembayaran_gaji', [GajiKaryawanController::class, 'jurnal_create'])->name('gaji_karyawans.jurnal_create')->middleware( ['role:owner']);
    Route::post('gaji_karyawans/pembayaran_gaji', [GajiKaryawanController::class, 'jurnal_store'])->name('gaji_karyawans.jurnal_store')->middleware( ['role:owner']);
    Route::resource('gaji_karyawans', GajiKaryawanController::class)->middleware( ['role:owner']);

    Route::resource('laporans', LaporanController::class)->middleware( ['role:owner']);
    Route::get('laporans/Export/Neraca', [LaporanController::class, 'exportPDFNeraca'])->name('laporans.export.neraca')->middleware( ['role:owner']);
    Route::get('laporans/Export/LabaRugi', [LaporanController::class, 'exportPDFLabaRugi'])->name('laporans.export.laba_rugi')->middleware( ['role:owner']);
    Route::get('laporans/Export/ArusKas', [LaporanController::class, 'exportPDFArusKas'])->name('laporans.export.arus_kas')->middleware( ['role:owner']);
    Route::get('laporans/Export/BukuBesar', [LaporanController::class, 'exportPDFBukuBesar'])->name('laporans.export.buku_besar')->middleware( ['role:owner']);
    Route::get('laporans/Export/JurnalUmum', [LaporanController::class, 'exportPDFJurnalUmum'])->name('laporans.export.jurnal_umum')->middleware( ['role:owner']);
    Route::get('laporans/Export/PerubahanModal', action: [LaporanController::class, 'exportPDFPerubahanModal'])->name('laporans.export.perubahan_modal')->middleware( ['role:owner']);
    Route::get('laporans/Export/TransaksiMasuk', action: [LaporanController::class, 'exportPDFTransaksiMasuk'])->name('laporans.export.transaksi_masuk')->middleware( ['role:owner']);
    Route::get('laporans/Export/TransaksiKeluar', action: [LaporanController::class, 'exportPDFTransaksiKeluar'])->name('laporans.export.transaksi_keluar')->middleware( ['role:owner']);
    Route::get('laporans/Export/LaporanStock', action: [LaporanController::class, 'exportPDFStock'])->name('laporans.export.stock')->middleware( ['role:owner']);

    Route::get('/laporan/Export/Excel/JurnalUmum', [LaporanController::class, 'exportExcelJurnalUmum'])->name('laporans.export.excel.jurnal_umum')->middleware( ['role:owner']);
    Route::get('/laporan/Export/Excel/Neraca', [LaporanController::class, 'exportExcelNeraca'])->name('laporans.export.excel.neraca')->middleware( ['role:owner']);
    Route::get('/laporan/Export/Excel/LabaRugi', [LaporanController::class, 'exportExcelLabaRugi'])->name('laporans.export.excel.laba_rugi')->middleware( ['role:owner']);
    Route::get('/laporan/Export/Excel/ArusKas', [LaporanController::class, 'exportExcelArusKas'])->name('laporans.export.excel.arus_kas')->middleware( ['role:owner']);
    Route::get('/laporan/Export/Excel/PerubahanModal', [LaporanController::class, 'exportExcelPerubahanModal'])->name('laporans.export.excel.perubahan_modal')->middleware( ['role:owner']);
    Route::get('/laporan/Export/Excel/TransaksiMasuk', [LaporanController::class, 'exportExcelTransaksiMasuk'])->name('laporans.export.excel.transaksi_masuk')->middleware( ['role:owner']);
    Route::get('/laporan/Export/Excel/TransaksiKeluar', [LaporanController::class, 'exportExcelTransaksiKeluar'])->name('laporans.export.excel.transaksi_keluar')->middleware( ['role:owner']);
    Route::get('/laporan/Export/Excel/LaporanStock', [LaporanController::class, 'exportExcelLaporanStock'])->name('laporans.export.excel.laporan_stock')->middleware( ['role:owner']);
    Route::get('/laporan/Export/Excel/BukuBesar', [LaporanController::class, 'exportExcelBukuBesar'])->name('laporans.export.excel.buku_besar')->middleware( ['role:owner']);


});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

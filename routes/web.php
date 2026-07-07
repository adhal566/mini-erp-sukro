<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BahanBakuController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\PenggajianController;

Route::get('/', function () {
    return view('dashboard'); 
});

Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
Route::post('/pos/checkout', [PosController::class, 'checkout'])->name('pos.checkout');
Route::get('/transaksi', [PosController::class, 'history'])->name('pos.history');

// Absensi
Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
Route::post('/absensi/clock-in', [AbsensiController::class, 'clockIn'])->name('absensi.clock_in');
Route::get('/reset-absen', function() {
    \App\Models\Absensi::truncate();
    return redirect()->route('absensi.index')->with('success', 'Data absen di-reset! Silakan selfie lagi.');
});

// Penggajian
Route::get('/penggajian', [PenggajianController::class, 'index'])->name('penggajian.index');
Route::post('/penggajian/generate', [PenggajianController::class, 'generate'])->name('penggajian.generate');

Route::resource('bahan-baku', BahanBakuController::class);
Route::resource('produk', ProdukController::class);
Route::post('produk/{produk}/resep', [ProdukController::class, 'storeResep'])->name('produk.resep.store');
Route::delete('produk/{produk}/resep/{resep}', [ProdukController::class, 'destroyResep'])->name('produk.resep.destroy');

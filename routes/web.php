<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenimbanganController;
use App\Http\Controllers\DatabaseController;

/*
|--------------------------------------------------------------------------
| Web Routes - SIMKABAR PT KAI (Angkutan Barang Retail)
|--------------------------------------------------------------------------
| File ini mengatur seluruh rute navigasi aplikasi monitoring penimbangan
| UPT Terminal Babat - PT Kereta Api Indonesia (Persero).
|
*/

// ==========================================
// 1. BERANDA UTAMA
// ==========================================
Route::get('/', function () {
    return view('beranda.index');
})->name('home');

// ==========================================
// 2. MODUL DATABASE & REKAPITULASI
// ==========================================
Route::prefix('database')->name('database.')->group(function () {
    // Halaman Data Utama Penimbangan (Menampilkan list file rekap)
    Route::get('/', [DatabaseController::class, 'index'])->name('index');
    
    // Form Modal Cari Data Penimbangan
    Route::get('/cari', [DatabaseController::class, 'cari'])->name('cari');
    
    // Aksi Generate Rekap Baru (Saat klik tombol "Buat" di Modal Cari Data)
    Route::post('/generate', [DatabaseController::class, 'generateRekap'])->name('generate');
    
    // Aksi Unduh File Excel/CSV Timbangan
    Route::get('/download/{id}', [DatabaseController::class, 'downloadFile'])->name('download');
    
    // Aksi Hapus File Rekap
    Route::delete('/delete/{id}', [DatabaseController::class, 'destroy'])->name('destroy');
});

// ==========================================
// 3. MODUL PENIMBANGAN RETAIL PT KAI
// ==========================================
Route::prefix('penimbangan')->name('penimbangan.')->group(function () {
    
    // Redirect otomatis ke halaman pilih loket jika akses /penimbangan
    Route::get('/', function () {
        return redirect()->route('penimbangan.index');
    });

    // Halaman Pemilihan Loket (Langsung Klik Loket 1 - 4)
    Route::get('/loket', [PenimbanganController::class, 'indexLoket'])->name('index');
    
    // Halaman Workspace Operasional Timbangan (?loket=X)
    Route::get('/workspace', [PenimbanganController::class, 'workspace'])->name('workspace');
    
    // API & Aksi Transaksi Timbangan
    Route::post('/store', [PenimbanganController::class, 'store'])->name('store');
    Route::delete('/delete/{id}', [PenimbanganController::class, 'destroy'])->name('destroy');
    Route::put('/pindah/{id}', [PenimbanganController::class, 'updatePindah'])->name('pindah');
});
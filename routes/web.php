<?php

use App\Http\Controllers\Back\DashboardController;
use App\Http\Controllers\Back\FasilitasController;
use App\Http\Controllers\Back\ReservasiController;
use App\Http\Controllers\Back\ArtikelController;
use App\Http\Controllers\Back\KamarController;
use App\Http\Controllers\Back\UserController;
use App\Http\Controllers\FrontController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Rute Login, Register, Logout bawaan Laravel
Auth::routes();

// ====================================================
// 1. ROUTE PUBLIC (Dapat diakses Publik TANPA Login)
// ====================================================
Route::get('/', [FrontController::class, 'index'])->name('front.index');
Route::get('/kamar-hotel', [FrontController::class, 'kamar'])->name('front.kamar');
Route::get('/check-availability', [FrontController::class, 'checkAvailability'])->name('front.checkAvailability');
Route::get('/api/cek-stok-kamar', [FrontController::class, 'cekStokKamar'])->name('front.cekStokKamar');
Route::get('/kamar-hotel/{slug}', [FrontController::class, 'detailKamar'])->name('front.detailKamar');
Route::get('/fasilitas-hotel', [FrontController::class, 'fasilitas'])->name('front.fasilitas');
Route::get('/artikel-hotel', [FrontController::class, 'artikel'])->name('front.artikel');
Route::get('/artikel-hotel/{slug}', [FrontController::class, 'detailArtikel'])->name('front.detailArtikel');
Route::post('/booking-online', [FrontController::class, 'storeBooking'])->name('front.storeBooking');

// ====================================================
// 2. ROUTE PRIVATE (DASHBOARD BACK-END)
// ====================================================
Route::middleware(['auth'])->group(function () {

    // A. Dashboard (Bisa diakses oleh Admin, Frontline, & Media)
    Route::middleware(['role:admin,frontline,media'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });

    // B. Operasional Hotel (Akses: Admin & Frontline)
    Route::middleware(['role:admin,frontline'])->group(function () {
        // Route Export Excel menggunakan ReservasiController
        Route::get('/reservasi/export', [ReservasiController::class, 'exportExcel'])->name('reservasi.export');
        
        Route::get('/reservasi', [ReservasiController::class, 'index'])->name('reservasi.index');
        Route::put('/reservasi/{id}/status', [ReservasiController::class, 'updateStatus'])->name('reservasi.updateStatus');
        Route::get('/reservasi/{id}/cetak', [ReservasiController::class, 'cetakBukti'])->name('reservasi.cetak');
        Route::delete('/reservasi/{id}', [ReservasiController::class, 'destroy'])->name('reservasi.destroy');

        Route::resource('kamar', KamarController::class);
        Route::resource('fasilitas', FasilitasController::class);
    });

    // C. Media & Artikel (Akses: Admin & Media)
    Route::middleware(['role:admin,media'])->group(function () {
        Route::resource('artikel', ArtikelController::class);
    });

    // D. Pengaturan Sistem & User (Akses: Khusus Admin)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
    });
    
});

// Laravel Filemanager Route
Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
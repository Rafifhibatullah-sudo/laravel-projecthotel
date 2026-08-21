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

// ----------------------------------------------------
// ROUTE YANG WAJIB LOGIN (Ditolak jika belum login)
// ----------------------------------------------------
Route::middleware(['auth'])->group(function () {

    // 1. DIBUKA UNTUK SEMUA ROLE YANG SUDAH LOGIN (Tamu & Admin)
    Route::middleware(['role:admin,tamu'])->group(function () {
        // Halaman Utama / Front-End (Sekarang Wajib Login Dulu)
        Route::get('/', [FrontController::class, 'index'])->name('front.index');
        Route::get('/kamar-hotel', [FrontController::class, 'kamar'])->name('front.kamar');
        Route::get('/kamar-hotel/{id}', [FrontController::class, 'detailKamar'])->name('front.detailKamar');
        Route::get('/fasilitas-hotel', [FrontController::class, 'fasilitas'])->name('front.fasilitas');
        Route::get('/artikel-hotel', [FrontController::class, 'artikel'])->name('front.artikel');
        Route::get('/artikel-hotel/{slug}', [FrontController::class, 'detailArtikel'])->name('front.detailArtikel');
        Route::post('/booking-online', [FrontController::class, 'storeBooking'])->name('front.storeBooking');

        // Dashboard & Reservasi Saya
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/reservasi', [ReservasiController::class, 'index'])->name('reservasi.index');
        Route::put('/reservasi/{id}/status', [ReservasiController::class, 'updateStatus'])->name('reservasi.updateStatus');
        Route::get('/reservasi/export', [ReservasiController::class, 'exportExcel'])->name('reservasi.export');
        Route::delete('/reservasi/{id}', [ReservasiController::class, 'destroy'])->name('reservasi.destroy');
    });

    // 2. KHUSUS ADMIN SAJA (Kelola Data Master)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('kamar', KamarController::class);
        Route::resource('fasilitas', FasilitasController::class);
        Route::resource('artikel', ArtikelController::class);
        Route::resource('users', UserController::class);
    });

});

Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

// Redirect default setelah login
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
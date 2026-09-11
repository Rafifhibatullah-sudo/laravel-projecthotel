<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\Fasilitas;
use App\Models\Kamar;
use App\Models\Reservasi;

class DashboardController extends Controller
{
    public function index()
    {
        $totalKamar     = Kamar::count();
        $totalFasilitas = Fasilitas::count();
        $totalArtikel   = Artikel::count();
        $totalReservasi = Reservasi::count();

        // Poin 1: Menghitung jumlah reservasi berdasarkan badge status
        $pendingReservasi   = Reservasi::where('status', 'pending')->count();
        $confirmedReservasi = Reservasi::where('status', 'confirmed')->count();
        $cancelledReservasi = Reservasi::where('status', 'cancelled')->count();

        // Poin 2: Mengambil 5 data pemesanan/tamu terbaru yang melakukan booking kamar
        $reservasiTerbaru = Reservasi::with('kamar')->latest()->take(5)->get();

        return view('back.dashboard.index', compact(
            'totalKamar', 
            'totalFasilitas', 
            'totalArtikel', 
            'totalReservasi',
            'pendingReservasi',
            'confirmedReservasi',
            'cancelledReservasi',
            'reservasiTerbaru'
        ));
    }
}
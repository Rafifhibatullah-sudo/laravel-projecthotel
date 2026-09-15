<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Kamar;
use App\Models\Fasilitas;
use App\Models\Artikel;
use App\Models\Reservasi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = date('Y-m-d');

        // Data statistik utama
        $totalKamar      = Kamar::count();
        $totalFasilitas  = Fasilitas::count();~
        $totalArtikel    = Artikel::count();
        $totalReservasi  = Reservasi::count();

        // Status Reservasi
        $pendingReservasi   = Reservasi::where('status', 'pending')->count();
        $confirmedReservasi = Reservasi::where('status', 'confirmed')->count();
        $cancelledReservasi = Reservasi::where('status', 'cancelled')->count();

        // Badge Check-In & Check-Out Berdasarkan Status & Tanggal
        $checkInHariIni = Reservasi::whereDate('check_in', $today)
            ->where('status', 'in')
            ->count();

        $checkOutHariIni = Reservasi::whereDate('check_out', $today)
            ->where('status', 'out')
            ->count();
        // Tabel Reservasi Terbaru
        $reservasiTerbaru = Reservasi::with('kamar')
            ->latest()
            ->take(5)
            ->get();

        return view('back.dashboard.index', compact(
            'totalKamar',
            'totalFasilitas',
            'totalArtikel',
            'totalReservasi',
            'pendingReservasi',
            'confirmedReservasi',
            'cancelledReservasi',
            'checkInHariIni',
            'checkOutHariIni',
            'reservasiTerbaru'
        ));
    }
}

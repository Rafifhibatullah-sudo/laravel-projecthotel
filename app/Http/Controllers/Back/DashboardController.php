<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Kamar;
use App\Models\Fasilitas;
use App\Models\Artikel;
use App\Models\Reservasi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->toDateString(); // Tanggal hari ini (YYYY-MM-DD)

        // 1. Data statistik utama
        $totalKamar     = Kamar::count();
        $totalFasilitas = Fasilitas::count();
        $totalArtikel   = Artikel::count();
        $totalReservasi = Reservasi::count();

        // 2. Status Reservasi
        $pendingReservasi   = Reservasi::whereIn('status', ['pending', 'Pending'])->count();
        $confirmedReservasi = Reservasi::whereIn('status', ['confirmed', 'Confirmed', 'in', 'In', 'out', 'Out'])->count();
        $cancelledReservasi = Reservasi::whereIn('status', ['cancelled', 'canceled', 'Dibatalkan', 'Batal'])->count();

        // 3. Total Tamu Sedang Menginap (In-House Guests)
        $checkInHariIni = Reservasi::whereDate('check_in', '<=', $today)
            ->whereDate('check_out', '>=', $today)
            ->whereIn('status', ['in', 'In', 'Check In', 'confirmed', 'Confirmed'])
            ->count();

        // 4. Check-Out Hari Ini
        $checkOutHariIni = Reservasi::whereDate('check_out', $today)
            ->whereIn('status', ['in', 'In', 'out', 'Out', 'Check In', 'Check Out'])
            ->count();

        // 5. Tabel Reservasi Terbaru
        $reservasiTerbaru = Reservasi::with('kamar')
            ->latest()
            ->take(5)
            ->get();

        // ==========================================
        // POIN 5 FIX: Hitung Sisa Stok Kamar Real-time
        // ==========================================
        $kamars = Kamar::all()->map(function ($kamar) use ($today) {
            // Hitung kamar yang terpakai/terbooking untuk hari ini
            $kamarTerpakai = Reservasi::where('kamar_id', $kamar->id)
                ->whereDate('check_in', '<=', $today)
                ->whereDate('check_out', '>', $today) // Masih menginap hari ini
                ->whereIn('status', ['confirmed', 'Confirmed', 'in', 'In', 'Check In'])
                ->sum('jumlah_kamar');

            // Hitung sisa stok (Stok awal - terpakai)
            $sisaStok = $kamar->stok - $kamarTerpakai;
            $kamar->sisa_stok = max(0, $sisaStok); // Tidak boleh minus
            $kamar->stok_terpakai = $kamarTerpakai;

            return $kamar;
        });

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
            'reservasiTerbaru',
            'kamars' // Data kamar dengan kalkulasi sisa stok otomatis
        ));
    }
}
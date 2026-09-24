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
        $validStatus = ['confirmed', 'Confirmed', 'in', 'In', 'Check In', 'out', 'Out', 'Check Out', 'paid', 'Paid'];

        // 1. Data statistik utama
        $totalKamar     = Kamar::count();
        $totalFasilitas = Fasilitas::count();
        $totalArtikel   = Artikel::count();
        $totalReservasi = Reservasi::count();

        // 2. Kalkulasi Pendapatan
        $pendapatanHariIni = Reservasi::whereIn('status', $validStatus)
            ->whereDate('check_in', Carbon::today())
            ->sum('total_harga');

        $pendapatanBulanIni = Reservasi::whereIn('status', $validStatus)
            ->whereMonth('check_in', Carbon::now()->month)
            ->whereYear('check_in', Carbon::now()->year)
            ->sum('total_harga');

        $totalPendapatan = Reservasi::whereIn('status', $validStatus)
            ->sum('total_harga');

        // 3. Status Reservasi
        $pendingReservasi   = Reservasi::whereIn('status', ['pending', 'Pending'])->count();
        $confirmedReservasi = Reservasi::whereIn('status', $validStatus)->count();
        $cancelledReservasi = Reservasi::whereIn('status', ['cancelled', 'canceled', 'Dibatalkan', 'Batal'])->count();

        // 4. Total Tamu Sedang Menginap (In-House Guests)
        $checkInHariIni = Reservasi::whereDate('check_in', '<=', $today)
            ->whereDate('check_out', '>=', $today)
            ->whereIn('status', ['in', 'In', 'Check In', 'confirmed', 'Confirmed'])
            ->count();

        // 5. Check-Out Hari Ini
        $checkOutHariIni = Reservasi::whereDate('check_out', $today)
            ->whereIn('status', ['in', 'In', 'out', 'Out', 'Check In', 'Check Out'])
            ->count();

        // 6. Tabel Reservasi Terbaru
        $reservasiTerbaru = Reservasi::with('kamar')
            ->latest()
            ->take(5)
            ->get();

        // 7. Hitung Sisa Stok Kamar Real-time
        $kamars = Kamar::all()->map(function ($kamar) use ($today) {
            $kamarTerpakai = Reservasi::where('kamar_id', $kamar->id)
                ->whereDate('check_in', '<=', $today)
                ->whereDate('check_out', '>', $today)
                ->whereIn('status', ['confirmed', 'Confirmed', 'in', 'In', 'Check In'])
                ->sum('jumlah_kamar');

            $stokAwal = $kamar->jumlah_kamar ?? $kamar->stok ?? 0;
            $sisaStok = $stokAwal - $kamarTerpakai;
            
            $kamar->sisa_stok = max(0, $sisaStok);
            $kamar->stok_terpakai = $kamarTerpakai;

            return $kamar;
        });

        return view('back.dashboard.index', compact(
            'totalKamar',
            'totalFasilitas',
            'totalArtikel',
            'totalReservasi',
            'pendapatanHariIni',
            'pendapatanBulanIni',
            'totalPendapatan',
            'pendingReservasi',
            'confirmedReservasi',
            'cancelledReservasi',
            'checkInHariIni',
            'checkOutHariIni',
            'reservasiTerbaru',
            'kamars'
        ));
    }
}
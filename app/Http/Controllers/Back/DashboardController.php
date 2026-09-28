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
        $today = Carbon::today('Asia/Jakarta');
        $todayString = $today->toDateString(); // YYYY-MM-DD
        $validStatus = ['confirmed', 'Confirmed', 'in', 'In', 'Check In', 'out', 'Out', 'Check Out', 'paid', 'Paid'];

        // =========================================================================
        // 1. OTOMATISASI STATUS BERDASARKAN WAKTU & TANGGAL
        // =========================================================================

        // A. Otomatis Check-In jika tanggal check_in sudah masuk/lewat dan status masih confirmed/paid
        Reservasi::whereIn('status', ['confirmed', 'Confirmed', 'paid', 'Paid'])
            ->whereDate('check_in', '<=', $todayString)
            ->update([
                'status' => 'in'
            ]);

        // B. Otomatis Check-Out jika tanggal check_out rencana sudah masuk/lewat
        $reservasiHarusOut = Reservasi::whereIn('status', ['in', 'In', 'Check In'])
            ->whereDate('check_out', '<=', $todayString)
            ->get();

        foreach ($reservasiHarusOut as $res) {
            $res->update([
                'status' => 'out',
                'checkout_real' => Carbon::now('Asia/Jakarta') // Catat jam checkout aktual
            ]);
        }

        // =========================================================================
        // 2. DATA STATISTIK UTAMA & STATUS RESERVASI
        // =========================================================================
        $totalKamar     = Kamar::count();
        $totalFasilitas = Fasilitas::count();
        $totalArtikel   = Artikel::count();
        $totalReservasi = Reservasi::count();

        $pendingReservasi   = Reservasi::whereIn('status', ['pending', 'Pending'])->count();
        $confirmedReservasi = Reservasi::whereIn('status', $validStatus)->count();
        $cancelledReservasi = Reservasi::whereIn('status', ['cancelled', 'canceled', 'Dibatalkan', 'Batal'])->count();

        // =========================================================================
        // 3. LOGIKA BADGE CHECK-IN & CHECK-OUT HARI INI (PERSISTEN)
        // =========================================================================

        // Check-In Hari Ini:
        // Menghitung SEMUA tamu yang tanggal masuknya (check_in) HARI INI.
        // Meskipun tamu tersebut checkout mendadak malamnya, historis check-in hari ini TETAP TERHITUNG (+1).
        $checkInHariIni = Reservasi::whereDate('check_in', $todayString)
            ->whereIn('status', ['in', 'In', 'Check In', 'out', 'Out', 'Check Out'])
            ->count();

        // Check-Out Hari Ini:
        // Menghitung tamu yang berstatus 'out' DAN terjadi checkout hari ini
        // (Berdasarkan: checkout_real hari ini OR check_out rencana hari ini OR updated_at hari ini).
        $checkOutHariIni = Reservasi::whereIn('status', ['out', 'Out', 'Check Out'])
            ->where(function($query) use ($todayString) {
                $query->whereDate('checkout_real', $todayString)
                      ->orWhereDate('check_out', $todayString)
                      ->orWhereDate('updated_at', $todayString);
            })
            ->count();

        // =========================================================================
        // 4. KALKULASI PENDAPATAN
        // =========================================================================
        $pendapatanHariIni = Reservasi::whereIn('status', $validStatus)
            ->whereDate('check_in', $todayString)
            ->sum('total_harga');

        $pendapatanBulanIni = Reservasi::whereIn('status', $validStatus)
            ->whereMonth('check_in', Carbon::now('Asia/Jakarta')->month)
            ->whereYear('check_in', Carbon::now('Asia/Jakarta')->year)
            ->sum('total_harga');

        $totalPendapatan = Reservasi::whereIn('status', $validStatus)
            ->sum('total_harga');

        // =========================================================================
        // 5. TABEL RESERVASI TERBARU
        // =========================================================================
        $reservasiTerbaru = Reservasi::with('kamar')
            ->latest()
            ->take(5)
            ->get();

        // =========================================================================
        // 6. HITUNG SISA STOK KAMAR REAL-TIME
        // =========================================================================
        $kamars = Kamar::all()->map(function ($kamar) use ($todayString) {
            $kamarTerpakai = Reservasi::where('kamar_id', $kamar->id)
                ->whereDate('check_in', '<=', $todayString)
                ->whereDate('check_out', '>', $todayString)
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
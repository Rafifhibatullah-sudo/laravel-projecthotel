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
        $totalArtikel = Artikel::count();
        $totalReservasi = Reservasi::count();

        // Ambil 5 data kamar terbaru untuk tabel ringkasan
        $kamarTerbaru   = Kamar::latest()->take(5)->get();

        return view('back.dashboard.index', compact(
            'totalKamar', 
            'totalFasilitas', 
            'totalArtikel', 
            'totalReservasi',
            'kamarTerbaru'
        ));
    }
}
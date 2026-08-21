<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\Fasilitas;
use App\Models\Artikel;
use App\Models\Reservasi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FrontController extends Controller
{
    // Halaman Beranda (Landing Page)
    public function index()
    {
        $kamars    = Kamar::latest()->take(3)->get();
        $fasilitas = Fasilitas::all();
        $artikels  = Artikel::where('status', 'publish')->latest()->take(3)->get();

        return view('front.index', compact('kamars', 'fasilitas', 'artikels'));
    }

    // Halaman Daftar Semua Kamar
    public function kamar()
    {
        $kamars = Kamar::latest()->get();
        return view('front.kamar', compact('kamars'));
    }

    // Halaman Detail Kamar & Form Booking
    public function detailKamar($id)
    {
        $kamar = Kamar::with('fasilitas')->findOrFail($id);
        return view('front.detail-kamar', compact('kamar'));
    }

    // Halaman Daftar Fasilitas
    public function fasilitas()
    {
        $fasilitas = Fasilitas::all();
        return view('front.fasilitas', compact('fasilitas'));
    }

    // Halaman Artikel & Detail Artikel
    public function artikel()
    {
        $artikels = Artikel::where('status', 'publish')->latest()->paginate(6);
        return view('front.artikel', compact('artikels'));
    }

    public function detailArtikel($slug)
    {
        // Cari artikel berdasarkan slug
        $artikel = Artikel::where('slug', $slug)->firstOrFail();

        // OTOMATIS TAMBAHKAN VIEWS +1
        $artikel->increment('views');

        return view('front.detail-artikel', compact('artikel'));
    }

    // Proses Simpan Reservasi / Booking Online
    public function storeBooking(Request $request)
    {
        $request->validate([
            'kamar_id'     => 'required|exists:kamars,id',
            'nama_pemesan' => 'required|string|max:255',
            'email'        => 'required|email',
            'no_hp'        => 'required|numeric',
            'check_in'     => 'required|date',
            'check_out'    => 'required|date|after:check_in',
            'jumlah_kamar' => 'required|numeric|min:1',
        ]);

        $kamar = Kamar::findOrFail($request->kamar_id);

        // Hitung durasi menginap (hari)
        $checkIn  = new \DateTime($request->check_in);
        $checkOut = new \DateTime($request->check_out);
        $durasi   = $checkIn->diff($checkOut)->days;
        if ($durasi == 0) $durasi = 1;

        // Hitung total harga
        $totalHarga = $kamar->harga * $request->jumlah_kamar * $durasi;

        // Generate Kode Booking unik
        $kodeBooking = 'RSV-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        $reservasi = Reservasi::create([
            'kode_booking' => $kodeBooking,
            'kamar_id'     => $request->kamar_id,
            'nama_pemesan' => $request->nama_pemesan,
            'email'        => $request->email,
            'no_hp'        => $request->no_hp,
            'check_in'     => $request->check_in,
            'check_out'    => $request->check_out,
            'jumlah_kamar' => $request->jumlah_kamar,
            'total_harga'  => $totalHarga,
            'status'       => 'pending',
            'catatan'      => $request->catatan,
        ]);

        // Buat Pesan Konfirmasi Otomatis ke WhatsApp Admin
        $nomorWAAdmin = '6282186993746'; // Ganti dengan nomor WA Admin Hotel kamu
        $pesan = "Halo Admin Grand Horizon Hotel, saya ingin mengonfirmasi booking online:\n\n" .
            "*Kode Booking:* {$kodeBooking}\n" .
            "*Nama:* {$request->nama_pemesan}\n" .
            "*Kamar:* {$kamar->nama_kamar}\n" .
            "*Check In:* {$request->check_in}\n" .
            "*Check Out:* {$request->check_out}\n" .
            "*Jumlah Kamar:* {$request->jumlah_kamar} Unit\n" .
            "*Total Harga:* Rp " . number_format($totalHarga, 0, ',', '.') . "\n\n" .
            "Mohon diproses, terima kasih!";

        $urlWA = "https://wa.me/{$nomorWAAdmin}?text=" . urlencode($pesan);

        return redirect()->away($urlWA);
    }
}

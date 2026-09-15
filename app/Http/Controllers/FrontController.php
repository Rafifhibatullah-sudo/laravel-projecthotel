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
        $today    = date('Y-m-d');
        $tomorrow = date('Y-m-d', strtotime('+1 day'));

        // Ambil data kamar dan hitung sisa stok untuk hari ini
        $kamars = Kamar::take(3)->get()->map(function ($kamar) use ($today, $tomorrow) {
            $bookedCount = Reservasi::where('kamar_id', $kamar->id)
                ->whereIn('status', ['pending', 'confirmed', 'in'])
                ->where(function ($query) use ($today, $tomorrow) {
                    $query->whereBetween('check_in', [$today, $tomorrow])
                        ->orWhereBetween('check_out', [$today, $tomorrow])
                        ->orWhere(function ($q) use ($today, $tomorrow) {
                            $q->where('check_in', '<=', $today)
                                ->where('check_out', '>=', $tomorrow);
                        });
                })->sum('jumlah_kamar');

            // Set nilai sisa_stok secara dinamis
            $kamar->sisa_stok = max(0, $kamar->jumlah_kamar - $bookedCount);
            return $kamar;
        });

        $fasilitas = Fasilitas::take(3)->get();
        $artikels  = Artikel::where('status', 'publish')->latest()->take(3)->get();

        return view('front.index', compact('kamars', 'fasilitas', 'artikels'));
    }

    // Halaman Daftar Semua Kamar (Hitung stok dinamis hari ini)
    public function kamar()
    {
        $today    = date('Y-m-d');
        $tomorrow = date('Y-m-d', strtotime('+1 day'));

        $kamars = Kamar::all()->map(function ($kamar) use ($today, $tomorrow) {
            $bookedCount = Reservasi::where('kamar_id', $kamar->id)
                ->whereIn('status', ['pending', 'confirmed', 'in'])
                ->where(function ($query) use ($today, $tomorrow) {
                    $query->whereBetween('check_in', [$today, $tomorrow])
                        ->orWhereBetween('check_out', [$today, $tomorrow])
                        ->orWhere(function ($q) use ($today, $tomorrow) {
                            $q->where('check_in', '<=', $today)
                                ->where('check_out', '>=', $tomorrow);
                        });
                })->sum('jumlah_kamar');

            $kamar->sisa_stok = max(0, $kamar->jumlah_kamar - $bookedCount);
            return $kamar;
        });

        return view('front.kamar', compact('kamars'));
    }

    // Halaman Detail Kamar & Form Booking
    public function detailKamar($id)
    {
        $kamar = Kamar::with('fasilitas')->findOrFail($id);
        return view('front.detail-kamar', compact('kamar'));
    }

    // Halaman Daftar Seluruh Fasilitas
    public function fasilitas()
    {
        $fasilitas = Fasilitas::all();
        return view('front.fasilitas', compact('fasilitas'));
    }

    // Halaman Artikel & Detail Artikel
    public function artikel()
    {
        $artikels = Artikel::where('status', 'publish')->latest()->get();
        return view('front.artikel', compact('artikels'));
    }

    public function detailArtikel($slug)
    {
        $artikel = Artikel::where('slug', $slug)->firstOrFail();
        $artikel->increment('views');

        return view('front.detail-artikel', compact('artikel'));
    }

    // Logika Cek Ketersediaan Kamar berdasarkan Tanggal Booking
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'check_in'  => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ]);

        $checkIn  = $request->check_in;
        $checkOut = $request->check_out;

        $kamars = Kamar::all()->map(function ($kamar) use ($checkIn, $checkOut) {
            $bookedCount = Reservasi::where('kamar_id', $kamar->id)
                ->whereIn('status', ['pending', 'confirmed', 'in'])
                ->where(function ($query) use ($checkIn, $checkOut) {
                    $query->whereBetween('check_in', [$checkIn, $checkOut])
                        ->orWhereBetween('check_out', [$checkIn, $checkOut])
                        ->orWhere(function ($q) use ($checkIn, $checkOut) {
                            $q->where('check_in', '<=', $checkIn)
                                ->where('check_out', '>=', $checkOut);
                        });
                })->sum('jumlah_kamar');

            $kamar->sisa_stok = max(0, $kamar->jumlah_kamar - $bookedCount);
            return $kamar;
        });

        return view('front.kamar', compact('kamars', 'checkIn', 'checkOut'));
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

        $kamar    = Kamar::findOrFail($request->kamar_id);
        $checkIn  = $request->check_in;
        $checkOut = $request->check_out;

        $bookedCount = Reservasi::where('kamar_id', $kamar->id)
            ->whereIn('status', ['pending', 'confirmed', 'in'])
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in', [$checkIn, $checkOut])
                    ->orWhereBetween('check_out', [$checkIn, $checkOut])
                    ->orWhere(function ($q) use ($checkIn, $checkOut) {
                        $q->where('check_in', '<=', $checkIn)
                            ->where('check_out', '>=', $checkOut);
                    });
            })->sum('jumlah_kamar');

        $sisaStok = $kamar->jumlah_kamar - $bookedCount;

        if ($request->jumlah_kamar > $sisaStok) {
            return redirect()->back()->with('error', 'Maaf, stok kamar tidak mencukupi untuk tanggal yang dipilih. Sisa stok: ' . $sisaStok);
        }

        $date1  = new \DateTime($checkIn);
        $date2  = new \DateTime($checkOut);
        $durasi = $date1->diff($date2)->days;
        if ($durasi == 0) $durasi = 1;

        $totalHarga  = $kamar->harga * $request->jumlah_kamar * $durasi;
        $kodeBooking = 'RSV-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        Reservasi::create([
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

        $nomorWAAdmin = '6282186993746';
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

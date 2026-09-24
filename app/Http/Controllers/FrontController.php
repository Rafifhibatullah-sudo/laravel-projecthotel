<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\Fasilitas;
use App\Models\Artikel;
use App\Models\Reservasi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class FrontController extends Controller
{
    /**
     * Halaman Beranda (Landing Page)
     */
    public function index()
    {
        $today    = Carbon::today()->toDateString();
        $tomorrow = Carbon::tomorrow()->toDateString();

        // OPSI 1: Update status reservasi otomatis menjadi 'out' jika tanggal check_out <= hari ini
        Reservasi::whereDate('check_out', '<=', $today)
            ->whereIn('status', ['in', 'In', 'Check In', 'confirmed', 'Confirmed'])
            ->update(['status' => 'out']);

        // Ambil 3 data kamar dan hitung sisa stok hari ini
        $kamars = Kamar::take(3)->get()->map(function ($kamar) use ($today, $tomorrow) {
            $bookedCount = Reservasi::where('kamar_id', $kamar->id)
                ->whereIn('status', ['pending', 'confirmed', 'in', 'Pending', 'Confirmed', 'Check In'])
                ->where(function ($query) use ($today, $tomorrow) {
                    $query->where('check_in', '<', $tomorrow)
                          ->where('check_out', '>', $today);
                })->sum('jumlah_kamar');

            $kamar->sisa_stok = max(0, $kamar->jumlah_kamar - $bookedCount);
            return $kamar;
        });

        $fasilitas = Fasilitas::take(3)->get();
        $artikels  = Artikel::where('status', 'publish')->latest()->take(3)->get();

        return view('front.index', compact('kamars', 'fasilitas', 'artikels'));
    }

    /**
     * Halaman Daftar Semua Kamar
     */
    public function kamar()
    {
        $today    = Carbon::today()->toDateString();
        $tomorrow = Carbon::tomorrow()->toDateString();

        // Update status otomatis sebelum mengambil data kamar
        Reservasi::whereDate('check_out', '<=', $today)
            ->whereIn('status', ['in', 'In', 'Check In', 'confirmed', 'Confirmed'])
            ->update(['status' => 'out']);

        $kamars = Kamar::all()->map(function ($kamar) use ($today, $tomorrow) {
            $bookedCount = Reservasi::where('kamar_id', $kamar->id)
                ->whereIn('status', ['pending', 'confirmed', 'in', 'Pending', 'Confirmed', 'Check In'])
                ->where(function ($query) use ($today, $tomorrow) {
                    $query->where('check_in', '<', $tomorrow)
                          ->where('check_out', '>', $today);
                })->sum('jumlah_kamar');

            $kamar->sisa_stok = max(0, $kamar->jumlah_kamar - $bookedCount);
            return $kamar;
        });

        return view('front.kamar', compact('kamars'));
    }

    /**
     * Halaman Detail Kamar Berdasarkan Slug
     */
    public function detailKamar($slug = null)
    {
        if (!$slug) {
            return redirect()->route('front.kamar');
        }

        $kamar = Kamar::where('slug', $slug)->firstOrFail();

        return view('front.detail-kamar', compact('kamar'));
    }

    /**
     * Halaman Daftar Seluruh Fasilitas
     */
    public function fasilitas()
    {
        $fasilitas = Fasilitas::all();
        return view('front.fasilitas', compact('fasilitas'));
    }

    /**
     * Halaman Daftar Artikel
     */
    public function artikel()
    {
        $artikels = Artikel::where('status', 'publish')->latest()->get();
        return view('front.artikel', compact('artikels'));
    }

    /**
     * Halaman Detail Artikel
     */
    public function detailArtikel($slug)
    {
        $artikel = Artikel::where('slug', $slug)->firstOrFail();
        $artikel->increment('views');

        return view('front.detail-artikel', compact('artikel'));
    }

    /**
     * Cek Ketersediaan Kamar berdasarkan Filter Tanggal
     */
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
                ->whereIn('status', ['pending', 'confirmed', 'in', 'Pending', 'Confirmed', 'Check In'])
                ->where(function ($query) use ($checkIn, $checkOut) {
                    $query->where('check_in', '<', $checkOut)
                          ->where('check_out', '>', $checkIn);
                })->sum('jumlah_kamar');

            $kamar->sisa_stok = max(0, $kamar->jumlah_kamar - $bookedCount);
            return $kamar;
        });

        return view('front.kamar', compact('kamars', 'checkIn', 'checkOut'));
    }

    /**
     * Endpoint API Cek Stok Kamar secara Realtime (AJAX / Fetch)
     */
    public function cekStokKamar(Request $request)
    {
        $kamarId  = $request->kamar_id;
        $checkIn  = $request->check_in;
        $checkOut = $request->check_out;

        $kamar = Kamar::find($kamarId);
        if (!$kamar) {
            return response()->json(['sisa_stok' => 0], 404);
        }

        if (!$checkIn || !$checkOut) {
            return response()->json([
                'sisa_stok'  => $kamar->jumlah_kamar,
                'stok_total' => $kamar->jumlah_kamar
            ]);
        }

        // Hitung total kamar yang dipesan pada rentang tanggal terpilih
        $bookedCount = Reservasi::where('kamar_id', $kamarId)
            ->whereIn('status', ['pending', 'confirmed', 'in', 'Pending', 'Confirmed', 'Check In'])
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->where('check_in', '<', $checkOut)
                      ->where('check_out', '>', $checkIn);
            })
            ->sum('jumlah_kamar');

        $sisaStok = max(0, $kamar->jumlah_kamar - $bookedCount);

        return response()->json([
            'sisa_stok'  => $sisaStok,
            'stok_total' => $kamar->jumlah_kamar
        ]);
    }

    /**
     * Proses Simpan Reservasi / Booking Online
     */
    public function storeBooking(Request $request)
    {
        // 1. Validasi Input Data & File Bukti Pembayaran
        $request->validate([
            'kamar_id'          => 'required|exists:kamars,id',
            'nama_pemesan'      => 'required|string|max:255',
            'email'             => 'required|email',
            'no_hp'             => 'required',
            'check_in'          => 'required|date',
            'check_out'         => 'required|date|after:check_in',
            'jumlah_kamar'      => 'required|numeric|min:1',
            'metode_pembayaran' => 'required|string', // <-- Validasi Ditambahkan
            'bukti_bayar'       => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $kamar    = Kamar::findOrFail($request->kamar_id);
        $checkIn  = $request->check_in;
        $checkOut = $request->check_out;

        // 2. Validasi Ketersediaan Stok
        $bookedCount = Reservasi::where('kamar_id', $kamar->id)
            ->whereIn('status', ['pending', 'confirmed', 'in', 'Pending', 'Confirmed', 'Check In'])
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->where('check_in', '<', $checkOut)
                      ->where('check_out', '>', $checkIn);
            })->sum('jumlah_kamar');

        $sisaStok = $kamar->jumlah_kamar - $bookedCount;

        if ($request->jumlah_kamar > $sisaStok) {
            return redirect()->back()->with('error', 'Maaf, stok kamar tidak mencukupi untuk tanggal yang dipilih. Sisa stok: ' . $sisaStok);
        }

        // 3. Hitung Durasi dan Total Harga
        $date1  = new \DateTime($checkIn);
        $date2  = new \DateTime($checkOut);
        $durasi = $date1->diff($date2)->days;
        if ($durasi == 0) {
            $durasi = 1;
        }

        $totalHarga  = $kamar->harga * $request->jumlah_kamar * $durasi;
        $kodeBooking = 'RSV-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        // 4. Proses Upload File Bukti Pembayaran
        $buktiBayarPath = null;
        if ($request->hasFile('bukti_bayar')) {
            $buktiBayarPath = $request->file('bukti_bayar')->store('bukti_bayar', 'public');
        }

        // 5. Simpan Data Reservasi ke Database
        Reservasi::create([
            'kode_booking'      => $kodeBooking,
            'kamar_id'          => $request->kamar_id,
            'nama_pemesan'      => $request->nama_pemesan,
            'email'             => $request->email,
            'no_hp'             => $request->no_hp,
            'check_in'          => $request->check_in,
            'check_out'         => $request->check_out,
            'jumlah_kamar'      => $request->jumlah_kamar,
            'total_harga'       => $totalHarga,
            'status'            => 'pending',
            'catatan'           => $request->catatan,
            'bukti_pembayaran'  => $buktiBayarPath,
            'metode_pembayaran' => $request->metode_pembayaran, // <-- Disimpan ke Database
        ]);

        // 6. Format & Redirect ke WhatsApp Admin
        $nomorWAAdmin = '6282186993746';
        $metodeText   = strtoupper($request->metode_pembayaran);

        $pesan = "Halo Admin Grand Horizon Hotel, saya telah melakukan booking online dan mengunggah bukti bayar:\n\n" .
            "*Kode Booking:* {$kodeBooking}\n" .
            "*Nama:* {$request->nama_pemesan}\n" .
            "*Kamar:* {$kamar->nama_kamar}\n" .
            "*Check In:* {$request->check_in}\n" .
            "*Check Out:* {$request->check_out}\n" .
            "*Jumlah Kamar:* {$request->jumlah_kamar} Unit\n" .
            "*Metode Bayar:* {$metodeText}\n" . 
            "*Total Harga:* Rp " . number_format($totalHarga, 0, ',', '.') . "\n\n" .
            "Mohon dicek bukti pembayarannya pada sistem admin. Terima kasih!";

        $urlWA = "https://wa.me/{$nomorWAAdmin}?text=" . urlencode($pesan);

        return redirect()->away($urlWA);
    }
}
<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use App\Models\Kamar;
use App\Models\Reservasi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KamarController extends Controller
{
    /**
     * Tampilkan daftar kamar beserta sisa stok
     */
    public function index()
    {
        $today = Carbon::today()->toDateString();

        $kamars = Kamar::with('fasilitas')->latest()->get()->map(function ($kamar) use ($today) {
            $kamarTerpakai = Reservasi::where('kamar_id', $kamar->id)
                ->whereDate('check_in', '<=', $today)
                ->whereDate('check_out', '>', $today)
                ->whereIn('status', ['confirmed', 'Confirmed', 'in', 'In', 'Check In'])
                ->sum('jumlah_kamar');

            $totalStok = $kamar->jumlah_kamar ?? $kamar->stok ?? 0;

            $kamar->sisa_stok = max(0, $totalStok - $kamarTerpakai);
            $kamar->terpakai  = $kamarTerpakai;

            return $kamar;
        });

        return view('back.kamar.index', compact('kamars'));
    }

    /**
     * Form tambah kamar
     */
    public function create()
    {
        $fasilitas = Fasilitas::all();
        return view('back.kamar.create', compact('fasilitas'));
    }

    /**
     * Simpan data kamar baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kamar'   => 'required|string|max:255',
            'tipe_kamar'   => 'required|string|max:255',
            'harga'        => 'required|numeric',
            'jumlah_kamar' => 'required|numeric',
            'deskripsi'    => 'nullable',
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'fasilitas'    => 'nullable|array', // Disamakan menjadi 'fasilitas'
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('kamars', 'public');
        }

        $kamar = Kamar::create([
            'nama_kamar'   => $request->nama_kamar,
            'tipe_kamar'   => $request->tipe_kamar,
            'harga'        => $request->harga,
            'jumlah_kamar' => $request->jumlah_kamar,
            'deskripsi'    => strip_tags($request->deskripsi),
            'foto'         => $fotoPath,
        ]);

        // Menggunakan sync agar data di tabel pivot kamar_fasilitas tersimpan aman
        if ($request->has('fasilitas')) {
            $kamar->fasilitas()->sync($request->fasilitas);
        }

        return redirect()->route('kamar.index')->with('success', 'Data Kamar Berhasil Ditambahkan!');
    }

    /**
     * Tampilkan detail kamar
     */
    public function show($id)
    {
        // Mendukung pencarian lewat ID maupun Slug
        $kamar = Kamar::with('fasilitas')
            ->where('id', $id)
            ->orWhere('slug', $id)
            ->firstOrFail();

        return view('back.kamar.show', compact('kamar'));
    }

    /**
     * Form edit kamar
     */
    public function edit($id)
    {
        $kamar = Kamar::with('fasilitas')
            ->where('id', $id)
            ->orWhere('slug', $id)
            ->firstOrFail();

        $fasilitas = Fasilitas::all();

        return view('back.kamar.edit', compact('kamar', 'fasilitas'));
    }

    /**
     * Update data kamar
     */
    public function update(Request $request, $id)
    {
        $kamar = Kamar::where('id', $id)
            ->orWhere('slug', $id)
            ->firstOrFail();

        $request->validate([
            'nama_kamar'   => 'required|string|max:255',
            'tipe_kamar'   => 'required|string|max:255',
            'harga'        => 'required|numeric',
            'jumlah_kamar' => 'required|numeric',
            'deskripsi'    => 'nullable',
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'fasilitas'    => 'nullable|array',
        ]);

        $fotoPath = $kamar->foto;
        if ($request->hasFile('foto')) {
            if ($kamar->foto && Storage::disk('public')->exists($kamar->foto)) {
                Storage::disk('public')->delete($kamar->foto);
            }
            $fotoPath = $request->file('foto')->store('kamars', 'public');
        }

        $kamar->update([
            'nama_kamar'   => $request->nama_kamar,
            'tipe_kamar'   => $request->tipe_kamar,
            'harga'        => $request->harga,
            'jumlah_kamar' => $request->jumlah_kamar,
            'deskripsi'    => strip_tags($request->deskripsi),
            'foto'         => $fotoPath,
        ]);

        // Sinkronisasi data fasilitas (otomatis hapus yang tidak dicentang & tambah yang baru)
        $kamar->fasilitas()->sync($request->fasilitas ?? []);

        return redirect()->route('kamar.index')->with('success', 'Data Kamar Berhasil Diperbarui!');
    }

    /**
     * Hapus kamar
     */
    public function destroy($id)
    {
        $kamar = Kamar::where('id', $id)
            ->orWhere('slug', $id)
            ->firstOrFail();

        if ($kamar->foto && Storage::disk('public')->exists($kamar->foto)) {
            Storage::disk('public')->delete($kamar->foto);
        }

        $kamar->fasilitas()->detach();
        $kamar->delete();

        return redirect()->route('kamar.index')->with('success', 'Data Kamar Berhasil Dihapus!');
    }
}
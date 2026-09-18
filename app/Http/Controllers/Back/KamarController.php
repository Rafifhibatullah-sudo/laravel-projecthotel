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

    public function index()
    {
        $today = Carbon::today()->toDateString();

        // Mengambil semua kamar beserta kalkulasi sisa stok real-time
        $kamars = Kamar::latest()->get()->map(function ($kamar) use ($today) {
            // Hitung kamar yang terpakai/terbooking untuk hari ini
            $kamarTerpakai = Reservasi::where('kamar_id', $kamar->id)
                ->whereDate('check_in', '<=', $today)
                ->whereDate('check_out', '>', $today)
                ->whereIn('status', ['confirmed', 'Confirmed', 'in', 'In', 'Check In'])
                ->sum('jumlah_kamar');

            // Total stok awal dari database
            $totalStok = $kamar->jumlah_kamar ?? $kamar->stok ?? 0;

            // Hitung sisa stok real-time
            $kamar->sisa_stok = max(0, $totalStok - $kamarTerpakai);
            $kamar->terpakai = $kamarTerpakai;

            return $kamar;
        });

        return view('back.kamar.index', compact('kamars'));
    }

    public function create()
    {
        $fasilitas = Fasilitas::all();
        return view('back.kamar.create', compact('fasilitas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kamar'   => 'required',
            'tipe_kamar'   => 'required',
            'harga'        => 'required|numeric',
            'jumlah_kamar' => 'required|numeric',
            'deskripsi'    => 'nullable',
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'fasilitas_id' => 'nullable|array',
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

        if ($request->has('fasilitas_id')) {
            $kamar->fasilitas()->attach($request->fasilitas_id);
        }

        return redirect()->route('kamar.index')->with('success', 'Data Kamar Berhasil Ditambahkan!');
    }

    public function show($id)
    {
        $kamar = Kamar::with('fasilitas')->findOrFail($id);
        return view('back.kamar.show', compact('kamar'));
    }

    public function edit($id)
    {
        $kamar = Kamar::with('fasilitas')->findOrFail($id);
        $fasilitas = Fasilitas::all();
        return view('back.kamar.edit', compact('kamar', 'fasilitas'));
    }

    public function update(Request $request, $id)
    {
        $kamar = Kamar::findOrFail($id);

        $request->validate([
            'nama_kamar'   => 'required',
            'tipe_kamar'   => 'required',
            'harga'        => 'required|numeric',
            'jumlah_kamar' => 'required|numeric',
            'deskripsi'    => 'nullable',
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'fasilitas'    => 'array',
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

        $kamar->fasilitas()->sync($request->fasilitas ?? []);

        return redirect()->route('kamar.index')->with('success', 'Data Kamar Berhasil Diperbarui!');
    }

    public function destroy($id)
    {
        $kamar = Kamar::findOrFail($id);

        if ($kamar->foto && Storage::disk('public')->exists($kamar->foto)) {
            Storage::disk('public')->delete($kamar->foto);
        }

        $kamar->fasilitas()->detach();
        $kamar->delete();

        return redirect()->route('kamar.index')->with('success', 'Data Kamar Berhasil Dihapus!');
    }
}
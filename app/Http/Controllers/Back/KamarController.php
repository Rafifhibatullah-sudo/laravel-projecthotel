<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use App\Models\Kamar;
use App\Models\Reservasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KamarController extends Controller
{
    public function index()
    {
        $kamars = Kamar::latest()->get();
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
            'deskripsi'    => $request->deskripsi,
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
            'deskripsi'    => $request->deskripsi,
            'foto'         => $fotoPath,
        ]);

        // Update relasi fasilitas di tabel pivot
        $kamar->fasilitas()->sync($request->fasilitas ?? []);

        return redirect()->route('kamar.index')->with('success', 'Data Kamar Berhasil Diperbarui!');
    }

    public function destroy($id)
    {
        $kamar = Kamar::findOrFail($id);

        if ($kamar->foto && Storage::disk('public')->exists($kamar->foto)) {
            Storage::disk('public')->delete($kamar->foto);
        }

        $kamar->fasilitas()->detach(); // Hapus relasi pivot
        $kamar->delete();

        return redirect()->route('kamar.index')->with('success', 'Data Kamar Berhasil Dihapus!');
    }

    public function reservasis()
{
    return $this->hasMany(Reservasi::class, 'kamar_id');
}
}

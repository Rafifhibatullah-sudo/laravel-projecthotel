<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use Illuminate\Http\Request;

class FasilitasController extends Controller
{
    public function index()
    {
        $fasilitas = Fasilitas::latest()->get();
        return view('back.fasilitas.index', compact('fasilitas'));
    }

    public function create()
    {
        return view('back.fasilitas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_fasilitas' => 'required',
            'deskripsi'      => 'nullable',
        ]);

        Fasilitas::create([
            'nama_fasilitas' => $request->nama_fasilitas,
            'deskripsi'      => $request->deskripsi,
        ]);

        return redirect()->route('fasilitas.index')->with('success', 'Fasilitas Berhasil Ditambahkan!');
    }

    public function edit($id)
    {
        $fasilitas = Fasilitas::findOrFail($id);
        return view('back.fasilitas.edit', compact('fasilitas'));
    }

    public function update(Request $request, $id)
    {
        $fasilitas = Fasilitas::findOrFail($id);

        $request->validate([
            'nama_fasilitas' => 'required|unique:fasilitas,nama_fasilitas,' . $id,
            'deskripsi'      => 'nullable',
        ]);

        $fasilitas->update([
            'nama_fasilitas' => $request->nama_fasilitas,
            'deskripsi'      => $request->deskripsi,
        ]);

        return redirect()->route('fasilitas.index')->with('success', 'Fasilitas Berhasil Diperbarui!');
    }

    public function show($id)
    {
        $fasilitas = Fasilitas::with('kamars')->findOrFail($id);
        return view('back.fasilitas.show', compact('fasilitas'));
    }

    public function destroy($id)
    {
        $fasilitas = Fasilitas::findOrFail($id);

        // Lepas relasi di tabel pivot sebelum dihapus agar data aman
        $fasilitas->kamars()->detach();
        $fasilitas->delete();

        return redirect()->route('fasilitas.index')->with('success', 'Fasilitas Berhasil Dihapus!');
    }
}

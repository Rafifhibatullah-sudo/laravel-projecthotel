<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ArtikelController extends Controller
{
    public function index()
    {
        $artikels = Artikel::latest()->get();
        return view('back.artikel.index', compact('artikels'));
    }

    public function create()
    {
        return view('back.artikel.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'    => 'required|unique:artikels,judul',
            'kategori' => 'required',
            'isi'      => 'required',
            'status'   => 'required|in:publish,draft',
            'gambar'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('artikels', 'public');
        }

        Artikel::create([
            'judul'    => $request->judul,
            'slug'     => Str::slug($request->judul),
            'kategori' => $request->kategori,
            'isi'      => $request->isi,
            'status'   => $request->status,
            'gambar'   => $gambarPath,
            'views'    => 0,
        ]);

        return redirect()->route('artikel.index')->with('success', 'Artikel Berhasil Ditambahkan!');
    }

    public function edit($id)
    {
        $artikel = Artikel::findOrFail($id);
        return view('back.artikel.edit', compact('artikel'));
    }

    public function update(Request $request, $id)
    {
        $artikel = Artikel::findOrFail($id);

        $request->validate([
            'judul'    => 'required|unique:artikels,judul,' . $id,
            'kategori' => 'required',
            'isi'      => 'required',
            'status'   => 'required|in:publish,draft',
            'gambar'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $gambarPath = $artikel->gambar;
        if ($request->hasFile('gambar')) {
            if ($artikel->gambar && Storage::disk('public')->exists($artikel->gambar)) {
                Storage::disk('public')->delete($artikel->gambar);
            }
            $gambarPath = $request->file('gambar')->store('artikels', 'public');
        }

        $artikel->update([
            'judul'    => $request->judul,
            'slug'     => Str::slug($request->judul),
            'kategori' => $request->kategori,
            'isi'      => $request->isi,
            'status'   => $request->status,
            'gambar'   => $gambarPath,
        ]);

        return redirect()->route('artikel.index')->with('success', 'Artikel Berhasil Diperbarui!');
    }

    public function destroy($id)
    {
        $artikel = Artikel::findOrFail($id);

        if ($artikel->gambar && Storage::disk('public')->exists($artikel->gambar)) {
            Storage::disk('public')->delete($artikel->gambar);
        }

        $artikel->delete();

        return redirect()->route('artikel.index')->with('success', 'Artikel Berhasil Dihapus!');
    }
    public function show($id)
    {
        $artikel = Artikel::findOrFail($id);
        return view('back.artikel.show', compact('artikel'));
    }
}
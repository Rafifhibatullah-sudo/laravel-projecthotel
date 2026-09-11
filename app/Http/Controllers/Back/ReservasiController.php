<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Reservasi;
use Illuminate\Http\Request;
use App\Exports\ReservasiExport;
use Maatwebsite\Excel\Facades\Excel;

class ReservasiController extends Controller
{
    // Tampilkan Seluruh Data Reservasi di Panel Admin
    public function index()
    {
        $reservasis = Reservasi::with('kamar')->latest()->get(); // Ambil data reservasi dengan relasi kamar
        return view('back.reservasi.index', compact('reservasis'));
    }

    // Ubah Status Pemesanan (Pending / Confirmed / Cancelled)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled', // Validasi status yang diperbolehkan atau mencegah masuk nya data ilegal
        ]);

        $reservasi = Reservasi::findOrFail($id);
        $reservasi->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status reservasi berhasil diperbarui!');
    }

    // Hapus Data Reservasi
    public function destroy($id)
    {
        $reservasi = Reservasi::findOrFail($id);
        $reservasi->delete();

        return redirect()->back()->with('success', 'Data reservasi berhasil dihapus!');
    }

    // Function untuk Download Laporan Excel
    public function exportExcel()
    {
        return Excel::download(new ReservasiExport, 'Laporan_Reservasi_Hotel_' . date('Ymd_His') . '.xlsx');
    }
}
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
        $reservasis = Reservasi::with('kamar')->latest()->get();
        return view('back.reservasi.index', compact('reservasis'));
    }

    // Ubah Status Pemesanan (pending / confirmed / in / out / cancelled)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,in,out,cancelled',
        ]);

        $reservasi = Reservasi::findOrFail($id);
        $reservasi->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status reservasi berhasil diperbarui menjadi ' . strtoupper($request->status) . '!');
    }

    // Fitur Cetak Bukti Pembayaran / Nota Kuitansi (Poin 5)
    public function cetakBukti($id)
    {
        $reservasi = Reservasi::with('kamar')->findOrFail($id);
        return view('back.reservasi.cetak_bukti', compact('reservasi'));
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
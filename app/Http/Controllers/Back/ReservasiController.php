<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Reservasi;
use Illuminate\Http\Request;
use App\Exports\ReservasiExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

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
            'status' => 'required|in:pending,confirmed,in,out,cancelled,Check In,Check Out',
        ]);

        $reservasi   = Reservasi::findOrFail($id);
        $statusInput = strtolower($request->status);

        $updateData = ['status' => $request->status];

        // Jika status diubah menjadi OUT / CHECK OUT (Termasuk Checkout Mendadak)
        if (in_array($statusInput, ['out', 'check out'])) {
            $updateData['status']        = 'out';
            $updateData['checkout_real'] = Carbon::now('Asia/Jakarta'); // Simpan waktu real saat tombol ditekan
        } 
        // Jika status dikembalikan ke IN / CHECK IN
        elseif (in_array($statusInput, ['in', 'check in'])) {
            $updateData['status']        = 'in';
            $updateData['checkout_real'] = null; // Reset nilai checkout_real
        }

        $reservasi->update($updateData);

        return redirect()->back()->with('success', 'Status reservasi berhasil diperbarui menjadi ' . strtoupper($request->status) . '!');
    }

    // Update Data Reservasi (Termasuk Jam Check-In & Check-Out jika diedit oleh Admin)
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pemesan'  => 'required|string|max:255',
            'email'         => 'required|email',
            'no_hp'         => 'required',
            'check_in'      => 'required|date',
            'jam_check_in'  => 'required',
            'check_out'     => 'required|date|after:check_in',
            'jam_check_out' => 'required',
            'jumlah_kamar'  => 'required|numeric|min:1',
            'status'        => 'required',
        ]);

        $reservasi = Reservasi::findOrFail($id);

        // Gabungkan tanggal dan jam menjadi Datetime utuh
        $checkInDateTime  = Carbon::parse($request->check_in . ' ' . $request->jam_check_in)->format('Y-m-d H:i:s');
        $checkOutDateTime = Carbon::parse($request->check_out . ' ' . $request->jam_check_out)->format('Y-m-d H:i:s');

        $reservasi->update([
            'nama_pemesan'  => $request->nama_pemesan,
            'email'         => $request->email,
            'no_hp'         => $request->no_hp,
            'check_in'      => $checkInDateTime,
            'jam_check_in'  => $request->jam_check_in,
            'check_out'     => $checkOutDateTime,
            'jam_check_out' => $request->jam_check_out,
            'jumlah_kamar'  => $request->jumlah_kamar,
            'status'        => $request->status,
            'catatan'       => $request->catatan,
        ]);

        return redirect()->back()->with('success', 'Data reservasi berhasil diperbarui!');
    }

    // Fitur Cetak Bukti Pembayaran / Nota Kuitansi
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
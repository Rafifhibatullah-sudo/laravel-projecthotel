<?php

namespace App\Exports;

use App\Models\Reservasi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReservasiExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Ambil seluruh data reservasi beserta data kamar relasinya
        return Reservasi::with('kamar')->latest()->get();
    }

    // Menentukan Nama Header Kolom Excel
    public function headings(): array
    {
        return [
            'Kode Booking',
            'Nama Pemesan',
            'Email',
            'No HP / WA',
            'Nama Kamar',
            'Check In',
            'Check Out',
            'Jumlah Kamar',
            'Total Harga',
            'Status',
            'Tanggal Booking',
        ];
    }

    // Memetakan Isi Data per Baris Excel
    public function map($reservasi): array
    {
        return [
            $reservasi->kode_booking,
            $reservasi->nama_pemesan,
            $reservasi->email,
            $reservasi->no_hp,
            $reservasi->kamar ? $reservasi->kamar->nama_kamar : 'Kamar Dihapus',
            $reservasi->check_in,
            $reservasi->check_out,
            $reservasi->jumlah_kamar . ' Unit',
            'Rp ' . number_format($reservasi->total_harga, 0, ',', '.'),
            strtoupper($reservasi->status),
            $reservasi->created_at->format('d-m-Y H:i'),
        ];
    }
}
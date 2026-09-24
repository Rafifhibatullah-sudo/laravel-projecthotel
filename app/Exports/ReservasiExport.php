<?php

namespace App\Exports;

use App\Models\Reservasi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class ReservasiExport implements FromCollection, WithHeadings, WithMapping
{
    protected $bulan;
    protected $tahun;
    protected $totalKeseluruhan = 0;

    // Menerima parameter filter bulan & tahun dari Controller
    public function __construct($bulan = null, $tahun = null)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Reservasi::with('kamar')
            ->whereIn('status', ['confirmed', 'Confirmed', 'in', 'In', 'Check In', 'out', 'Out', 'Check Out', 'paid', 'Paid']);

        // Jika ada filter bulan
        if ($this->bulan) {
            $query->whereMonth('check_in', $this->bulan);
        }

        // Jika ada filter tahun
        if ($this->tahun) {
            $query->whereYear('check_in', $this->tahun);
        }

        $data = $query->latest()->get();

        // Hitung total nilai transaksi untuk baris paling bawah
        $this->totalKeseluruhan = $data->sum(function ($item) {
            return $item->total_harga ?? $item->total_bayar ?? $item->total ?? 0;
        });

        // Sisipkan 2 baris tambahan di akhir collection: 1 baris kosong & 1 baris Total Pendapatan
        $data->push((object)[
            'is_summary' => true,
            'label' => '',
            'value' => ''
        ]);

        $data->push((object)[
            'is_summary' => true,
            'label' => 'TOTAL PENDAPATAN',
            'value' => $this->totalKeseluruhan
        ]);

        return $data;
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
        // Jika data yang di-loop adalah baris ringkasan (Total Pendapatan)
        if (isset($reservasi->is_summary) && $reservasi->is_summary) {
            if ($reservasi->label === 'TOTAL PENDAPATAN') {
                return [
                    'TOTAL PENDAPATAN', // Kolom Kode Booking
                    '',                 // Nama Pemesan
                    '',                 // Email
                    '',                 // No HP
                    '',                 // Nama Kamar
                    '',                 // Check In
                    '',                 // Check Out
                    '',                 // Jumlah Kamar
                    'Rp ' . number_format($reservasi->value, 0, ',', '.'), // Total Harga
                    '',                 // Status
                    '',                 // Tanggal Booking
                ];
            }

            // Baris Pembatas Kosong
            return ['', '', '', '', '', '', '', '', '', '', ''];
        }

        // Ambil nominal harga
        $harga = $reservasi->total_harga ?? $reservasi->total_bayar ?? $reservasi->total ?? 0;

        // Format tanggal Check-In & Check-Out agar aman dari error Object Carbon
        $checkIn = $reservasi->check_in ? Carbon::parse($reservasi->check_in)->format('d-m-Y') : '-';
        $checkOut = $reservasi->check_out ? Carbon::parse($reservasi->check_out)->format('d-m-Y') : '-';

        return [
            $reservasi->kode_booking ?? '-',
            $reservasi->nama_pemesan ?? '-',
            $reservasi->email ?? '-',
            $reservasi->no_hp ?? '-',
            $reservasi->kamar ? $reservasi->kamar->nama_kamar : 'Kamar Dihapus',
            $checkIn,
            $checkOut,
            ($reservasi->jumlah_kamar ?? 1) . ' Unit',
            'Rp ' . number_format($harga, 0, ',', '.'),
            strtoupper($reservasi->status ?? '-'),
            $reservasi->created_at ? Carbon::parse($reservasi->created_at)->format('d-m-Y H:i') : '-',
        ];
    }
}
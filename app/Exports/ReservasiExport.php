<?php

namespace App\Exports;

use App\Models\Reservasi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class ReservasiExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
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

        // Filter Berdasarkan Bulan jika ada
        if ($this->bulan) {
            $query->whereMonth('check_in', $this->bulan);
        }

        // Filter Berdasarkan Tahun jika ada
        if ($this->tahun) {
            $query->whereYear('check_in', $this->tahun);
        }

        $data = $query->latest()->get();

        // Hitung total nilai transaksi
        $this->totalKeseluruhan = $data->sum(function ($item) {
            return $item->total_harga ?? $item->total_bayar ?? $item->total ?? 0;
        });

        // Tentukan teks keterangan periode laporan
        $namaBulan = $this->bulan ? Carbon::create()->month((int)$this->bulan)->translatedFormat('F') : 'Semua Bulan';
        $teksTahun = $this->tahun ? $this->tahun : 'Semua Tahun';
        $periodeLaporan = "PERIODE: " . strtoupper($namaBulan) . " " . $teksTahun;

        $collection = collect();

        // Header Keterangan Laporan di Baris Excel Paling Atas
        $collection->push((object)[
            'is_header' => true,
            'col1' => 'LAPORAN TRANSAKSI RESERVASI HOTEL',
            'col2' => $periodeLaporan
        ]);

        $collection->push((object)['is_blank' => true]);

        // Masukkan data transaksi
        foreach ($data as $item) {
            $collection->push($item);
        }

        // Summary Total Pendapatan
        $collection->push((object)['is_blank' => true]);
        $collection->push((object)[
            'is_summary' => true,
            'label' => 'TOTAL PENDAPATAN',
            'value' => $this->totalKeseluruhan
        ]);

        return $collection;
    }

    // Menentukan Header Kolom Excel
    public function headings(): array
    {
        return [
            'Kode Booking',
            'Nama Pemesan',
            'Email',
            'No HP / WA',
            'Nama Kamar',
            'Check In (WIB)',
            'Check Out Rencana (WIB)',
            'Check Out Real (WIB)',
            'Jumlah Kamar',
            'Total Harga',
            'Status',
            'Tanggal Booking',
        ];
    }

    // Memetakan Isi Data per Baris Excel
    public function map($reservasi): array
    {
        // Header Judul Laporan
        if (isset($reservasi->is_header) && $reservasi->is_header) {
            return [
                $reservasi->col1,
                $reservasi->col2,
                '', '', '', '', '', '', '', '', '', ''
            ];
        }

        // Baris Kosong Pemisah
        if (isset($reservasi->is_blank) && $reservasi->is_blank) {
            return ['', '', '', '', '', '', '', '', '', '', '', ''];
        }

        // Baris Total Pendapatan
        if (isset($reservasi->is_summary) && $reservasi->is_summary) {
            return [
                'TOTAL PENDAPATAN',
                '', '', '', '', '', '', '', '',
                'Rp ' . number_format($reservasi->value, 0, ',', '.'),
                '', ''
            ];
        }

        // Nominal Harga
        $harga = $reservasi->total_harga ?? $reservasi->total_bayar ?? $reservasi->total ?? 0;

        // Format Waktu Indonesia (WIB)
        $checkIn = $reservasi->check_in ? Carbon::parse($reservasi->check_in)->setTimezone('Asia/Jakarta')->format('d-m-Y H:i') . ' WIB' : '-';
        $checkOut = $reservasi->check_out ? Carbon::parse($reservasi->check_out)->setTimezone('Asia/Jakarta')->format('d-m-Y H:i') . ' WIB' : '-';
        
        // Checkout Real
        $checkoutReal = isset($reservasi->checkout_real) && $reservasi->checkout_real 
            ? Carbon::parse($reservasi->checkout_real)->setTimezone('Asia/Jakarta')->format('d-m-Y H:i') . ' WIB' 
            : '-';

        return [
            $reservasi->kode_booking ?? '-',
            $reservasi->nama_pemesan ?? '-',
            $reservasi->email ?? '-',
            $reservasi->no_hp ?? '-',
            $reservasi->kamar ? $reservasi->kamar->nama_kamar : 'Kamar Dihapus',
            $checkIn,
            $checkOut,
            $checkoutReal,
            ($reservasi->jumlah_kamar ?? 1) . ' Unit',
            'Rp ' . number_format($harga, 0, ',', '.'),
            strtoupper($reservasi->status ?? '-'),
            $reservasi->created_at ? Carbon::parse($reservasi->created_at)->setTimezone('Asia/Jakarta')->format('d-m-Y H:i') : '-',
        ];
    }

    // Styling Tampilan Baris Excel
    public function styles(Worksheet $sheet)
    {
        return [
            // Tebalkan Header Utama
            1 => ['font' => ['bold' => true, 'size' => 12]],
            // Tebalkan Header Tabel
            3 => ['font' => ['bold' => true]],
        ];
    }
}
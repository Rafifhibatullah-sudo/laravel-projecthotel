<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pembayaran - {{ $reservasi->kode_booking }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        .invoice-card {
            max-width: 800px;
            margin: 30px auto;
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        /* KHUSUS UNTUK PROSES CETAK / SIMPAN PDF */
        @media print {
            /* Sembunyikan elemen navigasi & tombol */
            .no-print {
                display: none !important;
            }

            /* Reset margin & background halaman agar pas di kertas A4 */
            body {
                background-color: #ffffff !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .container {
                width: 100% !important;
                max-width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .invoice-card {
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
                padding: 10px !important;
            }

            /* Hilangkan warna background tabel striping agar hemat tinta & rapi */
            .table-striped>tbody>tr:nth-of-type(odd)>* {
                background-color: transparent !important;
            }

            /* Pastikan teks tetap hitam jelas saat dicetak */
            * {
                color: #000000 !important;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="invoice-card">
        
        <!-- Header Print & Kembali (Sembunyi saat dicetak) -->
        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom no-print">
            <a href="{{ route('reservasi.index') }}" class="btn btn-secondary"> Kembali ke Daftar Reservasi</a>
            
            <!-- Fungsi JavaScript Otomatis Membuka Dialog Print Browser -->
            <button onclick="cetakPDF()" class="btn btn-primary">
                🖨️ Cetak / Download PDF
            </button>
        </div>

        <!-- Header Nota -->
        <div class="row mb-4">
            <div class="col-6">
                <h3 class="fw-bold text-dark mb-1">GRAND HORIZON HOTEL</h3>
                <p class="text-muted small mb-0">Jl. Utama Hotel No. 26, Sumsel Indonesia</p>
                <p class="text-muted small">Email: info@grandhorizonRafifHibatullah.com | Telp: +6282186993746</p>
            </div>
            <div class="col-6 text-end">
                <h4 class="fw-bold text-uppercase">Bukti Pembayaran</h4>
                <div class="badge bg-outline-dark border border-dark text-dark font-monospace fs-6 px-3 py-2 mt-1">
                    {{ $reservasi->kode_booking }}
                </div>
                <p class="text-muted small mt-2">Tgl Transaksi: {{ $reservasi->created_at->format('d M Y H:i') }}</p>
            </div>
        </div>

        <hr>

        <!-- Informasi Pemesan & Kamar -->
        <div class="row my-4">
            <div class="col-6">
                <h6 class="fw-bold text-dark">Informasi Tamu:</h6>
                <table class="table table-borderless table-sm small">
                    <tr>
                        <td width="35%" class="text-muted">Nama Pemesan</td>
                        <td>: <strong>{{ $reservasi->nama_pemesan }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">No. Handphone</td>
                        <td>: {{ $reservasi->no_hp }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Email</td>
                        <td>: {{ $reservasi->email }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-6">
                <h6 class="fw-bold text-dark">Rincian Penginapan:</h6>
                <table class="table table-borderless table-sm small">
                    <tr>
                        <td width="35%" class="text-muted">Check-In</td>
                        <td>: <strong>{{ \Carbon\Carbon::parse($reservasi->check_in)->format('d M Y') }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Check-Out</td>
                        <td>: <strong>{{ \Carbon\Carbon::parse($reservasi->check_out)->format('d M Y') }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status Reservasi</td>
                        <td>: <span class="fw-bold text-uppercase">{{ $reservasi->status }}</span></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Tabel Rincian Biaya -->
        <div class="table-responsive my-4">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Kamar</th>
                        <th>Jumlah Unit</th>
                        <th>Durasi</th>
                        <th class="text-end">Total Biaya</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <strong>{{ $reservasi->kamar->nama_kamar ?? 'Kamar Dihapus' }}</strong>
                            <br><small class="text-muted">{{ $reservasi->kamar->tipe_kamar ?? '' }}</small>
                        </td>
                        <td>{{ $reservasi->jumlah_kamar }} Unit</td>
                        <td>
                            @php
                                $cIn  = \Carbon\Carbon::parse($reservasi->check_in);
                                $cOut = \Carbon\Carbon::parse($reservasi->check_out);
                                $malam = $cIn->diffInDays($cOut);
                            @endphp
                            {{ $malam }} Malam
                        </td>
                        <td class="text-end fw-bold">Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end fw-bold fs-5">TOTAL BAYAR</td>
                        <td class="text-end fw-bold fs-5">Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Footer Nota -->
        <div class="row pt-4 mt-5 border-top">
            <div class="col-6">
                <p class="small text-muted mb-0">Catatan:<br>
                <small class="text-muted">Harap tunjukkan bukti pembayaran ini pada saat proses Check-In di resepsionis hotel.</small></p>
            </div>
            <div class="col-6 text-end">
                <p class="small text-muted mb-4">Hormat Kami,</p>
                <br>
                <strong>Manajemen Grand Horizon Hotel</strong>
            </div>
        </div>
    </div>
</div>

<script>
    function cetakPDF() {
        window.print();
    }
</script>

</body>
</html>
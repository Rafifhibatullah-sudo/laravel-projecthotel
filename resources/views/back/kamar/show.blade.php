@extends('back.layout.template')

@section('title', 'Detail Kamar - Admin')

@section('content')
<!-- Ganti <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mb-5"> menjadi ini: -->
<main class="w-100 px-md-4 mb-5">
    <!-- Header Section -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom" data-aos="fade-down">
        <h1 class="h2">Detail Kamar: {{ $kamar->nama_kamar }}</h1>
        
    </div>

    <!-- Main Content Table -->
    <div class="mt-3">
        <table class="table table-striped table-bordered align-middle" data-aos="fade-up" data-aos-delay="100">
            <tr data-aos="fade-right" data-aos-delay="150">
                <th width="250px">Nama Kamar</th>
                <td>: {{ $kamar->nama_kamar }}</td>
            </tr>
            <tr data-aos="fade-right" data-aos-delay="200">
                <th>Tipe Kamar</th>
                <td>: <span class=" text-dark">{{ $kamar->tipe_kamar }} Room</span></td>
            </tr>
            <tr data-aos="fade-right" data-aos-delay="250">
                <th>Harga / Malam</th>
                <td>: <strong class="text-success">Rp {{ number_format($kamar->harga, 0, ',', '.') }}</strong></td>
            </tr>
            <tr data-aos="fade-right" data-aos-delay="200">
                <th>Stok Kamar</th>
                <td>: {{ $kamar->jumlah_kamar }} Unit</td>
            </tr>
            <tr data-aos="fade-right" data-aos-delay="250">
                <th>Deskripsi</th>
                <td>: {!! $kamar->deskripsi  !!}</td>
            </tr>
            <tr data-aos="fade-right" data-aos-delay="200">
                <th>Foto Kamar</th>
                <td>
                    @if($kamar->foto)
                        <a href="{{ asset('storage/' . $kamar->foto) }}" target="_blank" rel="noopener noreferrer">
                            <img src="{{ asset('storage/' . $kamar->foto) }}" alt="{{ $kamar->nama_kamar }}" width="200px" class="img-thumbnail rounded shadow-sm">
                        </a>
                    @else
                        : <span class="text-muted">Tidak ada foto tersedia</span>
                    @endif
                </td>
            </tr>
            <tr data-aos="fade-right" data-aos-delay="250">
                <th>Fasilitas Kamar</th>
                <td>
                    : 
                    @forelse($kamar->fasilitas as $f)
                        <span class="badge bg-secondary me-1">✓ {{ $f->nama_fasilitas }}</span>
                    @empty
                        <span class="text-muted">Tidak ada fasilitas khusus</span>
                    @endforelse
                </td>
            </tr>
            <tr data-aos="fade-right" data-aos-delay="200">
                <th>Tanggal Diperbarui</th>
                <td>: {{ $kamar->updated_at->format('d M Y, H:i') }} WIB</td>
            </tr>
        </table>

        <!-- Button Section -->
        <div class="float-end" data-aos="fade-up" data-aos-delay="550">
            <a href="{{ route('kamar.index') }}" class="btn btn-secondary">Kembali ke Daftar Kamar</a>
        </div>
    </div>
</main>
@endsection
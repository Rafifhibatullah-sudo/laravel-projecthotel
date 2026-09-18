@extends('back.layout.template')

@section('title', 'Detail Kamar - Admin')

@section('content')
<main class="w-100 px-md-4 mb-5">
    <!-- Header Section -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom" data-aos="fade-down">
        <h1 class="h2">Detail Kamar: {{ $kamar->nama_kamar }}</h1>
        <div>
            <a href="{{ route('kamar.edit', $kamar->id) }}" class="btn btn-warning rounded-3 me-2">
                <i class="fas fa-edit me-1"></i> Edit Kamar
            </a>
            <a href="{{ route('kamar.index') }}" class="btn btn-outline-secondary rounded-3">Kembali</a>
        </div>
    </div>

    <!-- Main Content Table -->
    <div class="mt-3">
        <table class="table table-striped table-bordered align-middle" data-aos="fade-up" data-aos-delay="100">
            <tr data-aos="fade-right" data-aos-delay="150">
                <th width="250px">Nama Kamar</th>
                <td>: <strong>{{ $kamar->nama_kamar }}</strong></td>
            </tr>
            <tr data-aos="fade-right" data-aos-delay="200">
                <th>Tipe Kamar</th>
                <td>: <span class="badge bg-info text-dark">{{ $kamar->tipe_kamar }} Room</span></td>
            </tr>
            <tr data-aos="fade-right" data-aos-delay="250">
                <th>Harga / Malam</th>
                <td>: <strong class="text-success fs-6">Rp {{ number_format($kamar->harga, 0, ',', '.') }}</strong></td>
            </tr>
            <tr data-aos="fade-right" data-aos-delay="200">
                <th>Stok Kamar</th>
                <td>: {{ $kamar->jumlah_kamar }} Unit</td>
            </tr>
            <tr data-aos="fade-right" data-aos-delay="250">
                <th>Deskripsi</th>
                <td>: {!! $kamar->deskripsi ?? '<em>Tidak ada deskripsi.</em>' !!}</td>
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
                        <span class="badge bg-primary px-2 py-1 mb-1 me-1">
                            <i class="fas fa-check-circle me-1"></i>{{ $f->nama_fasilitas }}
                        </span>
                    @empty
                        <span class="text-muted">Tidak ada fasilitas yang ditambahkan</span>
                    @endforelse
                </td>
            </tr>
            <tr data-aos="fade-right" data-aos-delay="200">
                <th>Tanggal Diperbarui</th>
                <td>: {{ $kamar->updated_at ? $kamar->updated_at->format('d M Y, H:i') : '-' }} WIB</td>
            </tr>
        </table>

        <!-- Button Section -->
        <div class="float-end" data-aos="fade-up" data-aos-delay="300">
            <a href="{{ route('kamar.index') }}" class="btn btn-secondary rounded-3">Kembali ke Daftar Kamar</a>
        </div>
    </div>
</main>
@endsection
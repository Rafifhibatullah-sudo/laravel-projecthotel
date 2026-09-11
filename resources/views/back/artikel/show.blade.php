@extends('back.layout.template')

@section('title', 'Detail Artikel - Halaman Admin')

@section('content')
<main class="w-100 px-md-4 mb-5">
    <!-- Header Section -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom" data-aos="fade-down">
        <h1 class="h2">Detail Artikel: {{ $artikel->judul }}</h1>
    </div>

    <!-- Main Content Table -->
    <div class="mt-3">
        <table class="table table-striped table-bordered align-middle" data-aos="fade-up" data-aos-delay="100">
            <tr data-aos="fade-right" data-aos-delay="150">
                <th width="250px">Judul Artikel</th>
                <td>: {{ $artikel->judul }}</td>
            </tr>
            <tr data-aos="fade-right" data-aos-delay="200">
                <th>Kategori</th>
                <td>: <span class="text-dark">{{ $artikel->kategori }}</span></td>
            </tr>
            <tr data-aos="fade-right" data-aos-delay="200">
                <th>Status Publikasi</th>
                <td>: 
                    @if($artikel->status == 'publish')
                        <span class="badge bg-success">Published</span>
                    @else
                        <span class="badge bg-danger">Private / Draft</span>
                    @endif
                </td>
            </tr>
            <tr data-aos="fade-right" data-aos-delay="200">
                <th>Gambar Header</th>
                <td>
                    @if($artikel->gambar)
                        <a href="{{ asset('storage/' . $artikel->gambar) }}" target="_blank" rel="noopener noreferrer">
                            <img src="{{ asset('storage/' . $artikel->gambar) }}" alt="{{ $artikel->judul }}" width="200px" class="img-thumbnail rounded shadow-sm">
                        </a>
                    @else
                        : <span class="text-muted">Tidak ada gambar header</span>
                    @endif
                </td>
            </tr>
            <tr data-aos="fade-right" data-aos-delay="200">
                <th>Isi Artikel</th>
                <td>: {!! $artikel->isi !!}</td>
            </tr>
            <tr data-aos="fade-right" data-aos-delay="200">
                <th>Dilihat</th>
                <td>: {{ $artikel->views }} x</td>
            </tr>
            <tr data-aos="fade-right" data-aos-delay="200">
                <th>Tanggal Dibuat</th>
                <td>: {{ $artikel->created_at ? $artikel->created_at->format('d M Y, H:i') . ' WIB' : '-' }}</td>
            </tr>
            <tr data-aos="fade-right" data-aos-delay="200">
                <th>Tanggal Diperbarui</th>
                <td>: {{ $artikel->updated_at ? $artikel->updated_at->format('d M Y, H:i') . ' WIB' : '-' }}</td>
            </tr>
        </table>

        <!-- Button Section -->
        <div class="float-end" data-aos="fade-up" data-aos-delay="200">
            <a href="{{ route('artikel.index') }}" class="btn btn-secondary">Kembali ke Daftar Artikel</a>
        </div>
    </div>
</main>
@endsection
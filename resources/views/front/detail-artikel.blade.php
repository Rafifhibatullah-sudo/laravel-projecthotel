@extends('front.layout.template')

@section('content')
<div class="container py-5 overflow-hidden">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      
      <!-- Tombol Kembali (Slide dari Kiri) -->
      <div data-aos="fade-right" data-aos-duration="600">
        <a href="{{ route('front.index') }}" class="btn btn-outline-secondary rounded-pill mb-4 btn-sm shadow-sm">
          <i class="fas fa-arrow-left me-1"></i> Kembali ke Beranda
        </a>
      </div>

      <!-- Header Artikel (Meluncur dari Atas) -->
      <div data-aos="fade-down" data-aos-duration="700">
        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-2 fw-semibold">
          {{ $artikel->kategori ?? 'Informasi' }}
        </span>
        <h1 class="fw-bold text-dark mb-3">{{ $artikel->judul }}</h1>
        
        <div class="d-flex align-items-center text-muted small mb-4 pb-3 border-bottom">
          <span class="me-4"><i class="far fa-calendar-alt me-1 text-primary"></i> {{ $artikel->created_at->format('d M Y') }}</span>
          <span><i class="far fa-eye me-1 text-primary"></i> {{ $artikel->views }} Views</span>
        </div>
      </div>

      <!-- Gambar Utama (Efek Zoom-In) -->
      @if($artikel->gambar)
        <div class="mb-4 overflow-hidden rounded-4 shadow-sm" data-aos="zoom-in" data-aos-duration="800" data-aos-delay="200">
          <img src="{{ asset('storage/' . $artikel->gambar) }}" class="img-fluid w-100" style="max-height: 400px; object-fit: cover;" alt="{{ $artikel->judul }}">
        </div>
      @endif

      <!-- Konten Artikel (Meluncur dari Bawah) -->
      <div class="lh-lg text-secondary fs-6 p-2" data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
        {!! nl2br(e($artikel->isi ?? $artikel->konten)) !!}
      </div>

    </div>
  </div>
</div>
@endsection
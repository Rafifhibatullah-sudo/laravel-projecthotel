@extends('front.layout.template')

@section('content')
<!-- Hero Section / Banner Utama -->
<section class="hero-section text-center position-relative">
  <div class="container position-relative z-1">
    
    <!-- Animasi Badge & Teks -->
    <span class="badge bg-primary bg-opacity-25 text-white px-3 py-2 rounded-pill mb-3 fw-medium" data-aos="fade-down" data-aos-delay="100">
        <i class="fw-bold fas fa-hotel me-2"></i> Welcome to Grand Horizon Hotel
    </span>
    
    <h1 class="display-4 fw-bold text-white mb-3" data-aos="fade-up" data-aos-delay="200">
      Nikmati Pengalaman Menginap Mewah & Nyaman
    </h1>
    
    <p class="lead text-white-50 mx-auto mb-5" style="max-width: 700px;" data-aos="fade-up" data-aos-delay="300">
      Temukan kenyamanan terbaik dengan fasilitas bintang lima, kamar elegan, dan pelayanan ramah sepanjang hari.
    </p>

    <!-- Floating Booking Box dengan Animasi Zoom In -->
    <div class="card border-0 shadow-lg rounded-4 p-3 bg-white text-dark text-start mx-auto" 
         style="max-width: 950px; margin-bottom: -150px;" 
         data-aos="zoom-in" 
         data-aos-delay="400">
      <div class="card-body">
        <h5 class="fw-bold mb-3 text-primary"><i class="fas fa-search me-2"></i>Cari & Booking Kamar</h5>
        <form action="{{ route('front.kamar') }}" method="GET" class="row g-3 align-items-end">
          
          <div class="col-md-4">
            <label class="form-label small fw-bold text-muted mb-1"><i class="fas fa-calendar-check me-1 text-primary"></i> Tanggal Check In</label>
            <input type="date" name="check_in" class="form-control form-control-lg fs-6" required>
          </div>

          <div class="col-md-4">
            <label class="form-label small fw-bold text-muted mb-1"><i class="fas fa-calendar-times me-1 text-danger"></i> Tanggal Check Out</label>
            <input type="date" name="check_out" class="form-control form-control-lg fs-6" required>
          </div>

          <div class="col-md-4">
            <button type="submit" class="btn btn-primary btn-lg w-100 rounded-3 fw-bold fs-6 shadow-sm">
              <i class="fas fa-door-open me-1"></i> Cek Ketersediaan
            </button>
          </div>

        </form>
      </div>
    </div>
  </div>
</section>

<!-- Space Pendorong untuk Floating Box -->
<div style="height: 100px;"></div>

<!-- Section Kamar Populer -->
<section class="py-5">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-end mb-4" data-aos="fade-right">
      <div>
        <span class="text-primary fw-bold text-uppercase small tracking-wider">Akomodasi</span>
        <h2 class="fw-bold text-dark mb-0">Pilihan Kamar Favorit</h2>
      </div>
      <a href="{{ route('front.kamar') }}" class="btn btn-outline-primary rounded-pill px-4 fw-semibold">
        Lihat Semua Kamar <i class="fas fa-arrow-right ms-1"></i>
      </a>
    </div>

    <div class="row g-4">
      @forelse($kamars as $index => $kamar)
      <!-- Animasi Bertahap (Staggered Animation) menggunakan Loop Index -->
      <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ 100 * ($index + 1) }}">
        <div class="card card-kamar h-100 shadow-sm overflow-hidden">
          <div class="position-relative">
            <a href="{{ route('front.detailKamar', $kamar->id) }}">
              @if($kamar->foto)
                <img src="{{ asset('storage/' . $kamar->foto) }}" class="card-img-top" alt="{{ $kamar->nama_kamar }}" style="height: 230px; object-fit: cover;">
              @else
                <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 230px;">
                  <i class="fas fa-image fa-2x"></i>
                </div>
              @endif
            </a>
            <span class="badge bg-primary position-absolute top-0 end-0 m-3 px-3 py-2 rounded-pill">
              {{ $kamar->tipe_kamar }}
            </span>
          </div>
          <div class="card-body p-4 d-flex flex-column justify-content-between">
            <div>
              <h5 class="card-title fw-bold text-dark mb-2">
                <a href="{{ route('front.detailKamar', $kamar->id) }}" class="text-decoration-none text-dark">
                  {{ $kamar->nama_kamar }}
                </a>
              </h5>
              <p class="text-muted small mb-3">
                {{ Str::limit($kamar->deskripsi ?? 'Kamar nyaman dengan pemandangan indah dan fasilitas lengkap.', 80) }}
              </p>
            </div>
            <div>
              <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-2">
                <div>
                  <span class="text-muted small d-block">Harga per malam</span>
                  <span class="fw-bold text-success fs-5">Rp {{ number_format($kamar->harga, 0, ',', '.') }}</span>
                </div>
                <a href="{{ route('front.detailKamar', $kamar->id) }}" class="btn btn-primary rounded-3 px-3">
                  Pesan Sekarang <i class="fas fa-chevron-right ms-1 fa-xs"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      @empty
      <div class="col-12 text-center py-5">
        <p class="text-muted">Belum ada kamar yang tersedia saat ini.</p>
      </div>
      @endforelse
    </div>
  </div>
</section>

<!-- Section Fasilitas Hotel -->
<section class="py-5 bg-white border-top border-bottom">
  <div class="container">
    <div class="text-center mb-5" style="max-width: 600px; margin: auto;" data-aos="fade-up">
      <span class="text-primary fw-bold text-uppercase small tracking-wider">Layanan Unggulan</span>
      <h2 class="fw-bold text-dark">Fasilitas Mewah Hotel Kami</h2>
      <p class="text-muted">Kami menyediakan berbagai keunggulan fasilitas untuk menjaga kenyamanan Anda selama penginapan.</p>
    </div>

    <div class="row g-4">
      @forelse($fasilitas as $index => $item)
      <div class="col-md-3 col-6" data-aos="zoom-in" data-aos-delay="{{ 100 * ($index + 1) }}">
        <div class="p-4 rounded-4 bg-light text-center h-100 border border-light-subtle card-hover">
          <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
            <i class="fas fa-concierge-bell fa-lg"></i>
          </div>
          <h6 class="fw-bold text-dark mb-1">{{ $item->nama_fasilitas }}</h6>
          <small class="text-muted d-block">{{ Str::limit($item->deskripsi ?? 'Fasilitas terbaik hotel', 50) }}</small>
        </div>
      </div>
      @empty
      <div class="col-12 text-center text-muted">Belum ada data fasilitas.</div>
      @endforelse
    </div>
  </div>
</section>

<!-- Section Artikel & Promo Terbaru -->
<section class="py-5">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-end mb-4" data-aos="fade-right">
      <div>
        <span class="text-primary fw-bold text-uppercase small tracking-wider">Kabar Terbaru</span>
        <h2 class="fw-bold text-dark mb-0">Artikel & Promo Hotel</h2>
      </div>
      <a href="{{ route('front.artikel') }}" class="btn btn-outline-primary rounded-pill px-4 fw-semibold">
        Lihat Semua Artikel <i class="fas fa-arrow-right ms-1"></i>
      </a>
    </div>

    <div class="row g-4">
      @forelse($artikels as $index => $artikel)
      <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ 100 * ($index + 1) }}">
        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden card-hover">
          <a href="{{ route('front.detailArtikel', $artikel->slug) }}">
            @if($artikel->gambar)
              <img src="{{ asset('storage/' . $artikel->gambar) }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $artikel->judul }}">
            @else
              <div class="bg-light text-muted d-flex align-items-center justify-content-center" style="height: 200px;">
                <i class="fas fa-newspaper fa-2x"></i>
              </div>
            @endif
          </a>
          
          <div class="card-body p-4">
            <span class="badge bg-info-subtle text-info border border-info-subtle mb-2 px-3 py-1 rounded-pill">{{ $artikel->kategori }}</span>
            <h5 class="fw-bold text-dark mb-2">
              <a href="{{ route('front.detailArtikel', $artikel->slug) }}" class="text-decoration-none text-dark">
                {{ Str::limit($artikel->judul, 50) }}
              </a>
            </h5>
            <p class="text-muted small mb-3">{{ Str::limit(strip_tags($artikel->isi), 90) }}</p>
            <div class="d-flex justify-content-between align-items-center text-muted small border-top pt-3">
              <span><i class="far fa-calendar-alt me-1"></i>{{ $artikel->created_at->format('d M Y') }}</span>
              <span><i class="far fa-eye me-1"></i>{{ $artikel->views }} views</span>
            </div>
          </div>
        </div>
      </div>
      @empty
      <div class="col-12 text-center text-muted py-4">Belum ada artikel yang diterbitkan.</div>
      @endforelse
    </div>
  </div>
</section>
@endsection
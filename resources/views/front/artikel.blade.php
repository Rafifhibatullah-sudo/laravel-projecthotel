@extends('front.layout.template')

@section('content')
<!-- Header Banner Artikel -->
<div class="bg-primary bg-opacity-10 py-5 mb-5 text-center overflow-hidden">
  <div class="container" data-aos="fade-down">
    <h2 class="fw-bold text-dark mb-2">Artikel & Promo Hotel</h2>
    <p class="text-muted mb-0">Dapatkan informasi berita, tips wisata, dan penawaran promo menarik dari kami.</p>
  </div>
</div>

<!-- Grid Artikel -->
<div class="container mb-5">
  <div class="row g-4">
    @forelse($artikels as $artikel)
    <!-- Animasi Masing-Masing Kartu (Delay Bertahap berdasarkan Loop) -->
    <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ 100 * $loop->iteration }}">
      <div class="card card-hover h-100 border-0 shadow-sm rounded-4 overflow-hidden">
        
        <a href="{{ route('front.detailArtikel', $artikel->slug) }}">
          @if($artikel->gambar)
            <img src="{{ asset('storage/' . $artikel->gambar) }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $artikel->judul }}">
          @else
            <div class="bg-light text-muted d-flex align-items-center justify-content-center" style="height: 200px;">
              <i class="fas fa-newspaper fa-2x"></i>
            </div>
          @endif
        </a>

        <div class="card-body p-4 d-flex flex-column justify-content-between">
          <div>
            <span class="badge bg-info-subtle text-info border border-info-subtle mb-2 px-3 py-1 rounded-pill">
              {{ $artikel->kategori }}
            </span>
            
            <h5 class="fw-bold text-dark mb-2">
              <a href="{{ route('front.detailArtikel', $artikel->slug) }}" class="text-decoration-none text-dark">
                {{ Str::limit($artikel->judul, 55) }}
              </a>
            </h5>
            
            <p class="text-muted small mb-3">
              {!! Str::limit(strip_tags($artikel->isi), 90) !!}
            </p>
          </div>

          <div class="d-flex justify-content-between align-items-center text-muted small border-top pt-3 mt-2">
            <span><i class="far fa-calendar-alt me-1 text-primary"></i>{{ $artikel->created_at->format('d M Y') }}</span>
            <span><i class="far fa-eye me-1 text-primary"></i>{{ $artikel->views }} views</span>
          </div>
        </div>

      </div>
    </div>
    @empty
    <div class="col-12 text-center text-muted py-5" data-aos="zoom-in">
      <i class="fas fa-newspaper fa-3x mb-3 text-secondary"></i>
      <p>Belum ada artikel yang diterbitkan saat ini.</p>
    </div>
    @endforelse
  </div>


</div>
@endsection
@extends('front.layout.template')

@section('content')
<!-- Header Banner Fasilitas (Meluncur dari Atas) -->
<div class="bg-primary bg-opacity-10 py-5 mb-5 text-center overflow-hidden">
  <div class="container" data-aos="fade-down" data-aos-duration="700">
    <h2 class="fw-bold text-dark mb-2">Fasilitas Hotel</h2>
    <p class="text-muted mb-0">Nikmati ragam fasilitas unggulan yang kami sediakan khusus untuk kenyamanan Anda.</p>
  </div>
</div>

<!-- Grid Fasilitas -->
<div class="container mb-5 overflow-hidden">
  <div class="row g-4">
    @forelse($fasilitas as $item)
    <!-- Kartu Fasilitas dengan Animasi Bertahap (Staggered Delay) -->
    <div class="col-md-4" data-aos="fade-up" data-aos-duration="800" data-aos-delay="{{ 100 * $loop->iteration }}">
      <div class="card card-hover border-0 shadow-sm rounded-4 h-100 p-4 text-center">
        
        <!-- Lingkaran Ikon (Efek Zoom-In) -->
        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3 mx-auto icon-wrapper" style="width: 70px; height: 70px;" data-aos="zoom-in" data-aos-delay="{{ 150 * $loop->iteration }}">
          <i class="fas fa-concierge-bell fa-2x"></i>
        </div>

        <h5 class="fw-bold text-dark mb-2">{{ $item->nama_fasilitas }}</h5>
        <p class="text-muted small mb-0">{!! $item->deskripsi ?? 'Fasilitas berkualitas tinggi untuk mendukung pengalaman menginap terbaik Anda.' !!}</p>
      
      </div>
    </div>
    @empty
    <div class="col-12 text-center text-muted py-5" data-aos="zoom-in">
      <i class="fas fa-concierge-bell fa-3x mb-3 text-secondary"></i>
      <p>Belum ada data fasilitas.</p>
    </div>
    @endforelse
  </div>
</div>
@endsection
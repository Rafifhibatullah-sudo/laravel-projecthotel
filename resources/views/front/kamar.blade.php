@extends('front.layout.template')

@section('content')
<div class="container py-5 overflow-hidden">
  <!-- Header Title -->
  <div class="text-center mb-5" data-aos="fade-down" data-aos-duration="700">
    <h2 class="fw-bold text-dark"> <i class="fas fa-bed me-2"></i>Daftar Kamar & Akomodasi</h2>
    <p class="text-muted">Pilih tipe kamar yang paling sesuai dengan kebutuhan menginap Anda.</p>
  </div>

  <div class="row g-4">
    @forelse($kamars as $kamar)
    @php
        // Menentukan stok yang tersedia
        $stokTersedia = isset($kamar->sisa_stok) ? $kamar->sisa_stok : $kamar->stok;
    @endphp
    <!-- Kartu Kamar dengan Animasi Bertahap -->
    <div class="col-md-4" data-aos="fade-up" data-aos-duration="800" data-aos-delay="{{ 100 * $loop->iteration }}">
      <div class="card card-kamar card-hover h-100 shadow-sm border-0 rounded-4 overflow-hidden">
        
        <!-- Foto Kamar: Tanpa Link <a> (Tidak Bisa Diklik) -->
          <div class="position-relative overflow-hidden">
            @if($kamar->foto)
              <img src="{{ asset('storage/' . $kamar->foto) }}" class="card-img-top" alt="{{ $kamar->nama_kamar }}" style="height: 230px; object-fit: cover; cursor: default;">
            @else
              <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 230px; cursor: default;">
                <i class="fas fa-image fa-2x"></i>
              </div>
            @endif
          
          <!-- Badge Tipe Kamar -->
          <span class="badge bg-primary position-absolute top-0 end-0 m-3 px-3 py-2 rounded-pill shadow-sm" data-aos="zoom-in" data-aos-delay="{{ 150 * $loop->iteration }}">
            {{ $kamar->tipe_kamar }}
          </span>

          <!-- Badge Stok Habis Jika Stok = 0 -->
          @if($stokTersedia <= 0)
            <span class="badge bg-danger position-absolute top-0 start-0 m-3 px-3 py-2 rounded-pill shadow-sm">
              <i class="fas fa-ban me-1"></i> Stok Habis
            </span>
          @endif
        </div>

        <div class="card-body p-4 d-flex flex-column justify-content-between">
          <div>
            <h5 class="card-title fw-bold text-dark mb-2">
              <a href="{{ route('front.detailKamar', $kamar->slug) }}" class="text-decoration-none text-dark">
                {{ $kamar->nama_kamar }}
              </a>
            </h5>
            <p class="text-muted small mb-2">
              {!! Str::limit($kamar->deskripsi ?? 'Kamar nyaman dengan pemandangan indah dan fasilitas lengkap.', 80) !!}
            </p>
            
            <!-- Sisa Stok Info -->
            <div class="mb-3">
              @if($stokTersedia > 0)
                <small class="text-success fw-semibold"><i class="fas fa-check-circle me-1"></i> Tersedia: {{ $stokTersedia }} Unit</small>
              @else
                <small class="text-danger fw-semibold"><i class="fas fa-times-circle me-1"></i> Tidak Tersedia</small>
              @endif
            </div>
          </div>

          <div>
            <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-2">
              <div>
                <span class="text-muted small d-block">Harga per malam</span>
                <span class="fw-bold text-success fs-5">Rp {{ number_format($kamar->harga, 0, ',', '.') }}</span>
              </div>
              

              @if($stokTersedia > 0)
                <a href="{{ route('front.detailKamar', $kamar->slug) }}" class="btn btn-primary rounded-3 px-3 shadow-sm">
                  Pesan Sekarang <i class="fas fa-chevron-right ms-1 fa-xs"></i>
                </a>
              @else
                <button class="btn btn-secondary rounded-3 px-3 shadow-sm" disabled>
                  Stok Habis
                </button>
              @endif
            </div>
          </div>
        </div>

      </div>
    </div>
    @empty
    <div class="col-12 text-center py-5" data-aos="zoom-in">
      <i class="fas fa-bed fa-3x mb-3 text-secondary"></i>
      <p class="text-muted">Belum ada data kamar yang tersedia saat ini.</p>
    </div>
    @endforelse
  </div>
</div>
@endsection
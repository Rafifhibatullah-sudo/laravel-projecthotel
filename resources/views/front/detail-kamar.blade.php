@extends('front.layout.template')

@section('content')
<div class="container py-5">
  <!-- Breadcrumb Navigation -->
  <nav aria-label="breadcrumb" class="mb-4" data-aos="fade-right" data-aos-duration="600">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('front.index') }}" class="text-decoration-none">Beranda</a></li>
      <li class="breadcrumb-item"><a href="{{ route('front.kamar') }}" class="text-decoration-none">Kamar</a></li>
      <li class="breadcrumb-item active" aria-current="page">{{ $kamar->nama_kamar }}</li>
    </ol>
  </nav>

  <div class="row g-4">
    <!-- Kolom Kiri: Detail Foto & Fasilitas Kamar -->
    <div class="col-lg-7" data-aos="fade-up" data-aos-duration="800">
      <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        @if($kamar->foto)
          <img src="{{ asset('storage/' . $kamar->foto) }}" class="img-fluid w-100" style="max-height: 400px; object-fit: cover;" alt="{{ $kamar->nama_kamar }}">
        @else
          <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 350px;">
            <i class="fas fa-image fa-3x"></i>
          </div>
        @endif
        <div class="card-body p-4">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="badge bg-primary px-3 py-2 rounded-pill fs-6" data-aos="zoom-in"
              data-aos-delay="300">{{ $kamar->tipe_kamar }}</span>
            <div class="text-end">
              <span class="text-muted small d-block">Harga per malam</span>
              <h3 class="fw-bold text-success mb-0">Rp {{ number_format($kamar->harga, 0, ',', '.') }}</h3>
            </div>
          </div>

          <h3 class="fw-bold text-dark mb-3">{{ $kamar->nama_kamar }}</h3>
          <p class="text-muted leading-relaxed mb-4">
            {!! $kamar->deskripsi ?? 'Kamar ini dirancang khusus untuk kenyamanan maksimal Anda dengan interior modern, tempat tidur premium, dan pemandangan luar biasa.' !!}
          </p>

          <h5 class="fw-bold text-dark mb-3"><i class="fas fa-concierge-bell text-primary me-2"></i>Fasilitas Kamar Ini</h5>
          <div class="row g-3">
            @forelse($kamar->fasilitas as $fas)
            <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
              <div class="d-flex align-items-center p-3 rounded-3 bg-light">
                <i class="fas fa-check-circle text-success me-3 fs-5"></i>
                <span class="fw-medium text-dark">{{ $fas->nama_fasilitas }}</span>
              </div>
            </div>
            @empty
            <div class="col-12 text-muted small">Fasilitas kamar ini disertakan standar hotel.</div>
            @endforelse
          </div>
        </div>
      </div>
    </div>

    <!-- Kolom Kanan: Form Booking Online -->
    <div class="col-lg-5" data-aos="fade-up" data-aos-duration="800"  data-aos-delay="200">
      <div class="card border-0 shadow-lg rounded-4 p-4 sticky-top" style="top: 100px;">
        <h4 class="fw-bold text-dark mb-1"><i class="fas fa-calendar-alt text-primary me-2"></i>Form Booking Online</h4>
        <p class="text-muted small mb-4">Isi formulir di bawah ini untuk memesan kamar secara instan.</p>

        <form action="{{ route('front.storeBooking') }}" method="POST" target="_blank">
          @csrf
          <input type="hidden" name="kamar_id" value="{{ $kamar->id }}">

          <div class="mb-3">
            <label class="form-label fw-semibold small">Nama Lengkap Pemesan</label>
            <input type="text" name="nama_pemesan" class="form-control rounded-3" placeholder="Masukkan nama sesuai KTP" required>
          </div>

          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label fw-semibold small">Email</label>
              <input type="email" name="email" class="form-control rounded-3" placeholder="email@domain.com" required>
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold small">No. HP / WA</label>
              <input type="number" name="no_hp" class="form-control rounded-3" placeholder="08123456789" required>
            </div>
          </div>

          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label fw-semibold small">Tanggal Check In</label>
              <input type="date" name="check_in" class="form-control rounded-3" required>
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold small">Tanggal Check Out</label>
              <input type="date" name="check_out" class="form-control rounded-3" required>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold small">Jumlah Kamar</label>
            <input type="number" name="jumlah_kamar" class="form-control rounded-3" value="1" min="1" max="{{ $kamar->stok }}" required>
            <small class="text-muted fs-7">Tersedia: {{ $kamar->stok }} Unit</small>
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold small">Catatan Tambahan (Opsional)</label>
            <textarea name="catatan" class="form-control rounded-3" rows="2" placeholder="Contoh: Minta kamar bebas asap rokok..."></textarea>
          </div>

          <button type="submit" class="btn btn-primary btn-lg w-100 rounded-3 fw-bold shadow-sm">
            <i class="fab fa-whatsapp me-2"></i> Booking & Konfirmasi WA
          </button>
          

        </form>
      </div>
    </div>
  </div>
</div>
@endsection
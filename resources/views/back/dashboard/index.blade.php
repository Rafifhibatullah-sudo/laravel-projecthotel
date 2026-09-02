@extends('back.layout.template')

@section('content')


<!-- Banner Welcome Modern -->
<div class="card border-0 shadow-sm rounded-4 bg-white mb-4 overflow-hidden" data-aos="fade-down">
  <div class="card-body p-4 p-lg-5 d-flex align-items-center justify-content-between">
    <div>
      <span class="badge bg-primary-subtle text-primary fw-semibold px-3 py-2 rounded-pill mb-2">
  <i class="fas fa-hotel me-2"></i>Grand Horizon Hotel
</span>
      <h1 class="h2 mb-1"> <i class="fas fa-user"></i> Selamat Datang! {{ Auth::user()->name }}</h1>
      <p class="text-muted mb-0">Berikut adalah ringkasan statistik dan aktivitas sistem hotel hari ini.</p>
    </div>
  </div>
</div>

<!-- Grid Kartu Statistik -->
<div class="row g-3 mb-4">

  <!-- Total Kamar -->
  <div class="col-12 col-sm-6 col-xl-3" data-aos="zoom-in" data-aos-delay="100">
    <div class="card card-stat card-gradient-blue text-white p-3 shadow-sm">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <span class="d-block small text-white-50 fw-semibold text-uppercase tracking-wider">Total Kamar</span>
          <h2 class="fw-bold mb-0 display-6 mt-1">{{ $totalKamar }}</h2>
        </div>
        <div class="icon-shape">
          <span data-feather="grid" class="text-white" style="width:26px; height:26px;"></span>
        </div>
      </div>
      <div class="border-top border-white border-opacity-10 pt-2">
        <small class="text-white-50"><i class="feather-14 me-1" data-feather="check-circle"></i> Varian tipe kamar terdaftar</small>
      </div>
    </div>
  </div>

  <!-- Total Fasilitas -->
  <div class="col-12 col-sm-6 col-xl-3" data-aos="zoom-in" data-aos-delay="200">
    <div class="card card-stat card-gradient-green text-white p-3 shadow-sm">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <span class="d-block small text-white-50 fw-semibold text-uppercase tracking-wider"> Total Fasilitas</span>
          <h2 class="fw-bold mb-0 display-6 mt-1">{{ $totalFasilitas }}</h2>
        </div>
        <div class="icon-shape">
          <span data-feather="list" class="text-white" style="width:26px; height:26px;"></span>
        </div>
      </div>
      <div class="border-top border-white border-opacity-10 pt-2">
        <small class="text-white-50"><i class="feather-14 me-1" data-feather="check-circle"></i> Fasilitas aktif & tersedia</small>
      </div>
    </div>
  </div>

  <!-- Total Artikel -->
  <div class="col-12 col-sm-6 col-xl-3" data-aos="zoom-in" data-aos-delay="300">
    <div class="card card-stat card-gradient-orange text-white p-3 shadow-sm">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <span class="d-block small text-white-50 fw-semibold text-uppercase tracking-wider">Total Artikel</span>
          <h2 class="fw-bold mb-0 display-6 mt-1">{{ $totalArtikel }}</h2>
        </div>
        <div class="icon-shape">
          <span data-feather="file-text" class="text-white" style="width:26px; height:26px;"></span>
        </div>
      </div>
      <div class="border-top border-white border-opacity-10 pt-2">
        <small class="text-white-50"><i class="feather-14 me-1" data-feather="clock"></i> Berita & pengumuman hotel</small>
      </div>
    </div>
  </div>

  <!-- Total Reservasi -->
  <div class="col-12 col-sm-6 col-xl-3" data-aos="zoom-in" data-aos-delay="400">
    <div class="card card-stat card-gradient-cyan text-white p-3 shadow-sm">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <span class="d-block small text-white-50 fw-semibold text-uppercase tracking-wider">Total Reservasi</span>
          <h2 class="fw-bold mb-0 display-6 mt-1">{{ $totalReservasi }}</h2>
        </div>
        <div class="icon-shape">
          <span data-feather="calendar" class="text-white" style="width:26px; height:26px;"></span>
        </div>
      </div>
      <div class="border-top border-white border-opacity-10 pt-2">
        <small class="text-white-50"><i class="feather-14 " data-feather="trending-up"></i> Pesanan masuk via WhatsApp</small>
      </div>
    </div>
  </div>

</div>

<!-- Seksi Ringkasan Cepat Data Kamar Terbaru -->
<div class="card border-0 shadow-sm rounded-4" data-aos="fade-up" data-aos-delay="500">
  <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
    <div>
      <h5 class="fw-bold text-dark mb-0">Daftar Kamar Terbaru</h5>
      <small class="text-muted">Kamar yang terakhir diinputkan ke sistem</small>
    </div>
    <a href="{{ route('kamar.index') }}" class="btn btn-sm btn-outline-primary rounded-3">Lihat Semua Data</a>
  </div>
  <div class="card-body px-4 pb-4">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Foto</th>
            <th>Nama Kamar</th>
            <th>Tipe Kamar</th>
            <th>Harga atau Malam</th>
            <th>Stok</th>
          </tr>
        </thead>
        <tbody>
          @forelse($kamarTerbaru as $kamar)
          <tr>
            <td width="10%">
              @if($kamar->foto)
                <img src="{{ asset('storage/' . $kamar->foto) }}" class="rounded-3" width="55" height="40" style="object-fit: cover;">
              @else
                <span class="badge bg-secondary">No Photo</span>
              @endif
            </td>
            <td class="fw-semibold">{{ $kamar->nama_kamar }}</td>
            <td><span class="badge bg-info-subtle text-info border border-info-subtle px-3 py-1 rounded-pill">{{ $kamar->tipe_kamar }}</span></td>
            <td class="fw-bold text-success">Rp {{ number_format($kamar->harga, 0, ',', '.') }}</td>
            <td>{{ $kamar->jumlah_kamar }} Unit</td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="text-center text-muted py-3">Belum ada data kamar.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
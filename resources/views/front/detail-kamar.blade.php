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
            <span class="badge bg-primary px-3 py-2 rounded-pill fs-6" data-aos="zoom-in" data-aos-delay="300">
              {{ $kamar->tipe_kamar }}
            </span>
            <div class="text-end">
              <span class="text-muted small d-block">Harga per malam</span>
              <h3 class="fw-bold text-success mb-0">Rp {{ number_format($kamar->harga, 0, ',', '.') }}</h3>
            </div>
          </div>

          <!-- Informasi Rekening Pembayaran (Tampilan Rapi & Modern) -->
          <div class="card bg-light border-0 rounded-3 p-3 my-3">
            <div class="d-flex align-items-center mb-2">
              <i class="fas fa-university text-primary fs-5 me-2"></i>
              <h6 class="fw-bold text-dark mb-0">Informasi Pembayaran / Transfer Bank</h6>
            </div>
            <p class="text-muted small mb-2">
              Pembayaran bebas menggunakan transfer bank atau e-wallet ke rekening resmi berikut:
            </p>
            <div class="row g-2">
              <div class="col-sm-6">
                <div class="p-2 bg-white rounded border text-center">
                  <span class="text-muted d-block extra-small">No. Rekening Bank</span>
                  <strong class="text-dark fs-6">092399123</strong>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="p-2 bg-white rounded border text-center">
                  <span class="text-muted d-block extra-small">No. Konfirmasi / E-Wallet</span>
                  <strong class="text-dark fs-6">08272713</strong>
                </div>
              </div>
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
    <div class="col-lg-5" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
      <div class="card border-0 shadow-lg rounded-4 p-4 sticky-top" style="top: 100px;">
        <h4 class="fw-bold text-dark mb-1"><i class="fas fa-calendar-alt text-primary me-2"></i>Form Booking Online</h4>
        <p class="text-muted small mb-3">Pilih tanggal check-in dan check-out untuk melihat ketersediaan stok.</p>

        <!-- Kotak Info Status Stok -->
        <div id="box-stok-info" class="alert alert-info d-flex align-items-center mb-3 rounded-3 py-2 px-3">
          <i class="fas fa-info-circle me-2"></i>
          <span id="stok-text">Pilih tanggal untuk mengecek stok kamar.</span>
        </div>

        @if(session('error'))
          <div class="alert alert-danger mb-3 rounded-3 small">
            {{ session('error') }}
          </div>
        @endif

        <form action="{{ route('front.storeBooking') }}" method="POST" enctype="multipart/form-data" target="_blank">
          @csrf
          <input type="hidden" name="kamar_id" id="kamar_id" value="{{ $kamar->id }}">
          <input type="hidden" id="harga_kamar" value="{{ $kamar->harga }}">

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
              <input type="date" name="check_in" id="check_in" class="form-control rounded-3" value="{{ request('check_in') }}" required min="{{ date('Y-m-d') }}">
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold small">Tanggal Check Out</label>
              <input type="date" name="check_out" id="check_out" class="form-control rounded-3" value="{{ request('check_out') }}" required min="{{ date('Y-m-d', strtotime('+1 day')) }}">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold small">Jumlah Kamar</label>
            <input type="number" name="jumlah_kamar" id="jumlah_kamar" class="form-control rounded-3" value="1" min="1" required>
          </div>
           
         <div class="mb-3"> 
        <label class="form-label fw-semibold small" for="metode_pembayaran">Metode Pembayaran</label> 
        <select class="form-select" name="metode_pembayaran" id="metode_pembayaran">
          <option value="" hidden selected>--- Pilih ---</option> 
          <option value="e-wallet">E-Wallet</option> 
          <option value="bank">Bank</option> 
        </select> 
      </div>

          <!-- Input Upload Bukti Pembayaran -->
          <div class="mb-3">
            <label class="form-label fw-semibold small text-dark">
              Upload Bukti Transfer / Pembayaran <span class="text-danger">*</span>
            </label>
            <input type="file" name="bukti_bayar" id="bukti_bayar" class="form-control rounded-3 @error('bukti_bayar') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg" required>
            <small class="text-muted d-block mt-1">Format: JPG, JPEG, PNG (Maksimal 2MB)</small>
            @error('bukti_bayar')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold small">Catatan Tambahan (Opsional)</label>
            <textarea name="catatan" class="form-control rounded-3" rows="2" placeholder="Contoh: Minta kamar bebas asap rokok..."></textarea>
          </div>

          <!-- Rincian Biaya Otomatis Real-time -->
          <div class="card bg-light border-0 rounded-3 p-3 mb-4">
            <h6 class="fw-bold text-dark mb-2"><i class="fas fa-file-invoice-dollar text-primary me-2"></i>Rincian Biaya</h6>
            <div class="d-flex justify-content-between small text-muted mb-1">
              <span>Durasi Menginap:</span>
              <span id="text-durasi" class="fw-bold text-dark">0 Malam</span>
            </div>
            <div class="d-flex justify-content-between small text-muted mb-1">
              <span>Harga x Kamar:</span>
              <span id="text-harga-kamar" class="fw-bold text-dark">Rp 0</span>
            </div>
            <hr class="my-2">
            <div class="d-flex justify-content-between fw-bold text-dark">
              <span>Total Biaya:</span>
              <span id="text-total-harga" class="text-success fs-6">Rp 0</span>
            </div>
          </div>

          <button type="submit" id="btn-submit-booking" class="btn btn-primary btn-lg w-100 rounded-3 fw-bold shadow-sm">
            <i class="fab fa-whatsapp me-2"></i> Booking & Konfirmasi WA
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- JavaScript Cek Stok Otomatis & Hitung Biaya Realtime -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  const checkInInput = document.getElementById('check_in');
  const checkOutInput = document.getElementById('check_out');
  const kamarId = document.getElementById('kamar_id').value;
  const hargaKamar = parseFloat(document.getElementById('harga_kamar').value) || 0;
  const stokText = document.getElementById('stok-text');
  const boxStokInfo = document.getElementById('box-stok-info');
  const btnSubmit = document.getElementById('btn-submit-booking');
  const jumlahKamarInput = document.getElementById('jumlah_kamar');

  const textDurasi = document.getElementById('text-durasi');
  const textHargaKamar = document.getElementById('text-harga-kamar');
  const textTotalHarga = document.getElementById('text-total-harga');

  function hitungBiayaRealtime() {
    const checkIn = new Date(checkInInput.value);
    const checkOut = new Date(checkOutInput.value);
    const jumlahKamar = parseInt(jumlahKamarInput.value) || 1;

    if (checkInInput.value && checkOutInput.value && checkOut > checkIn) {
      const diffTime = Math.abs(checkOut - checkIn);
      const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

      const totalPerMalam = hargaKamar * jumlahKamar;
      const grandTotal = totalPerMalam * diffDays;

      textDurasi.innerText = `${diffDays} Malam`;
      textHargaKamar.innerText = `Rp ${totalPerMalam.toLocaleString('id-ID')}`;
      textTotalHarga.innerText = `Rp ${grandTotal.toLocaleString('id-ID')}`;
    } else {
      textDurasi.innerText = '0 Malam';
      textHargaKamar.innerText = 'Rp 0';
      textTotalHarga.innerText = 'Rp 0';
    }
  }

  function cekStokRealtime() {
    const checkIn = checkInInput.value;
    const checkOut = checkOutInput.value;

    hitungBiayaRealtime();

    if (checkIn && checkOut) {
      if (checkOut <= checkIn) {
        boxStokInfo.className = 'alert alert-warning d-flex align-items-center mb-3 rounded-3 py-2 px-3';
        stokText.innerText = 'Tanggal Check-Out harus setelah Check-In!';
        btnSubmit.disabled = true;
        return;
      }

      boxStokInfo.className = 'alert alert-info d-flex align-items-center mb-3 rounded-3 py-2 px-3';
      stokText.innerText = 'Mengecek ketersediaan stok...';

      fetch(`/api/cek-stok-kamar?kamar_id=${kamarId}&check_in=${checkIn}&check_out=${checkOut}`)
        .then(res => {
          if (!res.ok) throw new Error('Network error');
          return res.json();
        })
        .then(data => {
          if (data.sisa_stok > 0) {
            boxStokInfo.className = 'alert alert-success d-flex align-items-center mb-3 rounded-3 py-2 px-3';
            stokText.innerHTML = `Stok Tersedia: <strong>${data.sisa_stok} Kamar</strong>`;
            jumlahKamarInput.max = data.sisa_stok;
            btnSubmit.disabled = false;
            btnSubmit.className = 'btn btn-primary btn-lg w-100 rounded-3 fw-bold shadow-sm';
            btnSubmit.innerHTML = '<i class="fab fa-whatsapp me-2"></i> Booking & Konfirmasi WA';
          } else {
            boxStokInfo.className = 'alert alert-danger d-flex align-items-center mb-3 rounded-3 py-2 px-3';
            stokText.innerHTML = '🚫 <strong>Stok Habis / Sold Out</strong> untuk tanggal ini!';
            jumlahKamarInput.max = 0;
            btnSubmit.disabled = true;
            btnSubmit.className = 'btn btn-secondary btn-lg w-100 rounded-3 fw-bold shadow-sm';
            btnSubmit.innerHTML = '<i class="fas fa-ban me-2"></i> Kamar Tidak Tersedia';
          }
        })
        .catch(err => {
          boxStokInfo.className = 'alert alert-danger d-flex align-items-center mb-3 rounded-3 py-2 px-3';
          stokText.innerText = 'Gagal mengecek stok. Silakan coba lagi.';
          btnSubmit.disabled = true;
        });
    }
  }

  if (checkInInput.value && checkOutInput.value) {
    cekStokRealtime();
  }

  checkInInput.addEventListener('change', cekStokRealtime);
  checkOutInput.addEventListener('change', cekStokRealtime);
  jumlahKamarInput.addEventListener('input', cekStokRealtime);
});
</script>
@endsection
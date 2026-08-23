<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Grand Horizon Hotel - Pengalaman Menginap Mewah & Nyaman</title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- FontAwesome CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <!-- AOS CSS (Animasi On Scroll) -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background-color: #f8fafc;
      color: #334155;
      overflow-x: hidden;
    }
    .navbar {
      backdrop-filter: blur(10px);
      background: rgba(255, 255, 255, 0.9);
      transition: all 0.3s ease;
    }
    .hero-section {
      background: linear-gradient(rgba(15, 23, 42, 0.65), rgba(15, 23, 42, 0.65)), url('https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;
      padding: 120px 0 100px;
      color: #ffffff;
    }
    .card-kamar, .card-hover {
      border: none;
      border-radius: 1.25rem;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card-kamar:hover, .card-hover:hover {
      transform: translateY(-8px);
      box-shadow: 0 20px 30px rgba(0,0,0,0.12) !important;
    }
    .footer {
      background: #0f172a;
      color: #94a3b8;
    }
  </style>
</head>
<body>

  <!-- Navbar Navigation -->
  <nav class="navbar navbar-expand-lg sticky-top navbar-light border-bottom shadow-sm">
    <div class="container">
      <a class="navbar-brand fw-bold text-primary fs-4" href="{{ route('front.index') }}">
        <i class="fas fa-hotel me-2"></i>Grand Horizon
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto align-items-center gap-2">
          <li class="nav-item"><a class="nav-link fw-semibold px-3" href="{{ route('front.index') }}">Beranda</a></li>
          <li class="nav-item"><a class="nav-link fw-semibold px-3" href="{{ route('front.kamar') }}">Kamar</a></li>
          <li class="nav-item"><a class="nav-link fw-semibold px-3" href="{{ route('front.fasilitas') }}">Fasilitas</a></li>
          <li class="nav-item"><a class="nav-link fw-semibold px-3" href="{{ route('front.artikel') }}">Artikel & Promo</a></li>

          <!-- LOGIKA PENGECEKAN USER LOGIN -->
          @guest
            <li class="nav-item ms-lg-2">
              <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-pill px-4 fw-semibold">
                <i class="fas fa-sign-in-alt me-1"></i> Login
              </a>
            </li>
          @else
            <li class="nav-item dropdown ms-lg-2">
              <a class="nav-link dropdown-toggle fw-bold text-dark bg-light px-3 py-2 rounded-pill border" href="#" role="button" data-bs-toggle="dropdown">
                <i class="fas fa-user-circle text-primary me-1 fs-5 align-middle"></i> {{ auth()->user()->name }}
              </a>
              <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 mt-2">
                <li class="px-3 py-1 small text-muted">Role: <strong>{{ ucfirst(auth()->user()->role) }}</strong></li>
                <li><hr class="dropdown-divider"></li>

                @if(auth()->user()->role == 'admin')
                  <li>
                    <a class="dropdown-item fw-semibold text-primary" href="{{ route('dashboard') }}">
                      <i class="fas fa-tachometer-alt me-2"></i> Ke Dashboard Admin
                    </a>
                  </li>
                  <li><hr class="dropdown-divider"></li>
                @endif

                <li>
                  <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger fw-semibold" onclick="return confirm('Yakin ingin keluar?');">
                      <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </button>
                  </form>
                </li>
              </ul>
            </li>
          @endguest

        </ul>
      </div>
    </div>
  </nav>

  <!-- Dynamic Content -->
  <main>
    @yield('content')
  </main>

  <!-- Section Peta Lokasi (Sematkan Google Maps) -->
  <section class="py-5 bg-white border-top">
    <div class="container" data-aos="fade-up">
      <div class="row align-items-center g-4">
        
        <!-- Kolom Informasi Singkat -->
        <div class="col-lg-5">
          <span class="text-primary fw-bold text-uppercase small tracking-wider">Lokasi Kami</span>
          <h3 class="fw-bold text-dark mt-1 mb-3">Grand Horizon Hotel</h3>
          <p class="text-muted lh-base mb-3">
            Menyediakan pengalaman menginap berkelas bintang lima puluh dengan fasilitas mewah, kamar nyaman, dan pelayanan terbaik untuk liburan serta perjalanan bisnis Anda.
          </p>
          <div class="d-flex align-items-center text-muted small">
            <i class="fas fa-map-marker-alt text-danger fs-5 me-2"></i>
            <span>Jl. Horizon Grand No. 88, Sumatera Selatan, Indonesia</span>
          </div>
        </div>

        <!-- Kolom Embedded Maps (Ukurannya pas & rapi) -->
        <div class="col-lg-7">
          <div class="rounded-4 overflow-hidden shadow-sm border border-2 border-light" style="height: 230px;">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3984.514158659106!2d104.74457787383801!3d-2.9547284397235676!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e3b742b746b209b%3A0x29e8c12afb478592!2sAston%20Palembang%20Hotel%20%26%20Conference%20Center!5e0!3m2!1sid!2sid!4v1787304084104!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- Footer Section -->
  <footer class="footer pt-5 pb-4">
    <div class="container" data-aos="fade-up">
      <div class="row g-4">
        <div class="col-md-5">
          <h4 class="fw-bold text-white mb-3"><i class="fas fa-hotel me-2 text-primary"></i>Grand Horizon Hotel</h4>
          <p class="small">Menyediakan pengalaman menginap berkelas bintang lima puluh dengan fasilitas mewah, kamar nyaman, dan pelayanan terbaik untuk liburan serta perjalanan bisnis Anda.</p>
        </div>
        <div class="col-md-3">
          <h6 class="fw-bold text-white mb-3">Navigasi Cepat</h6>
          <ul class="list-unstyled small">
            <li class="mb-2"><a href="{{ route('front.kamar') }}" class="text-decoration-none text-white-50">Tipe Kamar</a></li>
            <li class="mb-2"><a href="{{ route('front.fasilitas') }}" class="text-decoration-none text-white-50">Fasilitas Hotel</a></li>
            <li class="mb-2"><a href="{{ route('front.artikel') }}" class="text-decoration-none text-white-50">Promo Terbaru</a></li>
          </ul>
        </div>
        <div class="col-md-4">
          <h6 class="fw-bold text-white mb-3">Kontak & Lokasi</h6>
          <p class="small mb-2"><i class="fas fa-map-marker-alt me-2 text-primary"></i>Jl. Horizon Grand No. 88, Indonesia, Sumatera Selatan</p>
          <p class="small mb-2"><i class="fas fa-building me-2 text-primary"></i>500 cabang of business</p>
          <p class="small mb-2"><i class="fas fa-phone me-2 text-primary"></i>+6282186993746</p>
          <p class="small mb-1"><i class="fas fa-user-tie me-2 text-primary"></i> CEO - <a href="https://www.instagram.com/rafifhibatullah._" target="_blank" class="text-decoration-none text-white-50">rafifhibatullah._</a></p>
          <p class="small mb-0"><i class="fas fa-user-friends me-2 text-primary"></i> Co founder - ranggagunawan</p>
        </div>
      </div>
      <hr class="my-4 border-secondary opacity-25">
      <p class="text-center small mb-0">&copy; {{ date('Y') }} Grand Horizon Hotel. All Rights Reserved.</p>
    </div>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <!-- AOS JS -->
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init({
      duration: 800,  // Kecepatan animasi (0.8 detik)
      once: true,     // Animasi hanya jalan 1x saat di-scroll
      easing: 'ease-in-out',
    });
  </script>
</body>
</html>
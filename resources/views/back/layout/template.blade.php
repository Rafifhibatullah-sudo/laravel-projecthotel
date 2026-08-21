<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin Hotel</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="{{ asset('back/css/dashboard.css') }}" rel="stylesheet">
    <!-- Select2 CSS (Bootstrap 5 Theme) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- FontAwesome CDN (Icon Pack) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

  </head>
  <body>
    
    <!-- Navbar Atas -->
    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
      <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6" href="{{ route('dashboard') }}">
        Dashboard Data Admin Hotel
      </a>
      <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <form action="{{ route('logout') }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-link nav-link px-3 text-white text-decoration-none d-inline-block border-0 bg-transparent">
          Sign out
        </button>
      </form>
    </header>

    <div class="container-fluid">
      <div class="row">
        
        <!-- Sidebar Kiri -->
        @include('back.layout.sidebar')

        <!-- Area Konten Utama -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
          @yield('content')
        </main>

      </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    
<!-- jQuery & Select2 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
@stack('js')



<script>
  $(document).ready(function() {
    // Inisialisasi DataTables otomatis untuk tabel dengan class .datatable
    $('.datatable').DataTable({
      "language": {
        "search": "Cari Fasilitas:",
        "lengthMenu": "Tampilkan _MENU_ data",
        "zeroRecords": "Data tidak ditemukan",
        "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
        "infoEmpty": "Tidak ada data tersedia",
        "paginate": {
          "next": "next",
          "previous": "previous"
        }
      }
    });
  });
</script>

<script>
  $(document).ready(function() {
    $('.select2-multiple').select2({
      theme: 'bootstrap-5',
      placeholder: "-- Pilih Banyak Fasilitas --",
      allowClear: true,
      width: '100%'
    });
  });
</script>
    <script>
      feather.replace();
    </script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({
    duration: 700, // Durasi animasi dalam milidetik (0.7 detik)
    once: true,    // Animasi hanya berjalan 1 kali saat di-scroll
    easing: 'ease-in-out',
  });
</script>
  </body>
</html>
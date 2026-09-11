<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
  <div class="position-sticky pt-3 sidebar-sticky">
    <ul class="nav flex-column">
      
      <!-- 1. Dashboard (Akses: Admin & Resepsionis) -->, 
      <li class="nav-item">
        <a class="nav-link {{ Request::is('dashboard*') ? 'active' : '' }}" href="/dashboard">
          <span data-feather="home" class="align-text-bottom"></span>
          Dashboard
        </a>
      </li>

      <!-- 2. Data Reservasi (Akses: Admin & Resepsionis) -->
      <li class="nav-item">
        <a class="nav-link {{ Request::routeIs('reservasi.*') ? 'active' : '' }}" href="{{ route('reservasi.index') }}">
          <span data-feather="calendar" class="align-text-bottom"></span>
          Data Reservasi
        </a>
      </li>

      <!-- MENU KHUSUS ADMIN SAJA -->
      @if(auth()->user() && auth()->user()->role == 'admin')
        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted text-uppercase fs-7">
          <span>Kelola Master Data</span>
        </h6>

        <!-- 3. Data Kamar -->
        <li class="nav-item">
          <a class="nav-link {{ Request::routeIs('kamar.*') ? 'active' : '' }}" href="{{ route('kamar.index') }}">
            <span data-feather="box" class="align-text-bottom"></span>
            Data Kamar
          </a>
        </li>
        
        

        <!-- 4. Data Fasilitas -->
        <li class="nav-item">
          <a class="nav-link {{ Request::routeIs('fasilitas.*') ? 'active' : '' }}" href="{{ route('fasilitas.index') }}">
            <span data-feather="grid" class="align-text-bottom"></span>
            Fasilitas Hotel
          </a>
        </li>

        <!-- 5. Data Artikel -->
        <li class="nav-item">
          <a class="nav-link {{ Request::routeIs('artikel.*') ? 'active' : '' }}" href="{{ route('artikel.index') }}">
            <span data-feather="file-text" class="align-text-bottom"></span>
            Artikel Hotel
          </a>
        </li>

        <!-- 6. Manajemen Users -->
        <li class="nav-item">
          <a class="nav-link {{ Request::routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
            <span data-feather="users" class="align-text-bottom"></span>
            Manajemen User
          </a>
        </li>
         {{-- ringkasan : Request::is() mengecek berdasarkan path URL di address bar (contoh: /dashboard), sedangkan Request::routeIs() mengecek berdasarkan nama route yang didefinisikan di routes/web.php (contoh: kamar.index). routeIs() lebih fleksibel jika URL path sewaktu-waktu diubah." --}}
         

      @endif 
    </ul>
  </div>
</nav>
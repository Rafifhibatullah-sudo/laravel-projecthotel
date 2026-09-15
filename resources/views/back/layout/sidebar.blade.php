<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
  <div class="position-sticky pt-3 sidebar-sticky">
    <ul class="nav flex-column">
      
      <!-- 1. Dashboard (Akses: SEMUA ROLE - Admin, Frontline, Media) -->
      <li class="nav-item">
        <a class="nav-link {{ Request::is('dashboard*') ? 'active' : '' }}" href="/dashboard">
          <span data-feather="home" class="align-text-bottom"></span>
          Dashboard
        </a>
      </li>

      <!-- 2. Data Reservasi (Akses: Admin & Frontline) -->
      @if(auth()->user() && in_array(auth()->user()->role, ['admin', 'frontline']))
        <li class="nav-item">
          <a class="nav-link {{ Request::routeIs('reservasi.*') ? 'active' : '' }}" href="{{ route('reservasi.index') }}">
            <span data-feather="calendar" class="align-text-bottom"></span>
            Data Reservasi
          </a>
        </li>
      @endif

      <!-- 3. Kelola Operasional (Akses: Admin & Frontline) -->
      @if(auth()->user() && in_array(auth()->user()->role, ['admin', 'frontline']))
        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted text-uppercase fs-7">
          <span>Kelola Operasional</span>
        </h6>

        <!-- Data Kamar (Akses: Admin & Frontline) -->
        <li class="nav-item">
          <a class="nav-link {{ Request::routeIs('kamar.*') ? 'active' : '' }}" href="{{ route('kamar.index') }}">
            <span data-feather="box" class="align-text-bottom"></span>
            Data Kamar
          </a>
        </li>

        <!-- Fasilitas Hotel (Akses: Admin & Frontline) -->
        <li class="nav-item">
          <a class="nav-link {{ Request::routeIs('fasilitas.*') ? 'active' : '' }}" href="{{ route('fasilitas.index') }}">
            <span data-feather="grid" class="align-text-bottom"></span>
            Fasilitas Hotel
          </a>
        </li>
      @endif

      <!-- 4. Kelola Media & Konten (Akses: Admin & Media) -->
      @if(auth()->user() && in_array(auth()->user()->role, ['admin', 'media']))
        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted text-uppercase fs-7">
          <span>Kelola Media</span>
        </h6>

        <!-- Artikel Hotel (Akses: Admin & Media) -->
        <li class="nav-item">
          <a class="nav-link {{ Request::routeIs('artikel.*') ? 'active' : '' }}" href="{{ route('artikel.index') }}">
            <span data-feather="file-text" class="align-text-bottom"></span>
            Artikel Hotel
          </a>
        </li>
      @endif

      <!-- 5. Pengaturan Sistem (Akses: Khusus Admin) -->
      @if(auth()->user() && auth()->user()->role == 'admin')
        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted text-uppercase fs-7">
          <span>Pengaturan Sistem</span>
        </h6>

        <li class="nav-item">
          <a class="nav-link {{ Request::routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
            <span data-feather="users" class="align-text-bottom"></span>
            Manajemen User
          </a>
        </li>
      @endif

    </ul>
  </div>
</nav>
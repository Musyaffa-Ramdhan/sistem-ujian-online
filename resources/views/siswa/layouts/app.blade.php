<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- @yield('title'): Placeholder untuk judul halaman dari child view --}}
    <title>@yield('title', 'Siswa') | Ujian Online SMP</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style>
      /* CSS Global untuk Layout Siswa */
      body {
        background-color: #f0f0f0;
        font-family: "Segoe UI", sans-serif;
      }

      /* Sidebar Style */
      .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: 240px;
        background-color: #212529;
        color: white;
        padding-top: 20px;
        transition: 0.3s;
      }

      .sidebar h4 { text-align: center; color: #f8f9fa; font-weight: 700; margin-bottom: 30px; }

      .sidebar a {
        display: block;
        padding: 12px 20px;
        color: #ddd;
        text-decoration: none;
        transition: 0.3s;
        border-radius: 8px;
        margin: 5px 10px;
      }

      .sidebar a:hover,
      .sidebar a.active {
        background-color: royalblue;
        color: white;
      }

      .main-content {
        margin-left: 260px; /* Jarak dari sidebar */
        padding: 20px;
      }

      /* Responsive behavior */
      @media (max-width: 992px) {
        .sidebar { display: none; }
        .main-content { margin-left: 0; }
      }
    </style>
    
    {{-- @stack('styles'): Menampung CSS tambahan dari child view yang menggunakan @push('styles') --}}
    @stack('styles')
</head>

<body>
    <!-- NAVBAR (untuk mobile devices) -->
    <nav class="navbar navbar-dark bg-dark d-lg-none">
      <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar">
          <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#">@yield('title')</a>
      </div>
    </nav>

    <!-- SIDEBAR (Desktop) -->
    <div class="sidebar d-none d-lg-block">
      <h4>Menu Siswa</h4>
      {{-- Logika class 'active' berdasarkan section 'active_menu' di child view --}}
      <a href="{{ route('siswa.dashboard') }}" class="@yield('active_menu') == 'dashboard' ? 'active' : ''">🏠 Dashboard</a>
      <a href="{{ route('siswa.ujian') }}" class="@yield('active_menu') == 'ujian' ? 'active' : ''">📝 Ujian</a>
      <a href="{{ route('siswa.nilai') }}" class="@yield('active_menu') == 'nilai' ? 'active' : ''">📊 Nilai Ujian</a>
      <a href="{{ route('siswa.pengumuman') }}" class="@yield('active_menu') == 'pengumuman' ? 'active' : ''">📢 Pengumuman</a>
      <a href="{{ route('siswa.profile') }}" class="@yield('active_menu') == 'profile' ? 'active' : ''">👤 Profil</a>
      
      <form action="{{ route('siswa.logout') }}" method="POST" class="mt-3">
        @csrf
        <button type="submit" class="btn btn-danger btn-sm w-100 mx-2">🚪 Keluar</button>
      </form>
    </div>

    <!-- OFFCANVAS (Mobile Menu) -->
    <div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title">Menu Siswa</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
          <li><a class="nav-link" href="{{ route('siswa.dashboard') }}">Dashboard</a></li>
          <li><a class="nav-link" href="{{ route('siswa.ujian') }}">Ujian</a></li>
          <li><a class="nav-link" href="{{ route('siswa.nilai') }}">Nilai Ujian</a></li>
          <li><a class="nav-link" href="{{ route('siswa.pengumuman') }}">Pengumuman</a></li>
          <li><a class="nav-link" href="{{ route('siswa.profile') }}">Profil</a></li>
        </ul>
      </div>
    </div>

    <!-- MAIN CONTENT AREA -->
    <div class="main-content">
      {{-- @yield('content'): Tempat konten utama dari tiap halaman akan muncul --}}
      @yield('content')
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    {{-- @stack('scripts'): Menampung JS tambahan dari child view --}}
    @stack('scripts')
</body>
</html>

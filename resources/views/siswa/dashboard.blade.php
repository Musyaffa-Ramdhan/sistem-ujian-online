{{-- Layout Utama: File ini menggunakan template dari 'layouts.app' --}}
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    {{-- CSRF Token untuk keamanan AJAX request jika diperlukan --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Siswa | Ujian Online SMP</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style>
      /* Custom CSS */
      body {
        background-color: #f0f0f0;
        font-family: "Segoe UI", sans-serif;
      }

      /* === SIDEBAR STYLE === */
      .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh; /* Full tinggi layar */
        width: 240px;
        background-color: #212529; /* Warna gelap */
        color: white;
        padding-top: 20px;
        transition: 0.3s;
      }

      /* Judul di Sidebar */
      .sidebar h4 {
        text-align: center;
        color: #f8f9fa;
        font-weight: 700;
        margin-bottom: 30px;
      }

      /* Link Menu Sidebar */
      .sidebar a {
        display: block;
        padding: 12px 20px;
        color: #ddd;
        text-decoration: none;
        transition: 0.3s;
        border-radius: 8px;
        margin: 5px 10px;
      }

      /* Efek Hover dan Aktif pada Sidebar */
      .sidebar a:hover,
      .sidebar a.active {
        background-color: royalblue;
        color: white;
      }

      /* === MAIN CONTENT === */
      /* Memberi margin kiri agar tidak tertutup sidebar */
      .main-content {
        margin-left: 260px;
        padding: 20px;
      }

      /* Card Pembuka (Welcome Banner) */
      .pembuka {
        background-color: white;
        border-radius: 12px;
        padding: 20px;
        margin-top: 30px;
      }

      .pembuka img {
        width: 100%;
        height: auto;
        border-radius: 10px;
      }

      .pembuka h2 {
        color: #0d6efd;
        font-weight: 700;
        margin-top: 10px;
      }

      /* Responsive Layout: Sidebar hilang di layar kecil */
      @media (max-width: 992px) {
        .sidebar {
          display: none;
        }
        .main-content {
          margin-left: 0;
        }
      }
    </style>
  </head>

  <body>
    <!-- 
      NAVBAR (Tampil hanya di Mobile/Tablet) 
      class d-lg-none artinya hidden di layar besar (large) 
    -->
    <nav class="navbar navbar-dark bg-dark d-lg-none">
      <div class="container-fluid">
        {{-- Tombol Toggler untuk Offcanvas Sidebar --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar">
          <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#">Dashboard Siswa</a>
      </div>
    </nav>

    <!-- 
      SIDEBAR (Tampil hanya di Desktop) 
      class d-none d-lg-block artinya hidden di mobile, tampil di large screen
    -->
    <div class="sidebar d-none d-lg-block">
      <h4>Menu Siswa</h4>
      {{-- Link Menu dengan Blade Route --}}
      <a href="{{ route('siswa.dashboard') }}" class="active">🏠 Dashboard</a>
      <a href="{{ route('siswa.ujian') }}">📝 Ujian</a>
      <a href="{{ route('siswa.nilai') }}">📊 Nilai Ujian</a>
      <a href="{{ route('siswa.pengumuman') }}">📢 Pengumuman</a>
      <a href="{{ route('siswa.profile') }}">👤 Profil</a>
      
      {{-- Form Logout (Method POST wajib untuk keamanan) --}}
      <form action="{{ route('siswa.logout') }}" method="POST" class="mt-3">
        @csrf {{-- Token CSRF Wajib untuk form POST Laravel --}}
        <button type="submit" class="btn btn-danger btn-sm w-100">🚪 Keluar</button>
      </form>
    </div>

    <!-- 
      OFFCANVAS (Sidebar Versi Mobile) 
      Menggunakan komponen Bootstrap Offcanvas
    -->
    <div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title">Menu Siswa</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
          <li><a class="nav-link active" href="{{ route('siswa.dashboard') }}">Dashboard</a></li>
          <li><a class="nav-link" href="{{ route('siswa.ujian') }}">Ujian</a></li>
          <li><a class="nav-link" href="{{ route('siswa.nilai') }}">Nilai Ujian</a></li>
          <li><a class="nav-link" href="{{ route('siswa.pengumuman') }}">Pengumuman</a></li>
          <li><a class="nav-link" href="{{ route('siswa.profile') }}">Profil</a></li>
          <li><form action="{{ route('siswa.logout') }}" method="POST" class="mt-3">
        @csrf {{-- Token CSRF Wajib untuk form POST Laravel --}}
        <button type="submit" class="btn btn-danger btn-sm w-100">🚪 Keluar</button>
      </form></li>
        </ul>
      </div>
    </div>

    <!-- MAIN CONTENT AREA -->
    <div class="main-content">
      <div class="container">
        <h1 class="fw-bold mb-4">Dashboard</h1>

        <!-- Bagian Pembuka / Welcome Message -->
        <div class="pembuka">
          <div class="row align-items-center">
            <div class="col-md-5 mb-3 mb-md-0">
               {{-- Menggunakan helper asset() untuk meload gambar dari folder public --}}
               <img src="{{ asset('images/pxfuel.jpg') }}" alt="Gambar Dashboard" class="img-fluid shadow-sm" />
            </div>
            <div class="col-md-7">
              <h2>Selamat Datang di Halaman Ujian Online</h2>
              <p class="mt-2 text-secondary">
                {{-- Menampilkan nama user yang sedang login --}}
                Halo, <strong>{{ auth()->user()->name }}</strong>! Ini adalah halaman dashboard Anda. 
                Di sini Anda dapat melihat statistik ujian, mengakses ujian yang tersedia, melihat nilai, 
                dan informasi penting lainnya terkait kegiatan belajar Anda. Semoga sukses!
              </p>
            </div>
          </div>
        </div>

        <!-- Statistik Singkat Data -->
        <div class="container my-5 bg-light py-5 rounded-3">
          <div class="text-center mb-4">
            <h3 class="fw-bold text-primary">Statistik Singkat</h3>
          </div>

          <div class="row g-4 justify-content-center">
            <!-- Card 1: Ujian Selesai -->
            <div class="col-10 col-sm-6 col-md-4 col-lg-3">
              <div class="card bg-primary text-white text-center shadow-sm rounded-4">
                <div class="card-body">
                  <h5 class="card-title fw-semibold">Ujian Selesai</h5>
                  {{-- Menampilkan variabel $ujianSelesai dari Controller --}}
                  <p class="display-6 fw-bold mb-0">{{ $ujianSelesai }}</p>
                </div>
              </div>
            </div>

            <!-- Card 2: Ujian Aktif -->
            <div class="col-10 col-sm-6 col-md-4 col-lg-3">
              <div class="card bg-success text-white text-center shadow-sm rounded-4">
                <div class="card-body">
                  <h5 class="card-title fw-semibold">Ujian Aktif</h5>
                  <p class="display-6 fw-bold mb-0">{{ $ujianAktif }}</p>
                </div>
              </div>
            </div>

            <!-- Card 3: Nilai Rata-Rata -->
            <div class="col-10 col-sm-6 col-md-4 col-lg-3">
              <div class="card bg-info text-white text-center shadow-sm rounded-4">
                <div class="card-body">
                  <h5 class="card-title fw-semibold">Nilai Rata-Rata</h5>
                  <p class="display-6 fw-bold mb-0">{{ $nilaiRataRata }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>

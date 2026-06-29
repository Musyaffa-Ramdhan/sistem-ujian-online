<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Ujian | Siswa</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style>
      body {
        background-color: #f0f0f0;
        font-family: "Segoe UI", sans-serif;
      }

      /* === SIDEBAR === */
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

      .sidebar h4 {
        text-align: center;
        color: #f8f9fa;
        font-weight: 700;
        margin-bottom: 30px;
      }

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

      /* === MAIN CONTENT === */
      .main-content {
        margin-left: 260px;
        padding: 20px;
      }

      h1 {
        text-align: center;
        color: royalblue;
        font-weight: 800;
        margin-bottom: 25px;
        border-bottom: 3px solid royalblue;
        display: inline-block;
      }

      /* === TABLE STYLING === */
      .table-container {
        background-color: white;
        padding: 20px 40px;
        border-radius: 12px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        margin-top: 40px;
      }

      th {
        background-color: #f8f9fa;
        text-align: center;
      }

      td {
        text-align: center;
      }

      /* Responsive untuk layar kecil */
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
    <!-- NAVBAR (untuk mobile) -->
    <nav class="navbar navbar-dark bg-dark d-lg-none">
      <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar">
          <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#">Daftar Ujian</a>
      </div>
    </nav>

    <!-- SIDEBAR -->
    <div class="sidebar d-none d-lg-block">
      <h4>Menu Siswa</h4>
      <a href="{{ route('siswa.dashboard') }}">🏠 Dashboard</a>
      <a href="{{ route('siswa.ujian') }}" class="active">📝 Ujian</a>
      <a href="{{ route('siswa.nilai') }}">📊 Nilai Ujian</a>
      <a href="{{ route('siswa.pengumuman') }}">📢 Pengumuman</a>
      <a href="{{ route('siswa.profile') }}">👤 Profil</a>
      <form action="{{ route('siswa.logout') }}" method="POST" class="mt-3">
        @csrf
        <button type="submit" class="btn btn-danger btn-sm w-100 mx-2">🚪 Keluar</button>
      </form>
    </div>

    <!-- OFFCANVAS (Mobile Sidebar) -->
    <div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title">Menu Siswa</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
          <li><a class="nav-link" href="{{ route('siswa.dashboard') }}">Dashboard</a></li>
          <li><a class="nav-link active" href="{{ route('siswa.ujian') }}">Ujian</a></li>
          <li><a class="nav-link" href="{{ route('siswa.nilai') }}">Nilai Ujian</a></li>
          <li><a class="nav-link" href="{{ route('siswa.pengumuman') }}">Pengumuman</a></li>
          <li><a class="nav-link" href="{{ route('siswa.profile') }}">Profil</a></li>
        </ul>
      </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
      <div class="container table-container">
        <h1>Daftar Ujian</h1>
        <div class="table-responsive mt-4">
          <table class="table table-hover align-middle">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Ujian</th>
                <th>Mata Pelajaran</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Durasi</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($ujians as $index => $ujian)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $ujian->nama_ujian }}</td>
                <td>{{ $ujian->mataPelajaran->nama_mapel ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($ujian->tanggal_ujian)->format('d M Y') }}</td>
                <td>{{ $ujian->waktu_mulai }} - {{ $ujian->waktu_selesai }}</td>
                <td>{{ $ujian->durasi }} Menit</td>
                <td>
                  @php
                      $now = now();
                      $start = \Carbon\Carbon::parse($ujian->tanggal_ujian . ' ' . $ujian->waktu_mulai);
                      $end = \Carbon\Carbon::parse($ujian->tanggal_ujian . ' ' . $ujian->waktu_selesai);
                  @endphp

                  @if($ujian->is_done)
                      <button class="btn btn-success btn-sm" disabled>Sudah Dikerjakan</button>
                  @elseif($now->lessThan($start))
                      <button class="btn btn-warning btn-sm text-white" disabled>Belum Dibuka</button>
                  @elseif($now->greaterThan($end))
                      <button class="btn btn-danger btn-sm" disabled>Waktu Habis</button>
                  @else
                      <a class="btn btn-primary btn-sm" href="{{ route('siswa.ujian.show', $ujian->id_ujian) }}">Mulai</a>
                  @endif
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="7" class="text-center text-muted py-4">
                  Belum ada ujian tersedia untuk kelas Anda saat ini.
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

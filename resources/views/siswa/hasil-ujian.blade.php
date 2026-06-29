{{-- Mewarisi layout utama siswa --}}
@extends('siswa.layouts.app')

{{-- Bagian Title Halaman --}}
@section('title', 'Detail Hasil Ujian')
{{-- Menentukan menu aktif di sidebar --}}
@section('active_menu', 'nilai')

{{-- Bagian Content Utama --}}
@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h3 class="mb-0">📊 Hasil Ujian Individu</h3>
        </div>
        <div class="card-body">
            {{-- Melakukan looping data hasil ujian --}}
            {{-- @forelse: loop yang menangani kondisi jika data KOSONG (@empty) --}}
            @forelse($hasilUjians as $hasil)
            <div class="mb-4 p-3 border rounded">
                {{-- Menampilkan Nama Ujian dari relasi --}}
                <h5>{{ $hasil->ujian->nama_ujian }}</h5>
                {{-- Menampilkan Nama Mapel (menggunakan operator ?? sebagai fallback jika null) --}}
                <p class="text-muted">{{ $hasil->ujian->mataPelajaran->nama_mapel ?? 'Mata Pelajaran' }}</p>
                
                 <div class="row">
                     <div class="col-md-6">
                         {{-- Format Tanggal menggunakan Carbon --}}
                         <p><strong>Tanggal Ujian:</strong> {{ \Carbon\Carbon::parse($hasil->created_at)->format('d M Y') }}</p>
                         <p><strong>Jumlah Soal:</strong> {{ $hasil->total_soal ?? '-' }}</p>
                     </div>
                     <div class="col-md-6">
                         <p><strong>Nilai:</strong>
                             {{-- Logika Kondisional untuk Warna Badge: Hijau jika >= 75, Merah jika kurang --}}
                             <span class="badge {{ $hasil->nilai >= 75 ? 'bg-success' : 'bg-danger' }} fs-5">
                                 {{ $hasil->nilai }}
                             </span>
                         </p>
                         <p><strong>Status:</strong>
                             {{-- If-Else Blade Directive --}}
                             @if($hasil->nilai >= 75)
                                 <span class="text-success">✅ Lulus</span>
                             @else
                                 <span class="text-danger">❌ Tidak Lulus</span>
                             @endif
                         </p>
                         {{-- Tombol Cetak PDF --}}
                         <a href="{{ route('siswa.hasil.pdf', $hasil->kode_hasil_ujian) }}" class="btn btn-primary btn-sm" target="_blank">
                             🖨️ Cetak PDF
                         </a>
                     </div>
                 </div>
            </div>
            @empty
            {{-- Tampilan jika tidak ada data hasil ujian --}}
            <div class="text-center text-muted py-5">
                <p>Belum ada hasil ujian</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Push CSS ke stack 'styles' di layout utama (jika ada) --}}
@push('styles')
<style>
    .card {
        border-radius: 12px;
        margin-top: 30px;
    }
</style>
@endpush
@endsection

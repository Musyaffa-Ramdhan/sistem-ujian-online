@extends('siswa.layouts.app')

@section('title', 'Profil')
@section('active_menu', 'profile')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">👤 Profil Siswa</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Nama Lengkap:</strong></div>
                        <div class="col-md-8">{{ $user->name }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>NISN:</strong></div>
                        <div class="col-md-8">{{ $siswa->nisn }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Kelas:</strong></div>
                        <div class="col-md-8">{{ $siswa->kelas->nama_kelas ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Email:</strong></div>
                        <div class="col-md-8">{{ $user->email ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>No. Telepon:</strong></div>
                        <div class="col-md-8">
                            <form action="{{ route('siswa.profile.update-phone') }}" method="POST" class="d-flex">
                                @csrf
                                <input type="text" name="no_telp" class="form-control me-2" value="{{ $siswa->no_telp }}" placeholder="08..." required>
                                <button type="submit" class="btn btn-primary btn-sm px-3">Simpan</button>
                            </form>
                        </div>
                    </div>

                    <hr>
                    
                    <div class="text-center text-muted small mt-4">
                        <i class="fas fa-info-circle"></i> Hanya nomor telepon yang dapat Anda ubah secara mandiri. Untuk mengubah Nama, NISN, atau Kelas, silakan hubungi pihak Tata Usaha (TU) sekolah.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card {
        border-radius: 12px;
        margin-top: 30px;
    }

    .row.mb-3 {
        padding: 10px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .row.mb-3:last-child {
        border-bottom: none;
    }
</style>
@endpush
@endsection

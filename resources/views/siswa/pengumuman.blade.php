@extends('siswa.layouts.app')

@section('title', 'Pengumuman')
@section('active_menu', 'pengumuman')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">📢 Pengumuman</h3>
        </div>
        <div class="card-body">
            @forelse($pengumumans as $pengumuman)
            <div class="alert alert-info">
                <h5 class="alert-heading">{{ $pengumuman->judul }}</h5>
                <p class="mb-2">{{ $pengumuman->isi }}</p>
                <hr>
                <p class="mb-0 text-muted small">
                    <i class="bi bi-calendar"></i> {{ \Carbon\Carbon::parse($pengumuman->created_at)->format('d M Y, H:i') }}
                </p>
            </div>
            @empty
            <div class="text-center text-muted py-5">
                <p>Belum ada pengumuman</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

@push('styles')
<style>
    .card {
        border-radius: 12px;
        margin-top: 30px;
    }

    .alert {
        border-left: 4px solid #0d6efd;
    }
</style>
@endpush
@endsection

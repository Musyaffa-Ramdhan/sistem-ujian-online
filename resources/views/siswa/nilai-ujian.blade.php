@extends('siswa.layouts.app')

@section('title', 'Nilai Ujian')
@section('active_menu', 'nilai')

@section('content')
<div class="container table-container">
    <h1>Nilai Ujian</h1>
    <div class="table-responsive mt-4">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Mata Pelajaran</th>
                    <th>Nama Ujian</th>
                    <th>Tanggal</th>
                    <th>Nilai</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($nilais as $index => $nilai)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $nilai->ujian->mataPelajaran->nama_mapel ?? '-' }}</td>
                    <td>{{ $nilai->ujian->nama_ujian ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($nilai->created_at)->format('d M Y') }}</td>
                    <td>
                        <span class="badge {{ $nilai->nilai >= 75 ? 'bg-success' : 'bg-danger' }}">
                            {{ $nilai->nilai }}
                        </span>
                    </td>
                    <td>
                        @if($nilai->nilai >= 75)
                            <span class="text-success">Lulus</span>
                        @else
                            <span class="text-danger">Tidak Lulus</span>
                        @endif
                    </td>
                    <td>
                        <a class="btn btn-info btn-sm text-white" href="{{ route('siswa.hasil') }}">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">Belum ada nilai tersedia</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('styles')
<style>
    h1 {
        text-align: center;
        color: royalblue;
        font-weight: 800;
        margin-bottom: 25px;
        border-bottom: 3px solid royalblue;
        display: inline-block;
    }

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
</style>
@endpush
@endsection

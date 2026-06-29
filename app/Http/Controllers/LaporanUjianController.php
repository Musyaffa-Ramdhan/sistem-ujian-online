<?php

namespace App\Http\Controllers;

use App\Models\HasilUjian;
use App\Models\Ujian;
use Barryvdh\DomPDF\Facade\Pdf;

// Controller untuk menangani pembuatan/download laporan PDF
class LaporanUjianController extends Controller
{
    // Download Laporan Nilai Satu Kelas untuk Guru
    public function downloadPdf(Ujian $ujian)
    {
        // Security: Pastikan Guru yang login adalah pemilik ujian ini
        if (auth()->check() && auth()->user()->hasRole('Guru')) {
            $guruId = auth()->user()->guru?->id_guru;
            // Jika ID Guru di ujian tidak sama dengan ID Guru yang login, tolak (403)
            if ($ujian->id_guru !== $guruId) {
                abort(403, 'Unauthorized action.');
            }
        }

        // Ambil seluruh hasil ujian untuk ujian ID ini
        $results = HasilUjian::where('id_ujian', $ujian->id_ujian)
            ->with('siswa') // Sertakan data siswa
            ->get();

        // Load view PDF dan masukkan data
        $pdf = Pdf::loadView('laporan.nilai-pdf', [
            'ujian' => $ujian,
            'results' => $results,
        ]);

        // Stream (download/buka) file PDF. Nama file disanitasi (ganti spasi dengan underscore)
        return $pdf->stream('Laporan_'.str_replace(' ', '_', $ujian->nama_ujian).'.pdf');
    }

    // Download Laporan Hasil Individu Siswa (diakses oleh Guru)
    public function hasilSiswaPdf($id)
    {
        // Security: Verifikasi kepemilikan
        if (auth()->check() && auth()->user()->hasRole('Guru')) {
            $guruId = auth()->user()->guru?->id_guru;
            
            // Cari hasil ujian, pastikan ujiannya milik guru yang login
            $hasil = HasilUjian::where('kode_hasil_ujian', $id)
                ->whereHas('ujian', function ($q) use ($guruId) {
                    // Filter di dalam relasi ujian
                    $q->where('id_guru', $guruId);
                })
                ->with(['ujian', 'ujian.mataPelajaran', 'ujian.guru', 'siswa', 'siswa.kelas'])
                ->firstOrFail(); // Error 404 jika tidak ditemukan
        } else {
            abort(403, 'Unauthorized action.');
        }

        // Generate PDF
        $pdf = Pdf::loadView('laporan.hasil-siswa-pdf', [
            'hasil' => $hasil,
            'siswa' => $hasil->siswa,
        ]);

        // Stream PDF
        return $pdf->stream('Hasil_'.$hasil->siswa->nama.'_'.str_replace(' ', '_', $hasil->ujian->nama_ujian).'.pdf');
    }
}

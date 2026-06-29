<?php

namespace App\Http\Controllers;

// Import Model yang dibutuhkan
use App\Models\Pengumuman; //Digunakan untuk mengambil data pengumuman dari database.
use App\Models\Ujian; //Digunakan untuk mengelola data ujian.
use Illuminate\Http\Request;

// Controller untuk menangani fitur-fitur panel Siswa
class SiswaController extends Controller
{
    // Halaman Dashboard Siswa
    public function dashboard()
    {
        // Mengambil data siswa yang sedang login
        // auth()->user() mengambil user yang login, ->siswa mengakses relasi siswa dari user tersebut
        $siswa = auth()->user()->siswa;

        // --- Real Statistics ---

        // Menghitung jumlah ujian yang sudah selesai dikerjakan oleh siswa ini
        // Menggunakan Model HasilUjian, filter berdasarkan id_siswa, lalu hitung jumlahnya (count)
        $ujianSelesai = \App\Models\HasilUjian::where('id_siswa', $siswa->id_siswa)->count();

        // Menghitung jumlah ujian yang sedang aktif untuk kelas siswa ini
        // Syarat: id_kelas sama dengan kelas siswa, dan tanggal ujian hari ini atau masa depan
        $ujianAktif = \App\Models\Ujian::where('id_kelas', $siswa->id_kelas)
            ->whereDate('tanggal_ujian', '>=', now()) // Filter tanggal >= hari ini
            ->count();

        // Menghitung rata-rata nilai siswa
        // avg('nilai') menghitung rata-rata kolom nilai. Jika null, default 0.
        // number_format(..., 1) membulatkan menjadi 1 angka di belakang koma (misal: 85.5)
        $nilaiRataRata = number_format(\App\Models\HasilUjian::where('id_siswa', $siswa->id_siswa)->avg('nilai') ?? 0, 1);

        // Mengirim data ke view 'siswa.dashboard'
        // compact() membuat array dari variabel yang ada ($siswa menjadi ['siswa' => $siswa], dst)
        return view('siswa.dashboard', compact('siswa', 'ujianSelesai', 'ujianAktif', 'nilaiRataRata'));
    }

    // Halaman Daftar Ujian yang tersedia
    public function daftarUjian()
    {
        $siswa = auth()->user()->siswa;

        // Cek apakah data siswa ada. Jika tidak, redirect ke login dengan pesan error.
        if (! $siswa) {
            return redirect()->route('siswa.login')->with('error', 'Data siswa tidak ditemukan');
        }

        // --- Get Exams (Ambil Data Ujian) ---
        // Mengambil semua ujian untuk kelas siswa ini
        $ujians = \App\Models\Ujian::where('id_kelas', $siswa->id_kelas)
            ->orderBy('tanggal_ujian', 'desc') // Urutkan dari tanggal terbaru
            ->get()
            ->map(function ($ujian) use ($siswa) {
                // Modifikasi setiap item ujian untuk menambah properti 'is_done'
                // Mengecek apakah siswa sudah mengerjakan ujian ini (ada di tabel HasilUjian)
                $ujian->is_done = \App\Models\HasilUjian::where('id_ujian', $ujian->id_ujian)
                    ->where('id_siswa', $siswa->id_siswa)
                    ->exists(); // Mengembalikan true/false

                return $ujian;
            });

        // Tampilkan view daftar ujian dengan data ujian yang sudah diproses
        return view('siswa.daftar-ujian', compact('ujians'));
    }

    // Halaman Pengerjaan Ujian
    public function halamanUjian($id)
    {
        // Cari ujian berdasarkan ID, sertakan (eager load) data soal-soalnya. Error 404 jika tidak ketemu.
        $ujian = Ujian::with('soals')->findOrFail($id);
        $siswa = auth()->user()->siswa;

        // --- Validasi Akses Kelas ---
        // Jika ujian ini bukan untuk kelas siswa, tolak akses (403 Forbidden)
        if ($ujian->id_kelas != $siswa->id_kelas) {
            abort(403, 'Anda tidak memiliki akses ke ujian ini');
        }

        // --- Validasi Waktu (Strict Time Validation) ---
        $now = now(); // Waktu sekarang
        // Parsing waktu mulai dan selesai menjadi objek Carbon (library datetime PHP)
        $start = \Carbon\Carbon::parse($ujian->tanggal_ujian.' '.$ujian->waktu_mulai);
        $end = \Carbon\Carbon::parse($ujian->tanggal_ujian.' '.$ujian->waktu_selesai);

        // Jika sekarang < waktu mulai, redirect kembali (belum mulai)
        if ($now->lessThan($start)) {
            return redirect()->route('siswa.ujian')->with('error', 'Ujian belum dimulai! Silakan tunggu waktu ujian.');
        }

        // Jika sekarang > waktu selesai, redirect kembali (sudah lewat)
        if ($now->greaterThan($end)) {
            return redirect()->route('siswa.ujian')->with('error', 'Waktu ujian telah berakhir!');
        }

        // --- Cek Apakah Sudah Mengerjakan (Prevent Retake) ---
        $alreadyTaken = \App\Models\HasilUjian::where('id_ujian', $id)
            ->where('id_siswa', $siswa->id_siswa)
            ->exists();

        if ($alreadyTaken) {
            return redirect()->route('siswa.hasil')->with('error', 'Anda sudah mengerjakan ujian ini!');
        }

        // --- Ambil Jawaban yang Pernah Disimpan (Resume capability) ---
        // pluck('jawaban_siswa', 'id_soal') membuat array [id_soal => jawaban]
        $existingAnswers = \App\Models\JawabanSiswa::where('id_ujian', $id)
            ->where('id_siswa', $siswa->id_siswa)
            ->pluck('jawaban_siswa', 'id_soal')
            ->toArray();

        // --- Hitung Sisa Waktu ---
        // Batas waktu adalah mana yang lebih dulu: jadwal selesai atau (waktu mulai + durasi pengerjaan)
        $durationEnd = $start->copy()->addMinutes($ujian->durasi);
        $finalDeadline = $end->lessThan($durationEnd) ? $end : $durationEnd;

        // Hitung selisih detik antara sekarang dan deadline
        $sisaWaktu = $now->diffInSeconds($finalDeadline, false);

        // Tampilkan halaman ujian
        return view('siswa.halaman-ujian', compact('ujian', 'existingAnswers', 'sisaWaktu'));
    }

    // AJAX Endpoint untuk Menyimpan Jawaban per Soal
    public function saveAnswer(Request $request)
    {
        // Validasi input
        $request->validate([
            'id_ujian' => 'required',
            'id_soal' => 'required',
            'jawaban' => 'required',
        ]);

        $siswa = auth()->user()->siswa;

        // Security: Cegah modifikasi jawaban jika ujian sudah disubmit (sudah ada hasil)
        if (\App\Models\HasilUjian::where('id_ujian', $request->id_ujian)->where('id_siswa', $siswa->id_siswa)->exists()) {
            return response()->json(['success' => false, 'message' => 'Ujian sudah disubmit!'], 403);
        }

        // Simpan atau update jawaban di database
        // updateOrCreate: cari record berdasarkan parameter pertama, jika ada update, jika tidak buat baru.
        \App\Models\JawabanSiswa::updateOrCreate(
            [
                'id_ujian' => $request->id_ujian,
                'id_siswa' => $siswa->id_siswa,
                'id_soal' => $request->id_soal,
            ],
            [
                'jawaban_siswa' => $request->jawaban, // Data yang diupdate/disimpan
            ]
        );

        return response()->json(['success' => true]);
    }

    // Fungsi Submit Ujian (Selesai Mengerjakan)
    public function submitUjian(Request $request, $id)
    {
        $ujian = Ujian::with('soals')->findOrFail($id);
        $siswa = auth()->user()->siswa;

        // Prevent Duplicate Submission (Cegah submit ulang)
        if (\App\Models\HasilUjian::where('id_ujian', $id)->where('id_siswa', $siswa->id_siswa)->exists()) {
            return redirect()->route('siswa.hasil');
        }

        // Ambil semua jawaban siswa dari database
        $jawabanSiswa = \App\Models\JawabanSiswa::where('id_ujian', $id)
            ->where('id_siswa', $siswa->id_siswa)
            ->pluck('jawaban_siswa', 'id_soal')
            ->toArray();

        $benar = 0;
        $salah = 0;
        $totalSoal = $ujian->soals->count();

        // Loop setiap soal untuk koreksi
        foreach ($ujian->soals as $soal) {
            $userAnswer = $jawabanSiswa[$soal->id_soal] ?? null; // Ambil jawaban siswa untuk soal ini
            $isCorrect = $userAnswer === $soal->jawaban_benar; // Cek kecocokan dengan kunci jawaban

            if ($isCorrect) {
                $benar++;
            } else {
                $salah++;
            }
        }

        // Hitung Skor Akhir (Skala 0-100)
        $nilai = ($totalSoal > 0) ? round(($benar / $totalSoal) * 100, 2) : 0;

        // Simpan Hasil Ujian
        \App\Models\HasilUjian::create([
            'id_ujian' => $id,
            'id_siswa' => $siswa->id_siswa,
            'total_benar' => $benar,
            'total_salah' => $salah,
            'nilai' => $nilai,
        ]);

        return redirect()->route('siswa.hasil')->with('success', 'Ujian berhasil disubmit! Nilai Anda: '.$nilai);
    }

    // Halaman Daftar Nilai Ujian (History)
    public function nilaiUjian()
    {
        $siswa = auth()->user()->siswa;
        // Ambil semua hasil ujian siswa ini, urutkan dari terbaru
        $nilais = \App\Models\HasilUjian::where('id_siswa', $siswa->id_siswa)
            ->with('ujian') // Eager load relasi ujian
            ->orderBy('created_at', 'desc')
            ->get();

        return view('siswa.nilai-ujian', compact('nilais'));
    }

    // Sama dengan nilaiUjian, tapi mungkin route berbeda atau untuk halaman berbeda
    public function hasilUjian()
    {
        $siswa = auth()->user()->siswa;
        // Load relasi ujian dan mata pelajaran sekaligus
        $hasilUjians = \App\Models\HasilUjian::where('id_siswa', $siswa->id_siswa)
            ->with(['ujian', 'ujian.mataPelajaran'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('siswa.hasil-ujian', compact('hasilUjians'));
    }

    // Cetak PDF Hasil Ujian Individu
    public function hasilPdf($id)
    {
        $siswa = auth()->user()->siswa;
        // Ambil hasil ujian spesifik milik siswa ini
        $hasil = \App\Models\HasilUjian::where('id_siswa', $siswa->id_siswa)
            ->where('kode_hasil_ujian', $id)
            ->with(['ujian', 'ujian.mataPelajaran', 'ujian.guru']) // Load semua data terkait untuk PDF
            ->firstOrFail();

        // Load view khusus PDF dan pass datanya
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('siswa.hasil-pdf', compact('hasil', 'siswa'));

        // Stream (tampilkan di browser) file PDF
        return $pdf->stream('hasil-ujian-'.$hasil->ujian->nama_ujian.'.pdf');
    }

    // Halaman Pengumuman
    public function pengumuman()
    {
        $pengumumans = Pengumuman::orderBy('created_at', 'desc')->get();

        return view('siswa.pengumuman', compact('pengumumans'));
    }

    // Halaman Profil Siswa
    public function profile()
    {
        $siswa = auth()->user()->siswa;
        $user = auth()->user();

        return view('siswa.profile', compact('siswa', 'user'));
    }

    // Update Nomor Telepon Siswa
    public function updatePhone(Request $request)
    {
        $request->validate([
            'no_telp' => 'required|string|max:20',
        ]);

        $siswa = auth()->user()->siswa;
        $siswa->update([
            'no_telp' => $request->no_telp,
        ]);

        return redirect()->route('siswa.profile')->with('success', 'Nomor telepon berhasil diperbarui.');
    }
}

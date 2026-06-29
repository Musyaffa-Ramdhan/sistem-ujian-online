<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Model Ujian: Data header ujian
class Ujian extends Model
{
    protected $table = 'ujians';

    protected $primaryKey = 'id_ujian';

    protected $fillable = [
        'nama_ujian',
        'id_guru',          // Guru pembuat soal
        'id_mata_pelajaran',// Mata pelajaran ujian
        'id_kelas',         // Kelas target ujian
        'tanggal_ujian',
        'waktu_mulai',
        'waktu_selesai',
        'durasi',           // Durasi pengerjaan dalam menit
    ];

    // Guru: Menunjukkan siapa guru yang membuat atau bertanggung jawab atas ujian ini
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    // Mata Pelajaran: Menunjukkan mata pelajaran apa yang sedang diujikan
    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'id_mata_pelajaran', 'id_mata_pelajaran');
    }

    // Kelas: Menunjukkan kelas mana yang harus mengikuti ujian ini
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    // Daftar Soal: Mengambil semua daftar pertanyaan yang ada di dalam ujian ini
    public function soals()
    {
        return $this->hasMany(Soal::class, 'id_ujian', 'id_ujian');
    }

    // Daftar Hasil: Mengambil semua nilai yang didapat siswa setelah mengerjakan ujian ini
    public function hasilUjian()
    {
        return $this->hasMany(HasilUjian::class, 'id_ujian', 'id_ujian');
    }
}

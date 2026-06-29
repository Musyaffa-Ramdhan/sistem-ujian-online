<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Model JawabanSiswa: Menyimpan jawaban siswa per item soal (tracking detail)
class JawabanSiswa extends Model
{
    protected $table = 'jawaban_siswa'; // Tabel singular/custom

    protected $primaryKey = 'id_jawaban_siswa'; // PK custom

    protected $fillable = [
        'id_siswa',
        'id_soal',
        'id_ujian',     // Redundan tapi berguna untuk query cepat per ujian
        'jawaban_siswa',// Jawaban yang dipilih ('A', 'B', etc)
    ];

    // Relasi ke Siswa: Menunjukkan jawaban ini dijawab oleh siswa siapa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    // Relasi ke Butir Soal: Menunjukkan jawaban ini untuk pertanyaan atau soal yang mana
    public function soal()
    {
        return $this->belongsTo(Soal::class, 'id_soal', 'id_soal');
    }

    // Relasi ke Ujian: Menunjukkan jawaban ini bagian dari sesi ujian yang mana
    public function ujian()
    {
        return $this->belongsTo(Ujian::class, 'id_ujian', 'id_ujian');
    }
}

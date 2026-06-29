<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Model Soal: Representasi per butir soal dalam ujian
class Soal extends Model
{
    protected $table = 'soal'; // Nama tabel singular 'soal'

    protected $primaryKey = 'id_soal';

    protected $fillable = [
        'id_ujian',     // Ujian induk
        'soal',         // Teks pertanyaan
        'opsi_a',       // Pilihan A
        'opsi_b',       // Pilihan B
        'opsi_c',       // Pilihan C
        'opsi_d',       // Pilihan D
        'opsi_e',       // Pilihan E
        'jawaban_benar',// Kunci jawaban (misal: 'A', 'B')
    ];

    // Relasi ke Ujian: Menunjukkan soal ini adalah bagian dari ujian yang mana
    public function ujian()
    {
        return $this->belongsTo(Ujian::class, 'id_ujian', 'id_ujian');
    }

    // Relasi ke Jawaban Siswa: Untuk melihat jawaban apa saja yang diberikan siswa untuk soal ini
    public function jawabanSiswa()
    {
        return $this->hasMany(JawabanSiswa::class, 'id_soal', 'id_soal');
    }
}

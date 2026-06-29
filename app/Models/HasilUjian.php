<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Model HasilUjian: Menyimpan nilai akhir dan statistik pengerjaan siswa
class HasilUjian extends Model
{
    protected $table = 'hasil_ujians';

    protected $primaryKey = 'kode_hasil_ujian';

    protected $fillable = [
        'id_siswa',
        'id_ujian',
        'nilai',        // Nilai akhir (0-100)
        'total_benar',  // Jumlah soal benar
        'total_salah',  // Jumlah soal salah
    ];

    // Data Siswa: Menunjukkan nilai ini milik siswa siapa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    // Data Ujian: Menunjukkan nilai ini berasal dari ujian atau tes yang mana
    public function ujian()
    {
        return $this->belongsTo(Ujian::class, 'id_ujian', 'id_ujian');
    }

    // Relasi ke Laporan: Untuk melihat detail laporan lengkap dari hasil ujian ini
    public function laporan()
    {
        return $this->hasOne(LaporanUjian::class, 'kode_hasil_ujian', 'kode_hasil_ujian');
    }
}

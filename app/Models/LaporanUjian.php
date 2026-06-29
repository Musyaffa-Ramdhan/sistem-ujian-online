<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanUjian extends Model
{
    protected $table = 'laporan_ujian';

    protected $primaryKey = 'id_laporan_ujian';

    protected $fillable = [
        'kode_hasil_ujian',
        'laporan',
    ];

    // Relasi ke Hasil Ujian: Menghubungkan laporan ini dengan nilai atau hasil ujian tertentu
    public function hasilUjian()
    {
        return $this->belongsTo(HasilUjian::class, 'kode_hasil_ujian', 'kode_hasil_ujian');
    }
}

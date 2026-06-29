<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Model Siswa: Representasi data profil siswa
class Siswa extends Model
{
    protected $table = 'siswas';

    protected $primaryKey = 'id_siswa';

    protected $keyType = 'int';

    public $incrementing = true;

    use HasFactory;

    protected $fillable = [
        'nama',
        'nisn',      // Nomor Induk Siswa Nasional (unik)
        'no_telp',
        'id_kelas',  // Kelas tempat siswa berada
        'id_user',   // Akun user untuk login
    ];

    // Relasi ke User: Menghubungkan siswa ini ke akun penggunanya (untuk login)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Relasi ke Kelas: Memberitahu kita siswa ini terdaftar di kelas mana
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    // Relasi ke Jawaban Siswa: Menyimpan semua jawaban yang pernah dipilih siswa saat ujian
    public function jawaban()
    {
        return $this->hasMany(JawabanSiswa::class, 'id_siswa', 'id_siswa');
    }

    // Relasi ke Hasil Ujian: Menyimpan nilai-nilai akhir yang didapat oleh siswa
    public function hasilUjian()
    {
        return $this->hasMany(HasilUjian::class, 'id_siswa', 'id_siswa');
    }

    // Event model: Ketika data siswa dihapus, hapus juga akun usernya
    protected static function booted()
    {
        static::deleting(function ($siswa) {
            // Hapus user yang terkait
            if ($siswa->user) {
                $siswa->user->delete();
            }
        });
    }
}

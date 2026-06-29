<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Model Guru: Representasi data profil guru
class Guru extends Model
{
    use HasFactory;

    protected $table = 'gurus';

    protected $primaryKey = 'id_guru'; // Primary key custom

    protected $keyType = 'string'; // Tipe string (mungkin NIP atau kodifikasi lain)

    public $incrementing = false; // Tidak auto increment karena string

    protected $fillable = [
        'nama',
        'no_telp',
        'id_mata_pelajaran',
        'id_user', // Foreign key ke tabel users
    ];

    // Relasi balik ke User: Menghubungkan guru ini ke akun penggunanya (untuk login)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Relasi ke Mata Pelajaran: Memberitahu kita mata pelajaran apa yang diampu oleh guru ini
    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'id_mata_pelajaran', 'id_mata_pelajaran');
    }

    // Relasi: Satu guru bisa membuat banyak ujian atau tes untuk siswa
    public function ujians()
    {
        return $this->hasMany(Ujian::class, 'id_guru', 'id_guru');
    }

    // Custom Route Key Binding
    // Jika menggunakan route model binding, akan mencari berdasarkan id_guru
    public function getRouteKeyName()
    {
        return 'id_guru';
    }

    // Event model: Ketika data guru dihapus, hapus juga akun usernya
    protected static function booted()
    {
        static::deleting(function ($guru) {
            // Hapus user yang terkait
            if ($guru->user) {
                $guru->user->delete();
            }
        });
    }
}

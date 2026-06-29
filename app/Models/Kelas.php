<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Model Kelas: Representasi kelas siswa (e.g. X IPA 1)
class Kelas extends Model
{
    protected $table = 'kelas'; // Nama tabel tanpa 'es' di akhir

    protected $primaryKey = 'id_kelas';

    public $incrementing = true;    // Auto Increment

    protected $keyType = 'int';

    protected $fillable = ['nama_kelas'];

    // Relasi: Satu kelas menampung banyak siswa
    public function siswas()
    {
        return $this->hasMany(Siswa::class, 'id_kelas', 'id_kelas');
    }
}

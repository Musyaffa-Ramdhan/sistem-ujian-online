<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Model MataPelajaran: Representasi mapel (e.g. Matematika, Fisika)
class MataPelajaran extends Model
{
    protected $table = 'mata_pelajaran'; // Tabel singular

    protected $primaryKey = 'id_mata_pelajaran';

    protected $fillable = ['nama_mapel'];

    // Relasi: Satu mata pelajaran ini bisa diajarkan oleh banyak guru
    public function gurus()
    {
        return $this->hasMany(Guru::class, 'id_mata_pelajaran', 'id_mata_pelajaran');
    }

    // Relasi: Satu mata pelajaran bisa diujikan di banyak sesi ujian yang berbeda
    public function ujians()
    {
        return $this->hasMany(Ujian::class, 'id_mata_pelajaran', 'id_mata_pelajaran');
    }
}

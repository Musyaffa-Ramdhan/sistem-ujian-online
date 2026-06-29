<?php

namespace Database\Seeders;

use App\Models\MataPelajaran;
use Illuminate\Database\Seeder;

class MataPelajaranSeeder extends Seeder
{
    public function run(): void
    {
        $mataPelajaran = [
            ['nama_mapel' => 'Pendidikan Agama dan Budi Pekerti'],
            ['nama_mapel' => 'PPKN'],
            ['nama_mapel' => 'Bahasa Indonesia'],
            ['nama_mapel' => 'Matematika'],
            ['nama_mapel' => 'IPA'],
            ['nama_mapel' => 'IPS'],
            ['nama_mapel' => 'Bahasa Inggris'],
            ['nama_mapel' => 'Informatika'],
            ['nama_mapel' => 'PJOK'],
            ['nama_mapel' => 'Seni Budaya'],
        ];

        foreach ($mataPelajaran as $mapel) {
            MataPelajaran::firstOrCreate(
                ['nama_mapel' => $mapel['nama_mapel']],
                $mapel
            );
        }
    }
}

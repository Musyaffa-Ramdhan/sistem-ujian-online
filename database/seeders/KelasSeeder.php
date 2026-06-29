<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $tingkat = ['7', '8', '9'];
        $rombel = ['A', 'B', 'C', 'D', 'E'];

        foreach ($tingkat as $t) {
            foreach ($rombel as $r) {
                Kelas::firstOrCreate(
                    ['nama_kelas' => $t.$r],
                    ['nama_kelas' => $t.$r]
                );
                $this->command->info("Created kelas: {$t}{$r}");
            }
        }
    }
}

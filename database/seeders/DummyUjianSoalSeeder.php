<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Soal;
use App\Models\Ujian;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DummyUjianSoalSeeder extends Seeder
{
    public function run()
    {
        // 1. Target Subjects and their Gurus
        // We find the Mapel first, then the Guru teaching it.
        $targetMapels = ['Matematika', 'IPA', 'IPS'];

        $kelasAll = Kelas::all();

        if ($kelasAll->isEmpty()) {
            $this->command->error('No Kelas found!');

            return;
        }

        foreach ($targetMapels as $namaMapel) {
            $mapel = MataPelajaran::where('nama_mapel', $namaMapel)->first();
            if (! $mapel) {
                $this->command->warn("Mapel $namaMapel not found, skipping.");

                continue;
            }

            // Find Guru for this Mapel
            $guru = Guru::where('id_mata_pelajaran', $mapel->id_mata_pelajaran)->first();
            if (! $guru) {
                $this->command->warn("Guru for $namaMapel not found, skipping.");

                continue;
            }

            // Create Exam for EACH Class
            foreach ($kelasAll as $kelas) {
                // Determine Schedule (Randomize slightly or fixed)
                // Set to TOMORROW or TODAY 08:00
                $tanggal = Carbon::today();

                $ujian = Ujian::create([
                    'nama_ujian' => "Ujian Akhir Semester $namaMapel Kelas {$kelas->nama_kelas}",
                    'id_guru' => $guru->id_guru,
                    'id_mata_pelajaran' => $mapel->id_mata_pelajaran,
                    'id_kelas' => $kelas->id_kelas,
                    'tanggal_ujian' => $tanggal->format('Y-m-d'),
                    'waktu_mulai' => '08:00:00',
                    'waktu_selesai' => '10:00:00',
                    'durasi' => 120,
                ]);

                // Create 20 Dummy Questions
                $soalData = [];
                for ($i = 1; $i <= 20; $i++) {
                    $soalData[] = [
                        'id_ujian' => $ujian->id_ujian,
                        'soal' => "Soal nomor $i untuk mata pelajaran $namaMapel. Berapakah hasil dari x + y jika x=$i dan y=5?",
                        'opsi_a' => (string) ($i + 5),
                        'opsi_b' => (string) ($i + 6),
                        'opsi_c' => (string) ($i + 7),
                        'opsi_d' => (string) ($i + 8),
                        'opsi_e' => (string) ($i + 9),
                        'jawaban_benar' => 'A', // Fixed answer for easy testing
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                Soal::insert($soalData); // Batch insert for speed
            }

            $this->command->info("Created exams for $namaMapel across ".$kelasAll->count().' classes.');
        }
    }
}

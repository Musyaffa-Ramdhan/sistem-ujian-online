<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\Role;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Seeder;

// SiswaSeeder: Membuat data Siswa secara masal (15 siswa per kelas)
class SiswaSeeder extends Seeder
{
    // Kumpulan nama acak untuk siswa
    private $namaSiswaPool = [
        'Ahmad Fauzi', 'Siti Rahayu', 'Budi Santoso', 'Dewi Lestari', 'Rizki Ramadhan',
        'Ayu Wulandari', 'Eko Prasetyo', 'Fitri Handayani', 'Gilang Pratama', 'Hana Safitri',
        'Irfan Maulana', 'Jasmine Azzahra', 'Kevin Anggara', 'Lina Marlina', 'Muhammad Hasan',
        // ... (data lainnya)
    ];

    private $usedNisn = [];

    /**
     * Helper: Menghasilkan NISN 10 digit unik
     */
    private function generateUniqueNisn(): string
    {
        do {
            $nisn = (string) random_int(1000000000, 9999999999);
        } while (in_array($nisn, $this->usedNisn));

        $this->usedNisn[] = $nisn;
        return $nisn;
    }

    public function run(): void
    {
        $role = Role::where('nama_role', 'Siswa')->first();

        if (! $role) {
            $this->command->error('Role Siswa not found!');
            return;
        }

        $tingkat = ['7', '8', '9']; // Kelas 7, 8, 9
        $rombel = ['A', 'B', 'C', 'D', 'E']; // Rombel A-E
        $counter = 1;
        $namaIndex = 0;

        shuffle($this->namaSiswaPool);

        foreach ($tingkat as $t) {
            foreach ($rombel as $r) {
                $namaKelas = $t.$r; // misal '7A'
                $kelas = Kelas::where('nama_kelas', $namaKelas)->first();

                if (! $kelas) continue;

                // Membuat 15 siswa untuk setiap kelas
                for ($i = 1; $i <= 15; $i++) {
                    $nisn = $this->generateUniqueNisn();
                    $nama = $this->namaSiswaPool[$namaIndex % count($this->namaSiswaPool)];
                    $namaIndex++;

                    $email = strtolower(str_replace(' ', '.', $nama)) . ".{$namaKelas}@smp.com";
                    $noTelp = '0812'.random_int(10000000, 99999999);

                    // Buat Akun User
                    $user = User::firstOrCreate(
                        ['email' => $email],
                        [
                            'name' => $nama,
                            'password' => 'siswa2025',
                            'id_role' => $role->id_role,
                        ]
                    );

                    // Buat Profil Siswa
                    Siswa::firstOrCreate(
                        ['nisn' => $nisn],
                        [
                            'id_user' => $user->id_user,
                            'nama' => $nama,
                            'nisn' => $nisn,
                            'no_telp' => $noTelp,
                            'id_kelas' => $kelas->id_kelas,
                        ]
                    );

                    $counter++;
                }
            }
        }

        $this->command->info('Total students created: '.($counter - 1));
    }
}

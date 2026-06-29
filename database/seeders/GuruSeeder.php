<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

// GuruSeeder: Mengisi data awal untuk Guru
class GuruSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil data role Guru dari database
        $role = Role::where('nama_role', 'Guru')->first();

        if (! $role) {
            $this->command->error('Role Guru not found! Please run RoleSeeder first.');
            return;
        }

        // Data dummy 10 Guru
        $guruData = [
            ['id_guru' => 'G001', 'email' => 'ustadz.ahmad@smp.com', 'nama' => 'Ustadz Ahmad Maulana', 'mapel' => 'Pendidikan Agama dan Budi Pekerti', 'no_telp' => '081234567801'],
            ['id_guru' => 'G002', 'email' => 'budi.santoso@smp.com', 'nama' => 'Budi Santoso, S.Pd', 'mapel' => 'PPKN', 'no_telp' => '081234567802'],
            ['id_guru' => 'G003', 'email' => 'siti.rahmawati@smp.com', 'nama' => 'Siti Rahmawati, S.Pd', 'mapel' => 'Bahasa Indonesia', 'no_telp' => '081234567803'],
            ['id_guru' => 'G004', 'email' => 'dr.widodo@smp.com', 'nama' => 'Drs. Widodo Hartono', 'mapel' => 'Matematika', 'no_telp' => '081234567804'],
            ['id_guru' => 'G005', 'email' => 'dewi.lestari@smp.com', 'nama' => 'Dewi Lestari, M.Pd', 'mapel' => 'IPA', 'no_telp' => '081234567805'],
            ['id_guru' => 'G006', 'email' => 'agus.setiawan@smp.com', 'nama' => 'Agus Setiawan, S.Sos', 'mapel' => 'IPS', 'no_telp' => '081234567806'],
            ['id_guru' => 'G007', 'email' => 'maria.ulfa@smp.com', 'nama' => 'Maria Ulfa, S.Pd', 'mapel' => 'Bahasa Inggris', 'no_telp' => '081234567807'],
            ['id_guru' => 'G008', 'email' => 'eko.prasetyo@smp.com', 'nama' => 'Eko Prasetyo, S.Kom', 'mapel' => 'Informatika', 'no_telp' => '081234567808'],
            ['id_guru' => 'G009', 'email' => 'hendra.gunawan@smp.com', 'nama' => 'Hendra Gunawan, S.Or', 'mapel' => 'PJOK', 'no_telp' => '081234567809'],
            ['id_guru' => 'G010', 'email' => 'ratna.sari@smp.com', 'nama' => 'Ratna Sari, S.Sn', 'mapel' => 'Seni Budaya', 'no_telp' => '081234567810'],
        ];

        foreach ($guruData as $data) {
            // Find or create user: Pastikan akun login ada di tabel 'users'
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['nama'],
                    'password' => 'guru2025', // Password default
                    'id_role' => $role->id_role,
                ]
            );

            // Find mata pelajaran: Hubungkan guru dengan mapel yang ada
            $mataPelajaran = MataPelajaran::where('nama_mapel', $data['mapel'])->first();

            if (! $mataPelajaran) {
                $this->command->warn("Mata pelajaran '{$data['mapel']}' not found. Skipping guru: {$data['nama']}");
                continue;
            }

            // Create guru profile: Isi detail di tabel 'gurus'
            Guru::firstOrCreate(
                ['id_guru' => $data['id_guru']],
                [
                    'id_user' => $user->id_user,
                    'nama' => $data['nama'],
                    'no_telp' => $data['no_telp'],
                    'id_mata_pelajaran' => $mataPelajaran->id_mata_pelajaran,
                ]
            );

            $this->command->info("Created guru: {$data['nama']} ({$data['email']})");
        }
    }
}

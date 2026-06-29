<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

// UserSeeder: Membuat user default untuk tiap peran (Admin, Guru, Siswa)
class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil data Role
        $adminRole = Role::where('nama_role', 'Admin')->first();
        $guruRole = Role::where('nama_role', 'Guru')->first();
        $siswaRole = Role::where('nama_role', 'Siswa')->first();

        // 1. Tambah/Update Akun Administrator
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => '12345678',
                'id_role' => $adminRole->id_role,
            ]
        );

        // 2. Tambah Akun Guru Sample
        User::updateOrCreate(
            ['email' => 'guru1@example.com'],
            [
                'name' => 'Guru Mata Pelajaran',
                'password' => '12345678',
                'id_role' => $guruRole->id_role,
            ]
        );

        // 3. Tambah Akun Siswa Sample
        User::updateOrCreate(
            ['email' => 'siswa1@example.com'],
            [
                'name' => 'Siswa Kelas 10',
                'password' => '12345678',
                'id_role' => $siswaRole->id_role,
            ]
        );
    }
}

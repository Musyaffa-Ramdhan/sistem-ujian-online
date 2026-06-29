<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // 1. Define Permissions
        $permissions = [
            // Master Data (Admin)
            'kelola_users',
            'kelola_guru',
            'kelola_siswa',
            'kelola_kelas',
            'kelola_mata_pelajaran',

            // Akademik (Guru)
            'buat_ujian',
            'edit_ujian',
            'hapus_ujian',
            'input_nilai',
            'lihat_daftar_siswa',

            // Siswa (Standard)

            'kerjakan_ujian',
            'lihat_hasil_nilai',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['nama_permission' => $perm]);
        }

        // 2. Assign Permissions to Roles (Pivot)

        // Admin gets ALL
        $adminRole = Role::where('nama_role', 'Admin')->first();
        if ($adminRole) {
            $allPermissions = Permission::all();
            $adminRole->permissions()->sync($allPermissions); // Admin punya semua akses
        }

        // Guru partial access
        $guruRole = Role::where('nama_role', 'Guru')->first();
        if ($guruRole) {
            $guruPermissions = Permission::whereIn('nama_permission', [
                'buat_ujian', 'edit_ujian', 'hapus_ujian',
                'input_nilai', 'lihat_daftar_siswa',
            ])->get();
            $guruRole->permissions()->sync($guruPermissions);
        }

        // Siswa limited access
        $siswaRole = Role::where('nama_role', 'Siswa')->first();
        if ($siswaRole) {
            $siswaPermissions = Permission::whereIn('nama_permission', [
                'kerjakan_ujian', 'lihat_hasil_nilai',
            ])->get();
            $siswaRole->permissions()->sync($siswaPermissions);
        }
    }
}

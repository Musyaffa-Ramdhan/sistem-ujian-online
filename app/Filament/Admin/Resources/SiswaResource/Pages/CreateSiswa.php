<?php

namespace App\Filament\Admin\Resources\SiswaResource\Pages;

use App\Filament\Admin\Resources\SiswaResource;
use App\Models\Role;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;

class CreateSiswa extends CreateRecord
{
    protected static string $resource = SiswaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // 1. Get Role Siswa
        $role = Role::where('nama_role', 'Siswa')->firstOrFail();

        // 2. Create User
        $user = User::create([
            'name' => $data['nama'],
            'email' => $data['email'],
            'password' => $data['nisn'], // NISN sebagai password default; cast 'hashed' di User model akan hash otomatis
            'id_role' => $role->id_role,
        ]);

        // 3. Set id_user for Siswa
        $data['id_user'] = $user->id_user;

        // 4. Clean virtual fields
        unset($data['email']);

        return $data;
    }
}

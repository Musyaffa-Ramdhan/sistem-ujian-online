<?php

namespace App\Filament\Admin\Resources\GuruResource\Pages;

use App\Filament\Admin\Resources\GuruResource;
use App\Models\Role;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;

class CreateGuru extends CreateRecord
{
    protected static string $resource = GuruResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // 1. Get Role Guru
        $role = Role::where('nama_role', 'Guru')->firstOrFail();

        // 2. Create User (password akan otomatis di-hash oleh cast 'hashed' di User model)
        $user = User::create([
            'name'     => $data['nama'],
            'email'    => $data['email'],
            'password' => $data['password'],
            'id_role'  => $role->id_role,
        ]);

        // 3. Set id_user for Guru
        $data['id_user'] = $user->id_user;

        // 4. Remove virtual fields so they don't break Guru insert
        unset($data['email']);
        unset($data['password']);

        return $data;
    }
}

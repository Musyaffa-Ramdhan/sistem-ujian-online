<?php

namespace App\Filament\Admin\Resources\GuruResource\Pages;

use App\Filament\Admin\Resources\GuruResource;
use Filament\Resources\Pages\EditRecord;

class EditGuru extends EditRecord
{
    protected static string $resource = GuruResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Update User
        $guru = $this->getRecord();
        $user = $guru->user;

        if ($user) {
            $userUpdate = [];

            // Check if email changed
            if (isset($data['email']) && $data['email'] !== $user->email) {
                $userUpdate['email'] = $data['email'];
            }

            // Check if password filled (dipass plain, cast 'hashed' di User model akan hash otomatis)
            if (! empty($data['password'])) {
                $userUpdate['password'] = $data['password'];
            }

            // Update name sync
            if (isset($data['nama']) && $data['nama'] !== $user->name) {
                $userUpdate['name'] = $data['nama'];
            }

            if (! empty($userUpdate)) {
                $user->update($userUpdate);
            }
        }

        // Clean data for Guru model
        unset($data['email']);
        unset($data['password']);

        return $data;
    }
}

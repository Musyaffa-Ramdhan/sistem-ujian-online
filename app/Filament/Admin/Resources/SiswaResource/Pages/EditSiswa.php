<?php

namespace App\Filament\Admin\Resources\SiswaResource\Pages;

use App\Filament\Admin\Resources\SiswaResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;

class EditSiswa extends EditRecord
{
    protected static string $resource = SiswaResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $siswa = $this->getRecord();
        $user = $siswa->user;

        if ($user) {
            $userUpdate = [];

            if (isset($data['email']) && $data['email'] !== $user->email) {
                $userUpdate['email'] = $data['email'];
            }
            if (isset($data['nama']) && $data['nama'] !== $user->name) {
                $userUpdate['name'] = $data['nama'];
            }

            if (! empty($userUpdate)) {
                $user->update($userUpdate);
            }
        }

        unset($data['email']);

        return $data;
    }
}

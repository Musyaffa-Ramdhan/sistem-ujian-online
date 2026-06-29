<?php

namespace App\Filament\Guru\Resources\JawabanSiswaResource\Pages;

use App\Filament\Guru\Resources\JawabanSiswaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJawabanSiswa extends EditRecord
{
    protected static string $resource = JawabanSiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

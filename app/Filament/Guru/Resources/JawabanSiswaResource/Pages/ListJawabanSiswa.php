<?php

namespace App\Filament\Guru\Resources\JawabanSiswaResource\Pages;

use App\Filament\Guru\Resources\JawabanSiswaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJawabanSiswa extends ListRecords
{
    protected static string $resource = JawabanSiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

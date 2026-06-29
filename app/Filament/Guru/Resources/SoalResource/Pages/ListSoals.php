<?php

namespace App\Filament\Guru\Resources\SoalResource\Pages;

use App\Filament\Guru\Resources\SoalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSoals extends ListRecords
{
    protected static string $resource = SoalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

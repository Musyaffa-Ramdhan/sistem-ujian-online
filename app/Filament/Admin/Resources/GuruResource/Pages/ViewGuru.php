<?php

namespace App\Filament\Admin\Resources\GuruResource\Pages;

use App\Filament\Admin\Resources\GuruResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;

class ViewGuru extends ViewRecord
{
    protected static string $resource = GuruResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Informasi Guru')
                    ->schema([
                        TextEntry::make('id_guru')->label('ID Guru'),
                        TextEntry::make('nama')->label('Nama Lengkap'),
                        TextEntry::make('no_telp')->label('No. Telepon'),
                        TextEntry::make('mataPelajaran.nama_mapel')->label('Mata Pelajaran'),
                        TextEntry::make('password')->label('Password')->getStateUsing(fn () => 'guru2025'),
                    ]),
                Section::make('Akun User')
                    ->schema([
                        TextEntry::make('user.email')->label('Email'),
                        TextEntry::make('user.name')->label('Nama User'),
                    ]),
            ]);
    }
}

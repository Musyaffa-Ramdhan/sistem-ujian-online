<?php

namespace App\Filament\Admin\Resources\SiswaResource\Pages;

use App\Filament\Admin\Resources\SiswaResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;

class ViewSiswa extends ViewRecord
{
    protected static string $resource = SiswaResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Informasi Siswa')
                    ->schema([
                        TextEntry::make('id_siswa')->label('ID Siswa'),
                        TextEntry::make('nama')->label('Nama Lengkap'),
                        TextEntry::make('nisn')->label('NISN (Password)'),
                        TextEntry::make('kelas.nama_kelas')->label('Kelas'),
                    ]),
                Section::make('Akun User')
                    ->schema([
                        TextEntry::make('user.email')->label('Email'),
                        TextEntry::make('user.name')->label('Nama User'),
                    ]),
            ]);
    }
}

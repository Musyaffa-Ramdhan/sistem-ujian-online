<?php

namespace App\Filament\Guru\Resources;

use App\Filament\Guru\Resources\JawabanSiswaResource\Pages;
use App\Models\JawabanSiswa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class JawabanSiswaResource extends Resource
{
    protected static ?string $model = JawabanSiswa::class;

    protected static ?string $navigationLabel = 'Jawaban Siswa';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Manajemen Ujian';

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->hasRole('Guru')) {
            $guruId = auth()->user()->guru?->id_guru;
            $query->whereHas('ujian', function ($q) use ($guruId) {
                $q->where('id_guru', $guruId);
            });
        }

        return $query;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('id_ujian')
                    ->label('Ujian')
                    ->relationship('ujian', 'nama_ujian')
                    ->disabled(),
                Forms\Components\Select::make('id_siswa')
                    ->label('Siswa')
                    ->relationship('siswa', 'nama')
                    ->disabled(),
                Forms\Components\Select::make('id_soal')
                    ->label('Soal')
                    ->relationship('soal', 'soal')
                    ->disabled(),
                Forms\Components\TextInput::make('jawaban_siswa')
                    ->label('Jawaban Siswa')
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultGroup(
                Tables\Grouping\Group::make('siswa.kelas.nama_kelas')
                    ->label('Kelas')
                    ->collapsible()
            )
            ->columns([
                Tables\Columns\TextColumn::make('ujian.nama_ujian')->label('Ujian')->searchable(),
                Tables\Columns\TextColumn::make('siswa.nama')->label('Siswa')->searchable(),
                Tables\Columns\TextColumn::make('soal.soal')->label('Soal')->limit(50)->wrap(),
                Tables\Columns\TextColumn::make('jawaban_siswa')->label('Jawaban'),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d-m-Y H:i'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJawabanSiswa::route('/'),
            'create' => Pages\CreateJawabanSiswa::route('/create'),
            'edit' => Pages\EditJawabanSiswa::route('/{record}/edit'),
            'view' => Pages\ViewJawabanSiswa::route('/{record}'),
        ];
    }
}

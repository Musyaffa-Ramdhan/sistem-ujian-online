<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MataPelajaranResource\Pages;
use App\Models\MataPelajaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

// MataPelajaranResource: Mengelola data Mata Pelajaran di Panel Admin
class MataPelajaranResource extends Resource
{
    protected static ?string $model = MataPelajaran::class;

    protected static ?string $navigationLabel = 'Mata Pelajaran';

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationGroup = 'Manajemen Data';

    /**
     * Form Header Mapel
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_mapel')
                    ->label('Nama Mata Pelajaran')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    /**
     * Table List Mapel
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_mata_pelajaran')->sortable()->label('ID'),
                Tables\Columns\TextColumn::make('nama_mapel')->searchable()->sortable()->label('Mata Pelajaran'),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->dateTime('d-m-Y H:i'),
                Tables\Columns\TextColumn::make('updated_at')->label('Diubah')->dateTime('d-m-Y H:i'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMataPelajarans::route('/'),
            'create' => Pages\CreateMataPelajaran::route('/create'),
            'edit' => Pages\EditMataPelajaran::route('/{record}/edit'),
            'view' => Pages\ViewMataPelajaran::route('/{record}'),
        ];
    }
}

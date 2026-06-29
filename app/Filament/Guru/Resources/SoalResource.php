<?php

namespace App\Filament\Guru\Resources;

use App\Filament\Guru\Resources\SoalResource\Pages;
use App\Models\Soal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

// SoalResource: Mengelola butir soal ujian
class SoalResource extends Resource
{
    protected static ?string $model = Soal::class;

    /**
     * getEloquentQuery: Guru hanya bisa mengelola soal dari ujian yang ia buat
     */
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->hasRole('Guru')) {
            $guruId = auth()->user()->guru?->id_guru;
            // Filter: soal yang ujiannya milik guru ini
            $query->whereHas('ujian', function ($q) use ($guruId) {
                $q->where('id_guru', $guruId);
            });
        }

        return $query;
    }

    protected static ?string $navigationLabel = 'Soal';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Manajemen Ujian';

    /**
     * Form Input Soal
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Pilih Ujian mana soal ini akan diletakkan
                Forms\Components\Select::make('id_ujian')
                    ->label('Ujian')
                    // Relasi: Hanya tampilkan ujian buatan guru ini
                    ->relationship('ujian', 'nama_ujian', modifyQueryUsing: fn ($query) => $query->where('id_guru', auth()->user()->guru?->id_guru))
                    ->required(),
                    
                // Teks Pertanyaan
                Forms\Components\Textarea::make('soal')
                    ->label('Pertanyaan')
                    ->required(),
                    
                // Pilihan Ganda (A-D Wajib, E Opsional)
                Forms\Components\TextInput::make('opsi_a')->label('Pilihan A')->required(),
                Forms\Components\TextInput::make('opsi_b')->label('Pilihan B')->required(),
                Forms\Components\TextInput::make('opsi_c')->label('Pilihan C')->required(),
                Forms\Components\TextInput::make('opsi_d')->label('Pilihan D')->required(),
                Forms\Components\TextInput::make('opsi_e')->label('Pilihan E')->nullable(),
                
                // Kunci Jawaban
                Forms\Components\Select::make('jawaban_benar')
                    ->label('Jawaban Benar')
                    ->options([
                        'A' => 'A',
                        'B' => 'B',
                        'C' => 'C',
                        'D' => 'D',
                        'E' => 'E',
                    ])
                    ->required(),
            ]);
    }

    /**
     * Table: Daftar Soal
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_soal')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('ujian.nama_ujian')->label('Ujian')->searchable(),
                // Teks soal dipotong (limit) agar tidak terlalu panjang di tabel
                Tables\Columns\TextColumn::make('soal')->label('Pertanyaan')->limit(50)->wrap(),
                Tables\Columns\TextColumn::make('jawaban_benar')->label('Kunci'),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d-m-Y H:i'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSoals::route('/'),
            'create' => Pages\CreateSoal::route('/create'),
            'edit' => Pages\EditSoal::route('/{record}/edit'),
            'view' => Pages\ViewSoal::route('/{record}'),
        ];
    }
}

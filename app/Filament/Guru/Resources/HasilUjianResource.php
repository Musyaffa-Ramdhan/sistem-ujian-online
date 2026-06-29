<?php

namespace App\Filament\Guru\Resources;

use App\Filament\Guru\Resources\HasilUjianResource\Pages;
use App\Models\HasilUjian;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

// HasilUjianResource: Melihat nilai siswa dari sisi Guru
class HasilUjianResource extends Resource
{
    protected static ?string $model = HasilUjian::class;

    protected static ?string $navigationLabel = 'Hasil Ujian';

    protected static ?string $navigationIcon = 'heroicon-o-document-check';

    protected static ?string $navigationGroup = 'Manajemen Ujian';

    /**
     * getEloquentQuery: Guru hanya bisa melihat hasil ujian dari ujian yang ia buat sendiri
     */
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->hasRole('Guru')) {
            $guruId = auth()->user()->guru?->id_guru;
            // Filter: dimanapun ujian-nya milik guru ini
            $query->whereHas('ujian', function ($q) use ($guruId) {
                $q->where('id_guru', $guruId);
            });
        }

        return $query;
    }

    /**
     * Form: (Biasanya readonly, tapi guru bisa dipersilakan mengedit nilai jika perlu)
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Relasi Ujian (Disabled agar tidak diubah sembarang)
                Forms\Components\Select::make('id_ujian')
                    ->label('Ujian')
                    ->relationship('ujian', 'nama_ujian')
                    ->disabled(),
                
                // Relasi Siswa (Disabled)
                Forms\Components\Select::make('id_siswa')
                    ->label('Siswa')
                    ->relationship('siswa', 'nama')
                    ->disabled(),
                    
                // Input Nilai
                Forms\Components\TextInput::make('nilai')
                    ->label('Nilai Akhir')
                    ->numeric()
                    ->required(),
                    
                Forms\Components\TextInput::make('total_benar')
                    ->label('Total Benar')
                    ->numeric(),
                    
                Forms\Components\TextInput::make('total_salah')
                    ->label('Total Salah')
                    ->numeric(),
            ]);
    }

    /**
     * Table: Daftar nilai siswa
     */
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
                Tables\Columns\TextColumn::make('nilai')->label('Nilai')->sortable(),
                Tables\Columns\TextColumn::make('total_benar')->label('Benar'),
                Tables\Columns\TextColumn::make('total_salah')->label('Salah'),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d-m-Y H:i'),
            ])
            ->actions([
                // Aksi Custom: Cetak PDF detil pengerjaan per siswa
                Tables\Actions\Action::make('pdf')
                    ->label('Download PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->url(fn (HasilUjian $record) => route('guru.hasil.pdf', $record->kode_hasil_ujian))
                    ->openUrlInNewTab(),
                    
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHasilUjians::route('/'),
            'create' => Pages\CreateHasilUjian::route('/create'),
            'edit' => Pages\EditHasilUjian::route('/{record}/edit'),
            'view' => Pages\ViewHasilUjian::route('/{record}'),
        ];
    }
}

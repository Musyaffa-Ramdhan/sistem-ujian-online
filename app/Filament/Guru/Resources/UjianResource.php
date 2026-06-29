<?php

namespace App\Filament\Guru\Resources;

use App\Filament\Guru\Resources\UjianResource\Pages;
use App\Models\Ujian;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

// UjianResource: Pengaturan CRUD Ujian di Panel Guru
class UjianResource extends Resource
{
    protected static ?string $model = Ujian::class;

    /**
     * getEloquentQuery: Membatasi data agar Guru hanya melihat ujian buatannya sendiri
     */
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->hasRole('Guru')) {
            $guruId = auth()->user()->guru?->id_guru;
            // Tambahkan filter where id_guru = ID Guru yang sedang login
            $query->where('id_guru', $guruId);
        }

        return $query;
    }

    protected static ?string $navigationLabel = 'Ujian';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';

    protected static ?string $navigationGroup = 'Manajemen Ujian';

    /**
     * Form Header Ujian
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Input ID Guru secara otomatis (hidden dari user)
                Forms\Components\Hidden::make('id_guru')
                    ->default(fn () => auth()->user()->guru?->id_guru)
                    ->required(),

                // Nama atau Judul Ujian
                Forms\Components\TextInput::make('nama_ujian')
                    ->label('Nama Ujian')
                    ->required()
                    ->maxLength(255),

                // Pilih Mata Pelajaran (Otomatis memfilter hanya mapel yang diampu guru login)
                Forms\Components\Select::make('id_mata_pelajaran')
                    ->label('Mata Pelajaran')
                    ->relationship('mataPelajaran', 'nama_mapel', modifyQueryUsing: fn ($query) => $query->where('id_mata_pelajaran', auth()->user()->guru?->id_mata_pelajaran))
                    ->default(fn () => auth()->user()->guru?->id_mata_pelajaran)
                    ->selectablePlaceholder(false)
                    ->required(),

                // Pilih Kelas tujuan ujian
                Forms\Components\Select::make('id_kelas')
                    ->label('Kelas')
                    ->relationship('kelas', 'nama_kelas')
                    ->required(),

                // Tanggal pelaksanaan ujian
                Forms\Components\DatePicker::make('tanggal_ujian')
                    ->label('Tanggal Ujian')
                    ->native(false) // Menggunakan calendar picker modern
                    ->displayFormat('d/m/Y')
                    ->required(),

                // Jam Mulai
                Forms\Components\TimePicker::make('waktu_mulai')
                    ->label('Waktu Mulai')
                    ->required(),

                // Jam Selesai
                Forms\Components\TimePicker::make('waktu_selesai')
                    ->label('Waktu Selesai')
                    ->required(),

                // Durasi dalam menit
                Forms\Components\TextInput::make('durasi')
                    ->label('Durasi (menit)')
                    ->numeric()
                    ->required(),
            ]);
    }

    /**
     * Table: Daftar Ujian Guru
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_ujian')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('nama_ujian')->searchable(),
                Tables\Columns\TextColumn::make('mataPelajaran.nama_mapel')->label('Mapel')->searchable(),
                Tables\Columns\TextColumn::make('kelas.nama_kelas')->label('Kelas')->searchable(),
                Tables\Columns\TextColumn::make('tanggal_ujian')->date('d-m-Y'),
                Tables\Columns\TextColumn::make('waktu_mulai')->time('H:i'),
                Tables\Columns\TextColumn::make('waktu_selesai')->time('H:i'),
                Tables\Columns\TextColumn::make('durasi')->label('Durasi (m)'),
            ])
            ->actions([
                // Aksi Custom: Download PDF hasil satu kelas
                Tables\Actions\Action::make('pdf')
                    ->label('Laporan PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    // Mengarahkan ke route backend yang sudah kita buat sebelumnya
                    ->url(fn (Ujian $record) => route('laporan.pdf', $record))
                    ->openUrlInNewTab(),
                
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
            'index' => Pages\ListUjians::route('/'),
            'create' => Pages\CreateUjian::route('/create'),
            'edit' => Pages\EditUjian::route('/{record}/edit'),
            'view' => Pages\ViewUjian::route('/{record}'),
        ];
    }
}

<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SiswaResource\Pages;
use App\Models\Kelas;
use App\Models\Siswa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

// SiswaResource: CRUD data Siswa di Panel Admin
class SiswaResource extends Resource
{
    // Hubungkan ke Model Siswa
    protected static ?string $model = Siswa::class;

    // Label navigasi sidebar
    protected static ?string $navigationLabel = 'Siswa';

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationGroup = 'Manajemen User';

    /**
     * Form: Pengaturan buat/edit data siswa
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Nama Siswa
                Forms\Components\TextInput::make('nama')
                    ->label('Nama Siswa')
                    ->required()
                    ->maxLength(255),
                
                // NISN (Harus unik dan maksimal 20 digit untuk mencegah error spasi)
                Forms\Components\TextInput::make('nisn')
                    ->label('NISN')
                    ->required()
                    ->maxLength(20)
                    ->unique('siswas', 'nisn', ignoreRecord: true), // Abaikan record sendiri saat edit

                // No Telepon
                Forms\Components\TextInput::make('no_telp')
                    ->label('No. Telepon')
                    ->maxLength(20),
                    
                // Pilih Kelas (Relasi ke tabel kelas)
                Forms\Components\Select::make('id_kelas')
                    ->label('Kelas')
                    ->options(Kelas::all()->pluck('nama_kelas', 'id_kelas'))
                    ->required()
                    ->searchable(),

                // Section Identitas Login (Menyambungkan ke tabel users)
                Forms\Components\Section::make('Akun User')
                    ->description('Email dan password untuk login')
                    ->schema([
                        // Email User
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            // Mengambil email dari relasi user jika record ada
                            ->formatStateUsing(fn ($record) => $record?->user?->email),
                    ])
                    ->collapsible(),
            ]);
    }

    /**
     * Table: Pengaturan daftar siswa di tabel
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_siswa')->sortable()->label('ID'),
                Tables\Columns\TextColumn::make('nama')->searchable()->label('Nama'),
                Tables\Columns\TextColumn::make('nisn')->searchable()->label('NISN'),
                Tables\Columns\TextColumn::make('user.email')->searchable()->label('Email'),
                Tables\Columns\TextColumn::make('no_telp')->label('No. Telp'),
                Tables\Columns\TextColumn::make('kelas.nama_kelas')->label('Kelas')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d-m-Y H:i')->label('Dibuat'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            // Tombol "Create" yang muncul di tengah jika data kosong
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    /**
     * getPages: Endpoint halaman internal Filament
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSiswa::route('/'),
            'create' => Pages\CreateSiswa::route('/create'),
            'edit' => Pages\EditSiswa::route('/{record}/edit'),
            'view' => Pages\ViewSiswa::route('/{record}'),
        ];
    }
}

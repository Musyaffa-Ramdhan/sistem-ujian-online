<?php

namespace App\Filament\Admin\Resources;

// Import Pages yang terkait dengan Resource ini
use App\Filament\Admin\Resources\GuruResource\Pages;
use App\Models\Guru;
use App\Models\MataPelajaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

// GuruResource: Pengaturan halaman CRUD (Tambah, Edit, Hapus, Lihat) data Guru di Panel Admin
class GuruResource extends Resource
{
    // Hubungkan resource ini dengan Model Guru
    protected static ?string $model = Guru::class;

    // Judul record yang akan muncul di pencarian global
    protected static ?string $recordTitleAttribute = 'nama';

    // Label yang muncul di menu navigasi
    protected static ?string $navigationLabel = 'Guru';

    // Ikon untuk menu navigasi (menggunakan Heroicons)
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    // Pengelompokan menu di sidebar
    protected static ?string $navigationGroup = 'Manajemen User';

    /**
     * static function form: Mengatur struktur form Tambah & Edit data
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Input ID Guru (bisa NIP atau Kode Guru)
                Forms\Components\TextInput::make('id_guru')
                    ->label('ID Guru')
                    ->required()
                    ->maxLength(255),
                    
                // Input Nama Lengkap
                Forms\Components\TextInput::make('nama')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(255),
                    
                // Input Nomor Telepon dengan tipe tel()
                Forms\Components\TextInput::make('no_telp')
                    ->label('No. Telepon')
                    ->tel()
                    ->maxLength(20),
                    
                // Pilih Mata Pelajaran (Relasi ke tabel mata_pelajaran)
                Forms\Components\Select::make('id_mata_pelajaran')
                    ->label('Mata Pelajaran')
                    ->options(MataPelajaran::all()->pluck('nama_mapel', 'id_mata_pelajaran'))
                    ->required()
                    ->searchable(), // Bisa diketik untuk mencari

                // Bagian form tambahan untuk Akun Login (tabel users)
                Forms\Components\Section::make('Akun User')
                    ->description('Email dan password untuk login')
                    ->schema([
                        // Email User
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->rules([
                                // Validasi unik email di tabel users, kecuali record yang sedang diedit
                                fn ($get, $livewire) => 'unique:users,email,'.($livewire->getRecord()?->user?->id_user ?? 'NULL').',id_user',
                            ])
                            // Menampilkan email dari relasi user saat edit
                            ->formatStateUsing(fn ($record) => $record?->user?->email),

                        // Password User
                        Forms\Components\TextInput::make('password')
                            ->label('Password')
                            ->password()
                            // Hanya tampil dan wajib diisi saat mode 'create' (Buat Baru)
                            ->visible(fn ($context) => $context === 'create')
                            ->required()
                            ->dehydrated(true), // Pastikan data dikirim saat simpan
                    ])
                    ->collapsible(), // Bisa di-expand/collapse
            ]);
    }

    /**
     * static function table: Mengatur tampilan tabel daftar guru
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Kolom ID Guru
                Tables\Columns\TextColumn::make('id_guru')
                    ->label('ID Guru')
                    ->sortable()
                    ->searchable(),
                    
                // Kolom Nama
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                    
                // Kolom Email (diambil dari relasi user)
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(),
                    
                // Kolom Nomor Telepon
                Tables\Columns\TextColumn::make('no_telp')
                    ->label('No. Telepon'),
                    
                // Kolom Mata Pelajaran (relasi)
                Tables\Columns\TextColumn::make('mataPelajaran.nama_mapel')
                    ->label('Mata Pelajaran')
                    ->sortable(),
                    
                // Kolom Tanggal Dibuat (disembunyikan secara default)
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Tempat untuk menambahkan filter tabel
            ])
            ->actions([
                // Tombol aksi di setiap baris (Liat, Edit, Hapus)
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Aksi untuk banyak data sekaligus (misal: Hapus Masal)
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    /**
     * static function getPages: Daftar route halaman untuk resource ini
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGurus::route('/'), // Daftar data
            'create' => Pages\CreateGuru::route('/create'), // Tambah data
            'edit' => Pages\EditGuru::route('/{record}/edit'), // Edit data
            'view' => Pages\ViewGuru::route('/{record}'), // Lihat detail
        ];
    }
}

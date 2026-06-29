<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// Model User: Representasi tabel 'users' untuk autentikasi
// FilamentUser: Interface agar user bisa mengakses panel admin/guru Filament
class User extends Authenticatable implements FilamentUser
{
    use Notifiable;

    protected $table = 'users';

    protected $primaryKey = 'id_user'; // Primary key custom

    protected $keyType = 'int'; // Tipe data primary key integer

    public $incrementing = true; // Primary key auto increment

    // Kolom yang boleh diisi secara massal (create/update)
    protected $fillable = [
        'name',
        'email',
        'password',
        'id_role',
    ];

    // Kolom yang disembunyikan saat model dikonversi ke array/JSON
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Konversi tipe data otomatis
    protected $casts = [
        'password' => 'hashed', // Password otomatis di-hash saat disimpan
    ];

    // Relasi ke tabel Roles: Menentukan tingkatan akses user (seperti Admin, Guru, atau Siswa)
    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role' /**fk di table user */, 'id_role' /** pk di table roles */);
    }

    // Relasi ke tabel Guru: Jika user ini adalah seorang guru, ambil data profil gurunya di sini
    public function guru()
    {                                       
        return $this->hasOne(Guru::class, 'id_user' /** fk di table guru */, 'id_user' /**pk di table user */);
    }

    // Relasi ke tabel Siswa: Jika user ini adalah seorang siswa, ambil data profil siswanya di sini
    public function siswa()
    {
        return $this->hasOne(Siswa::class, 'id_user' /** fk di table siswa */, 'id_user' /** pk di table user */);
    }

    // Helper untuk cek role user
    public function hasRole($roleName)
    {
        // Cek apakah relasi role ada, dan nama role sesuai
        return $this->role && $this->role->nama_role === $roleName;
    }

    // Implementasi method interface FilamentUser
    public function canAccessPanel(Panel $panel): bool
    {
        // Atur hak akses berdasarkan ID panel (admin/guru) dan role user
        if ($panel->getId() === 'admin') {
            return $this->hasRole('Admin');
        }

        if ($panel->getId() === 'guru') {
            return $this->hasRole('Guru'); // Hanya Guru yang bisa akses panel guru
        }

        return false;
    }

    // Custom Reset Password Notification
    public function sendPasswordResetNotification($token)
    {
        // Mengirim notifikasi custom (bukan default Laravel)
        $this->notify(new \App\Notifications\GuruResetPasswordNotification($token));
    }
}

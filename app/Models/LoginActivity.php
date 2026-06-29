<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Model LoginActivity: Mencatat log setiap kali user login
class LoginActivity extends Model
{
    use HasFactory;

    protected $table = 'login_activities';

    protected $fillable = [
        'id_user',      // User yang login
        'id_role',      // Role saat login
        'login_at',     // Waktu login
        'ip_address',   // IP Address user
    ];

    // Data User: Menunjukkan siapa pengguna yang melakukan login ini
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Data Role: Menunjukkan peran apa (Admin/Guru/Siswa) yang digunakan saat login
    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role', 'id_role');
    }
}

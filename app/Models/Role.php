<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';

    protected $primaryKey = 'id_role';

    protected $fillable = ['nama_role'];

    // Relasi ke Permissions: Menunjukkan apa saja yang boleh dilakukan oleh jabatan (role) ini
    public function permissions()
    {
        return $this->belongsToMany(
            Permission::class,
            'role_permission',
            'id_role',
            'id_permission'
        );
    }

    // Relasi ke Users: Menunjukkan siapa saja pengguna yang memiliki jabatan (role) ini
    public function users()
    {
        return $this->hasMany(User::class, 'id_role', 'id_role');
    }
}

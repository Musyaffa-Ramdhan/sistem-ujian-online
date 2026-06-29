<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';

    protected $primaryKey = 'id_permission';

    protected $fillable = ['nama_permission'];

    // Relasi ke Roles: Menunjukkan hak akses ini dimiliki oleh jabatan (role) mana saja
    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'role_permission',
            'id_permission',
            'id_role'
        );
    }
}

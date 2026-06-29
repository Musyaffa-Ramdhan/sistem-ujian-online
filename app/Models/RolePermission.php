<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $table = 'role_permission';

    protected $primaryKey = 'id_role_permission';

    protected $fillable = [
        'id_role',
        'id_permission',
    ];

    public $timestamps = true;
}

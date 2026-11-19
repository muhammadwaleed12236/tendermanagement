<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermissionSetting extends Model
{
    protected $fillable = [
        'role',
        'permissions',
    ];

    protected function casts(): array
    {
        return [
            'permissions' => 'array',
        ];
    }
}

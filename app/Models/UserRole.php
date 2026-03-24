<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserRole extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'is_active'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function rights(): HasMany
    {
        return $this->hasMany(UserRoleRight::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'role_id');
    }
}

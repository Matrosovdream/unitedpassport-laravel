<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserRoleRight extends Model
{
    protected $fillable = ['user_role_id', 'role_code', 'role_id'];

    public function userRole(): BelongsTo
    {
        return $this->belongsTo(UserRole::class);
    }
}

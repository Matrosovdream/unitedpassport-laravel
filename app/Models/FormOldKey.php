<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormOldKey extends Model
{
    protected $fillable = [
        'form_id', 'old_field_id', 'new_field_code',
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }
}

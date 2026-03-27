<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormStatus extends Model
{
    protected $fillable = [
        'form_id', 'code', 'value', 'description', 'color',
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(FormItem::class, 'status_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormField extends Model
{
    protected $fillable = [
        'field_key', 'name', 'description', 'type', 'default_value',
        'options', 'field_order', 'page_num', 'required', 'field_options', 'form_id',
    ];

    protected function casts(): array
    {
        return [
            'field_order' => 'integer',
            'page_num' => 'integer',
        ];
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function metas(): HasMany
    {
        return $this->hasMany(FormItemMeta::class, 'field_id');
    }
}

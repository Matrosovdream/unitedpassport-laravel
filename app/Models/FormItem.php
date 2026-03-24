<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormItem extends Model
{
    protected $fillable = [
        'item_key', 'name', 'description', 'ip', 'form_id',
        'post_id', 'user_id', 'parent_item_id', 'is_draft', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'is_draft' => 'boolean',
        ];
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function metas(): HasMany
    {
        return $this->hasMany(FormItemMeta::class, 'item_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(FormPayment::class, 'item_id');
    }
}

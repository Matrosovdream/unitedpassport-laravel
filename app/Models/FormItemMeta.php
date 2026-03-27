<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormItemMeta extends Model
{
    protected $fillable = ['field_key', 'meta_value', 'field_id', 'item_id'];

    public function field(): BelongsTo
    {
        return $this->belongsTo(FormField::class, 'field_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(FormItem::class, 'item_id');
    }
}

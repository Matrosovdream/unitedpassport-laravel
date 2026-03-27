<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Form extends Model
{
    protected $fillable = [
        'form_key', 'name', 'description', 'parent_form_id',
        'logged_in', 'editable', 'is_template', 'default_template', 'status', 'options',
    ];

    protected function casts(): array
    {
        return [
            'logged_in' => 'boolean',
            'editable' => 'boolean',
            'is_template' => 'boolean',
            'default_template' => 'boolean',
        ];
    }

    public function fields(): HasMany
    {
        return $this->hasMany(FormField::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(FormItem::class);
    }

    public function statuses(): HasMany
    {
        return $this->hasMany(FormStatus::class);
    }

    public function settings(): HasMany
    {
        return $this->hasMany(FormSetting::class);
    }
}

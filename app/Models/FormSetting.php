<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormSetting extends Model
{
    protected $fillable = ['form_id', 'setting_code', 'setting_id'];

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }
}

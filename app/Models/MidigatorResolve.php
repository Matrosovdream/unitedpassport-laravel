<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MidigatorResolve extends Model
{
    protected $fillable = [
        'prevention_id', 'prevention_guid', 'resolution_type', 'description',
    ];

    public function prevention(): BelongsTo
    {
        return $this->belongsTo(MidigatorPrevention::class, 'prevention_id');
    }

    public function history(): HasMany
    {
        return $this->hasMany(MidigatorResolveHistory::class, 'resolve_id');
    }
}

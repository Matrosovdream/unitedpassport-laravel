<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MidigatorResolveHistory extends Model
{
    protected $table = 'midigator_resolve_history';

    protected $fillable = [
        'resolve_id', 'prevention_id', 'user_id',
        'prevention_guid', 'resolution_type', 'description',
    ];

    public function resolve(): BelongsTo
    {
        return $this->belongsTo(MidigatorResolve::class, 'resolve_id');
    }

    public function prevention(): BelongsTo
    {
        return $this->belongsTo(MidigatorPrevention::class, 'prevention_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MidigatorPrevention extends Model
{
    protected $fillable = [
        'amount', 'arn', 'card_brand', 'card_first_6', 'card_last_4',
        'currency', 'merchant_descriptor', 'mid', 'order_guid', 'order_id',
        'prevention_case_number', 'prevention_guid', 'prevention_timestamp',
        'prevention_type', 'transaction_timestamp', 'is_resolved',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'prevention_timestamp' => 'datetime',
            'transaction_timestamp' => 'datetime',
            'is_resolved' => 'boolean',
        ];
    }

    public function resolves(): HasMany
    {
        return $this->hasMany(MidigatorResolve::class, 'prevention_id');
    }

    public function resolveHistory(): HasMany
    {
        return $this->hasMany(MidigatorResolveHistory::class, 'prevention_id');
    }
}

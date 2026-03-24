<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EasypostShipmentRate extends Model
{
    protected $fillable = [
        'easypost_id', 'entry_id', 'mode', 'service', 'carrier',
        'rate', 'currency', 'retail_rate', 'retail_currency',
        'list_rate', 'list_currency', 'billing_type', 'delivery_days',
        'delivery_date', 'delivery_date_guaranteed', 'est_delivery_days',
        'easypost_shipment_id',
    ];

    protected function casts(): array
    {
        return [
            'rate' => 'decimal:2',
            'retail_rate' => 'decimal:2',
            'list_rate' => 'decimal:2',
            'delivery_date' => 'datetime',
            'delivery_date_guaranteed' => 'boolean',
        ];
    }

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(EasypostShipment::class, 'easypost_shipment_id', 'easypost_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EasypostShipmentParcel extends Model
{
    protected $fillable = [
        'easypost_id', 'entry_id', 'length', 'width',
        'height', 'weight', 'easypost_shipment_id',
    ];

    protected function casts(): array
    {
        return [
            'length' => 'decimal:2',
            'width' => 'decimal:2',
            'height' => 'decimal:2',
            'weight' => 'decimal:2',
        ];
    }

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(EasypostShipment::class, 'easypost_shipment_id', 'easypost_id');
    }
}

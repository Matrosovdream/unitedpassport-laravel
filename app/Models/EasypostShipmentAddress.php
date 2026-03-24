<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EasypostShipmentAddress extends Model
{
    protected $fillable = [
        'easypost_id', 'entry_id', 'address_type', 'name', 'company',
        'street1', 'street2', 'city', 'state', 'zip', 'country',
        'phone', 'email', 'easypost_shipment_id',
    ];

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(EasypostShipment::class, 'easypost_shipment_id', 'easypost_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EasypostShipmentHistory extends Model
{
    protected $table = 'easypost_shipment_history';

    protected $fillable = [
        'shipment_id', 'easypost_shipment_id', 'user_id', 'change_type', 'description',
    ];

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(EasypostShipment::class, 'shipment_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

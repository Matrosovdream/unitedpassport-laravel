<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EasypostShipment extends Model
{
    protected $fillable = [
        'easypost_id', 'entry_id', 'is_return', 'status',
        'tracking_code', 'refund_status', 'mode', 'tracking_url',
    ];

    protected function casts(): array
    {
        return [
            'is_return' => 'boolean',
        ];
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(EasypostShipmentAddress::class, 'easypost_shipment_id', 'easypost_id');
    }

    public function label(): HasOne
    {
        return $this->hasOne(EasypostShipmentLabel::class, 'easypost_shipment_id', 'easypost_id');
    }

    public function parcel(): HasOne
    {
        return $this->hasOne(EasypostShipmentParcel::class, 'easypost_shipment_id', 'easypost_id');
    }

    public function rates(): HasMany
    {
        return $this->hasMany(EasypostShipmentRate::class, 'easypost_shipment_id', 'easypost_id');
    }

    public function history(): HasMany
    {
        return $this->hasMany(EasypostShipmentHistory::class, 'shipment_id');
    }
}

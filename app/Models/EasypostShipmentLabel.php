<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EasypostShipmentLabel extends Model
{
    protected $fillable = [
        'easypost_id', 'easypost_shipment_id', 'entry_id', 'date_advance',
        'integrated_form', 'label_date', 'label_resolution', 'label_size',
        'label_type', 'label_file_type', 'label_url', 'label_pdf_url',
        'label_zpl_url', 'label_epl2_url',
    ];

    protected function casts(): array
    {
        return [
            'label_date' => 'datetime',
        ];
    }

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(EasypostShipment::class, 'easypost_shipment_id', 'easypost_id');
    }
}

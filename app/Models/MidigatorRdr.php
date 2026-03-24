<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MidigatorRdr extends Model
{
    protected $table = 'midigator_rdr';

    protected $fillable = [
        'amount', 'arn', 'auth_code', 'card_first_6', 'card_last_4',
        'currency', 'merchant_descriptor', 'event_guid', 'event_timestamp',
        'event_type', 'rdr_guid', 'rdr_case_number', 'rdr_date',
        'rdr_resolution', 'prevention_type', 'transaction_date',
        'order_id', 'is_resolved',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'event_timestamp' => 'datetime',
            'rdr_date' => 'date',
            'transaction_date' => 'date',
            'is_resolved' => 'boolean',
        ];
    }
}

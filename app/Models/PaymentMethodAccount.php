<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentMethodAccount extends Model
{
    protected $fillable = [
        'payment_method_id', 'label', 'login_id', 'transaction_key',
        'api_key', 'api_secret', 'public_key', 'merchant_id',
        'environment', 'webhook_secret', 'extra', 'is_active',
    ];

    protected $hidden = [
        'transaction_key', 'api_key', 'api_secret', 'webhook_secret',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}

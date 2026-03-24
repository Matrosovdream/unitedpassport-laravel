<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentAuthnet extends Model
{
    protected $table = 'payments_authnet';

    protected $fillable = [
        'amount', 'payment_id', 'invoice_id', 'entry_id',
        'form_id', 'authnet_login_id', 'authnet_transaction_key',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
        ];
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(FormPayment::class, 'payment_id');
    }
}

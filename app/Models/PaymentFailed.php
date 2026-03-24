<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentFailed extends Model
{
    protected $table = 'payments_failed';

    protected $fillable = ['entry_id', 'form_id', 'payment_id', 'error_code', 'error_message'];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(FormPayment::class, 'payment_id');
    }
}

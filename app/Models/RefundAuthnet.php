<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RefundAuthnet extends Model
{
    protected $table = 'refunds_authnet';

    protected $fillable = ['sum', 'payment_id'];

    protected function casts(): array
    {
        return [
            'sum' => 'decimal:2',
        ];
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(FormPayment::class, 'payment_id');
    }
}

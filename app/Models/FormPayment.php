<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormPayment extends Model
{
    protected $fillable = [
        'receipt_id', 'invoice_id', 'sub_id', 'item_id',
        'amount', 'status', 'begin_date', 'expire_date', 'payment_method', 'test',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'begin_date' => 'date',
            'expire_date' => 'date',
            'test' => 'boolean',
        ];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(FormItem::class, 'item_id');
    }

    public function method(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method');
    }

    public function authnetPayments(): HasMany
    {
        return $this->hasMany(PaymentAuthnet::class, 'payment_id');
    }

    public function failures(): HasMany
    {
        return $this->hasMany(PaymentFailed::class, 'payment_id');
    }

    public function refunds(): HasMany
    {
        return $this->hasMany(RefundAuthnet::class, 'payment_id');
    }
}

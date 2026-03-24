<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    protected $fillable = ['name', 'slug', 'gateway', 'is_active'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(PaymentMethodAccount::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(FormPayment::class, 'payment_method');
    }
}

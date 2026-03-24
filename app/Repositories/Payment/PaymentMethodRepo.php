<?php

namespace App\Repositories\Payment;

use App\Models\PaymentMethod;
use App\Repositories\AbstractRepo;

class PaymentMethodRepo extends AbstractRepo
{
    protected $withRelations = ['accounts'];

    public function __construct()
    {
        $this->model = new PaymentMethod();
    }

    public function mapItem($item)
    {
        if (empty($item)) {
            return null;
        }

        return [
            'id' => $item->id,
            'name' => $item->name,
            'slug' => $item->slug,
            'gateway' => $item->gateway,
            'is_active' => $item->is_active,
            'Model' => $item,
        ];
    }
}

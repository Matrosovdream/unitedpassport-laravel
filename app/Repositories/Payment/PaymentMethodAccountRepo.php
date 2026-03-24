<?php

namespace App\Repositories\Payment;

use App\Models\PaymentMethodAccount;
use App\Repositories\AbstractRepo;

class PaymentMethodAccountRepo extends AbstractRepo
{
    public function __construct()
    {
        $this->model = new PaymentMethodAccount();
    }

    public function getByMethodId($methodId)
    {
        $items = $this->model
            ->where('payment_method_id', $methodId)
            ->where('is_active', true)
            ->get();

        return $this->mapItems($items);
    }

    public function mapItem($item)
    {
        if (empty($item)) {
            return null;
        }

        return [
            'id' => $item->id,
            'payment_method_id' => $item->payment_method_id,
            'label' => $item->label,
            'environment' => $item->environment,
            'is_active' => $item->is_active,
            'Model' => $item,
        ];
    }
}

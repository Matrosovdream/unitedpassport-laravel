<?php

namespace App\Repositories\Payment;

use App\Models\RefundAuthnet;
use App\Repositories\AbstractRepo;

class RefundAuthnetRepo extends AbstractRepo
{
    public function __construct()
    {
        $this->model = new RefundAuthnet();
    }

    public function mapItem($item)
    {
        if (empty($item)) {
            return null;
        }

        return [
            'id' => $item->id,
            'sum' => $item->sum,
            'payment_id' => $item->payment_id,
            'Model' => $item,
        ];
    }
}

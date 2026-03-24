<?php

namespace App\Repositories\Payment;

use App\Models\PaymentFailed;
use App\Repositories\AbstractRepo;

class PaymentFailedRepo extends AbstractRepo
{
    public function __construct()
    {
        $this->model = new PaymentFailed();
    }

    public function mapItem($item)
    {
        if (empty($item)) {
            return null;
        }

        return [
            'id' => $item->id,
            'entry_id' => $item->entry_id,
            'form_id' => $item->form_id,
            'payment_id' => $item->payment_id,
            'error_code' => $item->error_code,
            'error_message' => $item->error_message,
            'Model' => $item,
        ];
    }
}

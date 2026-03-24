<?php

namespace App\Repositories\Payment;

use App\Models\PaymentAuthnet;
use App\Repositories\AbstractRepo;

class PaymentAuthnetRepo extends AbstractRepo
{
    public function __construct()
    {
        $this->model = new PaymentAuthnet();
    }

    public function mapItem($item)
    {
        if (empty($item)) {
            return null;
        }

        return [
            'id' => $item->id,
            'amount' => $item->amount,
            'payment_id' => $item->payment_id,
            'invoice_id' => $item->invoice_id,
            'entry_id' => $item->entry_id,
            'form_id' => $item->form_id,
            'Model' => $item,
        ];
    }
}

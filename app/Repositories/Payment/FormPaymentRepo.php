<?php

namespace App\Repositories\Payment;

use App\Models\FormPayment;
use App\Repositories\AbstractRepo;

class FormPaymentRepo extends AbstractRepo
{
    protected $withRelations = ['item', 'method'];

    public function __construct()
    {
        $this->model = new FormPayment();
    }

    public function getByItemId($itemId)
    {
        $items = $this->model
            ->where('item_id', $itemId)
            ->with($this->withRelations)
            ->orderBy('id', 'desc')
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
            'receipt_id' => $item->receipt_id,
            'invoice_id' => $item->invoice_id,
            'item_id' => $item->item_id,
            'amount' => $item->amount,
            'status' => $item->status,
            'begin_date' => $item->begin_date,
            'expire_date' => $item->expire_date,
            'payment_method' => $item->payment_method,
            'Model' => $item,
        ];
    }
}

<?php

namespace App\Repositories\Easypost;

use App\Models\EasypostShipmentRate;
use App\Repositories\AbstractRepo;

class EasypostShipmentRateRepo extends AbstractRepo
{
    public function __construct()
    {
        $this->model = new EasypostShipmentRate();
    }

    public function mapItem($item)
    {
        if (empty($item)) {
            return null;
        }

        return [
            'id' => $item->id,
            'easypost_id' => $item->easypost_id,
            'service' => $item->service,
            'carrier' => $item->carrier,
            'rate' => $item->rate,
            'currency' => $item->currency,
            'delivery_days' => $item->delivery_days,
            'delivery_date' => $item->delivery_date,
            'Model' => $item,
        ];
    }
}

<?php

namespace App\Repositories\Easypost;

use App\Models\EasypostShipmentHistory;
use App\Repositories\AbstractRepo;

class EasypostShipmentHistoryRepo extends AbstractRepo
{
    public function __construct()
    {
        $this->model = new EasypostShipmentHistory();
    }

    public function mapItem($item)
    {
        if (empty($item)) {
            return null;
        }

        return [
            'id' => $item->id,
            'shipment_id' => $item->shipment_id,
            'easypost_shipment_id' => $item->easypost_shipment_id,
            'user_id' => $item->user_id,
            'change_type' => $item->change_type,
            'description' => $item->description,
            'Model' => $item,
        ];
    }
}

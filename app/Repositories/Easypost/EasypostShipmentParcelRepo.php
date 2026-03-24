<?php

namespace App\Repositories\Easypost;

use App\Models\EasypostShipmentParcel;
use App\Repositories\AbstractRepo;

class EasypostShipmentParcelRepo extends AbstractRepo
{
    public function __construct()
    {
        $this->model = new EasypostShipmentParcel();
    }

    public function mapItem($item)
    {
        if (empty($item)) {
            return null;
        }

        return [
            'id' => $item->id,
            'easypost_id' => $item->easypost_id,
            'length' => $item->length,
            'width' => $item->width,
            'height' => $item->height,
            'weight' => $item->weight,
            'Model' => $item,
        ];
    }
}

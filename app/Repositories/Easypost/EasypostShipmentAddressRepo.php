<?php

namespace App\Repositories\Easypost;

use App\Models\EasypostShipmentAddress;
use App\Repositories\AbstractRepo;

class EasypostShipmentAddressRepo extends AbstractRepo
{
    public function __construct()
    {
        $this->model = new EasypostShipmentAddress();
    }

    public function mapItem($item)
    {
        if (empty($item)) {
            return null;
        }

        return [
            'id' => $item->id,
            'easypost_id' => $item->easypost_id,
            'address_type' => $item->address_type,
            'name' => $item->name,
            'company' => $item->company,
            'street1' => $item->street1,
            'street2' => $item->street2,
            'city' => $item->city,
            'state' => $item->state,
            'zip' => $item->zip,
            'country' => $item->country,
            'phone' => $item->phone,
            'email' => $item->email,
            'Model' => $item,
        ];
    }
}

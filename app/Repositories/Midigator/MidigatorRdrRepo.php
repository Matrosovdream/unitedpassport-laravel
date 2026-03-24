<?php

namespace App\Repositories\Midigator;

use App\Models\MidigatorRdr;
use App\Repositories\AbstractRepo;

class MidigatorRdrRepo extends AbstractRepo
{
    public function __construct()
    {
        $this->model = new MidigatorRdr();
    }

    public function getByGuid($guid)
    {
        $item = $this->model
            ->where('rdr_guid', $guid)
            ->first();

        return $this->mapItem($item);
    }

    public function mapItem($item)
    {
        if (empty($item)) {
            return null;
        }

        return [
            'id' => $item->id,
            'amount' => $item->amount,
            'arn' => $item->arn,
            'rdr_guid' => $item->rdr_guid,
            'rdr_case_number' => $item->rdr_case_number,
            'rdr_date' => $item->rdr_date,
            'rdr_resolution' => $item->rdr_resolution,
            'order_id' => $item->order_id,
            'is_resolved' => $item->is_resolved,
            'Model' => $item,
        ];
    }
}

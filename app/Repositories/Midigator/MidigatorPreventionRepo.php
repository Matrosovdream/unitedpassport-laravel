<?php

namespace App\Repositories\Midigator;

use App\Models\MidigatorPrevention;
use App\Repositories\AbstractRepo;

class MidigatorPreventionRepo extends AbstractRepo
{
    protected $withRelations = ['resolves'];

    public function __construct()
    {
        $this->model = new MidigatorPrevention();
    }

    public function getByGuid($guid)
    {
        $item = $this->model
            ->where('prevention_guid', $guid)
            ->with($this->withRelations)
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
            'card_brand' => $item->card_brand,
            'order_id' => $item->order_id,
            'prevention_guid' => $item->prevention_guid,
            'prevention_type' => $item->prevention_type,
            'is_resolved' => $item->is_resolved,
            'Model' => $item,
        ];
    }
}

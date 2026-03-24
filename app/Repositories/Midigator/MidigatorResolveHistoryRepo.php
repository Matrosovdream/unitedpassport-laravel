<?php

namespace App\Repositories\Midigator;

use App\Models\MidigatorResolveHistory;
use App\Repositories\AbstractRepo;

class MidigatorResolveHistoryRepo extends AbstractRepo
{
    public function __construct()
    {
        $this->model = new MidigatorResolveHistory();
    }

    public function mapItem($item)
    {
        if (empty($item)) {
            return null;
        }

        return [
            'id' => $item->id,
            'resolve_id' => $item->resolve_id,
            'prevention_id' => $item->prevention_id,
            'user_id' => $item->user_id,
            'prevention_guid' => $item->prevention_guid,
            'resolution_type' => $item->resolution_type,
            'description' => $item->description,
            'Model' => $item,
        ];
    }
}

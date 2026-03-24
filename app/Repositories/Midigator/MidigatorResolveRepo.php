<?php

namespace App\Repositories\Midigator;

use App\Models\MidigatorResolve;
use App\Repositories\AbstractRepo;

class MidigatorResolveRepo extends AbstractRepo
{
    protected $withRelations = ['prevention', 'history'];

    public function __construct()
    {
        $this->model = new MidigatorResolve();
    }

    public function mapItem($item)
    {
        if (empty($item)) {
            return null;
        }

        return [
            'id' => $item->id,
            'prevention_id' => $item->prevention_id,
            'prevention_guid' => $item->prevention_guid,
            'resolution_type' => $item->resolution_type,
            'description' => $item->description,
            'Model' => $item,
        ];
    }
}

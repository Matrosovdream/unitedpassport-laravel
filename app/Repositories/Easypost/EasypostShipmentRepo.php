<?php

namespace App\Repositories\Easypost;

use App\Models\EasypostShipment;
use App\Repositories\AbstractRepo;

class EasypostShipmentRepo extends AbstractRepo
{
    protected $withRelations = ['addresses', 'label', 'parcel', 'rates'];

    public function __construct()
    {
        $this->model = new EasypostShipment();
    }

    public function getByEasypostId($easypostId)
    {
        $item = $this->model
            ->where('easypost_id', $easypostId)
            ->with($this->withRelations)
            ->first();

        return $this->mapItem($item);
    }

    public function getByEntryId($entryId)
    {
        $items = $this->model
            ->where('entry_id', $entryId)
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
            'easypost_id' => $item->easypost_id,
            'entry_id' => $item->entry_id,
            'is_return' => $item->is_return,
            'status' => $item->status,
            'tracking_code' => $item->tracking_code,
            'refund_status' => $item->refund_status,
            'mode' => $item->mode,
            'tracking_url' => $item->tracking_url,
            'Model' => $item,
        ];
    }
}

<?php

namespace App\Repositories\Form;

use App\Models\FormItemMeta;
use App\Repositories\AbstractRepo;

class FormItemMetaRepo extends AbstractRepo
{
    public function __construct()
    {
        $this->model = new FormItemMeta();
    }

    public function getByItemId($itemId)
    {
        $items = $this->model
            ->where('item_id', $itemId)
            ->with(['field'])
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
            'meta_value' => $item->meta_value,
            'field_id' => $item->field_id,
            'item_id' => $item->item_id,
            'Model' => $item,
        ];
    }
}

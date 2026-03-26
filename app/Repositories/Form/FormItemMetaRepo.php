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

    private function unpackValue(?string $value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        $unserialized = @unserialize($value);

        if ($unserialized === false && $value !== 'b:0;') {
            return $value;
        }

        if (is_array($unserialized)) {
            $flat = array_values(array_filter($unserialized, fn($v) => $v !== '' && $v !== null));
            return implode(', ', array_map('strval', $flat));
        }

        return (string) $unserialized;
    }

    public function mapItem($item)
    {
        if (empty($item)) {
            return null;
        }

        return [
            'id' => $item->id,
            'meta_value' => $this->unpackValue($item->meta_value),
            'field_id' => $item->field_id,
            'item_id' => $item->item_id,
            'field' => $item->relationLoaded('field') && $item->field ? [
                'id' => $item->field->id,
                'name' => $item->field->name,
                'type' => $item->field->type,
                'field_order' => $item->field->field_order,
                'page_num' => $item->field->page_num,
                'field_key' => $item->field->field_key,
            ] : null,
            'Model' => $item,
        ];
    }
}

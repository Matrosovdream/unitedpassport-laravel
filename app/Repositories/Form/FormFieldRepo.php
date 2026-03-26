<?php

namespace App\Repositories\Form;

use App\Models\FormField;
use App\Repositories\AbstractRepo;

class FormFieldRepo extends AbstractRepo
{
    public function __construct()
    {
        $this->model = new FormField();
    }

    public function getByFormId($formId)
    {
        $items = $this->model
            ->where('form_id', $formId)
            ->orderBy('page_num')
            ->orderBy('field_order')
            ->get();

        return $items->map(fn($item) => $this->mapItem($item));
    }

    public function getMaxOrder(int $formId, int $pageNum): int
    {
        return (int) $this->model
            ->where('form_id', $formId)
            ->where('page_num', $pageNum)
            ->max('field_order');
    }

    public function mapItem($item)
    {
        if (empty($item)) {
            return null;
        }

        return [
            'id' => $item->id,
            'field_key' => $item->field_key,
            'name' => $item->name,
            'description' => $item->description,
            'type' => $item->type,
            'default_value' => $item->default_value,
            'options' => $item->options,
            'field_order' => $item->field_order,
            'page_num' => $item->page_num,
            'required' => $item->required,
            'field_options' => $item->field_options,
            'form_id' => $item->form_id,
            'Model' => $item,
        ];
    }
}

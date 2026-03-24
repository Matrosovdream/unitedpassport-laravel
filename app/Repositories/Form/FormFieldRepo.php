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
            ->orderBy('field_order')
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
            'field_key' => $item->field_key,
            'name' => $item->name,
            'type' => $item->type,
            'field_order' => $item->field_order,
            'page_num' => $item->page_num,
            'required' => $item->required,
            'form_id' => $item->form_id,
            'Model' => $item,
        ];
    }
}

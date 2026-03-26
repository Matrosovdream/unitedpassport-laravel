<?php

namespace App\Repositories\Form;

use App\Models\Form;
use App\Repositories\AbstractRepo;

class FormRepo extends AbstractRepo
{
    protected $withRelations = ['fields', 'settings'];

    public function __construct()
    {
        $this->model = new Form();
    }

    public function getByKey($formKey)
    {
        $item = $this->model
            ->where('form_key', $formKey)
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
            'form_key' => $item->form_key,
            'name' => $item->name,
            'description' => $item->description,
            'parent_form_id' => $item->parent_form_id,
            'logged_in' => $item->logged_in,
            'editable' => $item->editable,
            'is_template' => $item->is_template,
            'default_template' => $item->default_template,
            'status' => $item->status,
            'options' => $item->options,
            'fields_count' => $item->relationLoaded('fields') ? $item->fields->count() : 0,
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at,
            'Model' => $item,
        ];
    }
}

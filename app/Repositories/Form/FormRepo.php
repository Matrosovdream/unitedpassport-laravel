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
            'status' => $item->status,
            'is_template' => $item->is_template,
            'Model' => $item,
        ];
    }
}

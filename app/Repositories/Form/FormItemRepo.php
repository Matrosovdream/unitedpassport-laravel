<?php

namespace App\Repositories\Form;

use App\Models\FormItem;
use App\Repositories\AbstractRepo;

class FormItemRepo extends AbstractRepo
{
    protected $withRelations = ['metas', 'form', 'user'];

    public function __construct()
    {
        $this->model = new FormItem();
    }

    public function getByFormId($formId, $paginate = 20)
    {
        $items = $this->model
            ->where('form_id', $formId)
            ->with($this->withRelations)
            ->orderBy('id', 'desc')
            ->paginate($paginate);

        return $this->mapItems($items);
    }

    public function mapItem($item)
    {
        if (empty($item)) {
            return null;
        }

        return [
            'id' => $item->id,
            'item_key' => $item->item_key,
            'name' => $item->name,
            'form_id' => $item->form_id,
            'user_id' => $item->user_id,
            'is_draft' => $item->is_draft,
            'Model' => $item,
        ];
    }
}

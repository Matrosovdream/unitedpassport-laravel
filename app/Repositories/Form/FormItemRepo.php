<?php

namespace App\Repositories\Form;

use App\Models\FormItem;
use App\Repositories\AbstractRepo;

class FormItemRepo extends AbstractRepo
{
    protected $withRelations = ['form', 'user'];

    public function __construct()
    {
        $this->model = new FormItem();
    }

    public function search(array $params, int $perPage, array $sorting)
    {
        $query = $this->model->with($this->withRelations);

        if (!empty($params['search'])) {
            $search = '%' . $params['search'] . '%';
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', $search)
                    ->orWhereHas('form', fn($q) => $q->where('name', 'LIKE', $search))
                    ->orWhereHas('user', fn($q) => $q->where('name', 'LIKE', $search)->orWhere('email', 'LIKE', $search));
            });
        }

        if (!empty($params['form_id'])) {
            $query->where('form_id', $params['form_id']);
        }

        if (!empty($params['entry_id'])) {
            $query->where('id', (int) $params['entry_id']);
        }

        if (isset($params['is_draft']) && $params['is_draft'] !== '') {
            $query->where('is_draft', (bool) $params['is_draft']);
        }

        if (!empty($params['date_from'])) {
            $query->whereDate('created_at', '>=', $params['date_from']);
        }

        if (!empty($params['date_to'])) {
            $query->whereDate('created_at', '<=', $params['date_to']);
        }

        if (!empty($sorting)) {
            foreach ($sorting as $column => $direction) {
                $query->orderBy($column, $direction);
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        $items = $query->paginate($perPage);

        return $this->mapItems($items);
    }

    private function unpackValue(?string $value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        // Check for PHP serialized data
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

    public function getWithMetas(int $id)
    {
        $item = $this->model
            ->with(['form', 'user', 'metas.field'])
            ->find($id);

        return $this->mapItem($item);
    }

    public function mapItem($item)
    {
        if (empty($item)) {
            return null;
        }

        $metas = null;
        if ($item->relationLoaded('metas')) {
            $metas = $item->metas->map(function ($meta) {
                return [
                    'id' => $meta->id,
                    'meta_value' => $this->unpackValue($meta->meta_value),
                    'field_id' => $meta->field_id,
                    'field' => $meta->relationLoaded('field') && $meta->field ? [
                        'id' => $meta->field->id,
                        'name' => $meta->field->name,
                        'type' => $meta->field->type,
                        'field_order' => $meta->field->field_order,
                        'page_num' => $meta->field->page_num,
                        'field_key' => $meta->field->field_key,
                    ] : null,
                ];
            })->sortBy(fn($m) => $m['field']['field_order'] ?? 9999)->values();
        }

        return [
            'id' => $item->id,
            'item_key' => $item->item_key,
            'name' => $item->name,
            'form_id' => $item->form_id,
            'user_id' => $item->user_id,
            'ip' => $item->ip,
            'is_draft' => $item->is_draft,
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at,
            'form' => $item->relationLoaded('form') && $item->form ? [
                'id' => $item->form->id,
                'name' => $item->form->name,
                'form_key' => $item->form->form_key,
            ] : null,
            'user' => $item->relationLoaded('user') && $item->user ? [
                'id' => $item->user->id,
                'name' => $item->user->name,
                'email' => $item->user->email,
            ] : null,
            'metas' => $metas,
            'Model' => $item,
        ];
    }
}

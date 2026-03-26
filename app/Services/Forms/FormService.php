<?php

namespace App\Services\Forms;

use App\Repositories\Form\FormFieldRepo;
use App\Repositories\Form\FormItemMetaRepo;
use App\Repositories\Form\FormItemRepo;
use App\Repositories\Form\FormRepo;
use Illuminate\Support\Str;

class FormService
{
    public function __construct(
        protected FormRepo $formRepo,
        protected FormFieldRepo $fieldRepo,
        protected FormItemRepo $itemRepo,
        protected FormItemMetaRepo $metaRepo,
    ) {}

    public function getAll(array $filter = [], int $perPage = 20, array $sorting = [])
    {
        return $this->formRepo->getAll($filter, $perPage, $sorting);
    }

    public function getById(int $id)
    {
        return $this->formRepo->getByID($id);
    }

    public function getFormWithFields(int $id)
    {
        $form = $this->formRepo->getByID($id);

        if (!$form) {
            return null;
        }

        $form['fields'] = $this->fieldRepo->getByFormId($id)
            ->map(fn($field) => collect($field)->except('Model'));

        return $form;
    }

    public function updateForm(int $id, array $data): ?array
    {
        return $this->formRepo->update($id, $data);
    }

    public function addField(int $formId, array $data): ?array
    {
        $data['form_id'] = $formId;

        if (empty($data['field_key'])) {
            $data['field_key'] = Str::slug($data['name'] ?? 'field', '_') . '_' . Str::random(6);
        }

        if (!isset($data['page_num'])) {
            $data['page_num'] = 1;
        }

        if (!isset($data['field_order'])) {
            $data['field_order'] = $this->fieldRepo->getMaxOrder($formId, $data['page_num']) + 1;
        }

        return $this->fieldRepo->create($data);
    }

    public function updateField(int $fieldId, array $data): ?array
    {
        return $this->fieldRepo->update($fieldId, $data);
    }

    public function deleteField(int $fieldId): bool
    {
        return $this->fieldRepo->delete($fieldId);
    }

    public function reorderFields(int $formId, array $fields): void
    {
        foreach ($fields as $field) {
            $this->fieldRepo->update($field['id'], [
                'field_order' => $field['field_order'],
                'page_num' => $field['page_num'],
            ]);
        }
    }

    public function getByKey(string $formKey)
    {
        return $this->formRepo->getByKey($formKey);
    }

    public function getFieldsByFormId(int $formId)
    {
        return $this->fieldRepo->getByFormId($formId);
    }

    public function getEntries(array $filter, int $perPage, array $sorting)
    {
        return $this->itemRepo->search($filter, $perPage, $sorting);
    }

    public function getEntry(int $id)
    {
        return $this->itemRepo->getWithMetas($id);
    }

    public function deleteEntry(int $id): bool
    {
        return $this->itemRepo->delete($id);
    }

    public function updateEntry(int $itemId, array $values): ?array
    {
        $item = $this->itemRepo->getByID($itemId);

        if (!$item) {
            return null;
        }

        $fields = $this->fieldRepo->getByFormId($item['form_id']);

        foreach ($fields as $field) {
            $fieldKey = $field['field_key'];
            if (!\array_key_exists($fieldKey, $values)) {
                continue;
            }

            $existing = $this->metaRepo->getFirst([
                'field_id' => $field['id'],
                'item_id' => $itemId,
            ]);

            if ($existing) {
                $this->metaRepo->update($existing['id'], ['meta_value' => $values[$fieldKey]]);
            } else {
                $this->metaRepo->create([
                    'item_id' => $itemId,
                    'field_id' => $field['id'],
                    'meta_value' => $values[$fieldKey],
                ]);
            }
        }

        return $this->itemRepo->getWithMetas($itemId);
    }

    public function submitForm(int $formId, array $values, ?int $userId, ?string $ip): array
    {
        $item = $this->itemRepo->create([
            'item_key' => 'item_' . Str::random(12),
            'form_id' => $formId,
            'user_id' => $userId,
            'ip' => $ip,
            'is_draft' => false,
        ]);

        $fields = $this->fieldRepo->getByFormId($formId);

        foreach ($fields as $field) {
            $fieldKey = $field['field_key'];
            if (array_key_exists($fieldKey, $values)) {
                $this->metaRepo->create([
                    'item_id' => $item['id'],
                    'field_id' => $field['id'],
                    'meta_value' => $values[$fieldKey],
                ]);
            }
        }

        return $item;
    }
}

<?php

namespace App\Mixins\Migrators;

use App\Repositories\Form\FormFieldRepo;

class FormFieldsMigrator extends AbstractMigrator
{
    protected FormFieldRepo $repo;

    public function __construct()
    {
        $this->repo = new FormFieldRepo();
    }

    protected function mapColumns(array $row): array
    {
        return [
            'id' => $row['id'],
            'field_key' => $row['field_key'] ?? null,
            'name' => $row['name'] ?? null,
            'description' => $row['description'] ?? null,
            'type' => $row['type'] ?? null,
            'default_value' => $row['default_value'] ?? null,
            'options' => $row['options'] ?? null,
            'field_order' => $row['field_order'] ?? 0,
            'page_num' => $row['page_num'] ?? 1,
            'required' => $row['required'] ?? null,
            'field_options' => $row['field_options'] ?? null,
            'form_id' => $row['form_id'] ?? null,
            'created_at' => $row['created_at'],
            'updated_at' => $row['created_at'],
        ];
    }

    protected function migrateRow(array $row): string
    {
        $mapped = $this->mapColumns($row);
        $existing = $this->repo->getByID($mapped['id']);

        if ($existing) {
            $this->repo->update($mapped['id'], $mapped);
            return 'updated';
        }

        $this->repo->getModel()->forceCreate($mapped);
        return 'created';
    }
}

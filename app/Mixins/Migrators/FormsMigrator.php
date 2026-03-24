<?php

namespace App\Mixins\Migrators;

use App\Repositories\Form\FormRepo;

class FormsMigrator extends AbstractMigrator
{
    protected FormRepo $repo;

    public function __construct()
    {
        $this->repo = new FormRepo();
    }

    protected function mapColumns(array $row): array
    {
        return [
            'id' => $row['id'],
            'form_key' => $row['form_key'] ?? null,
            'name' => $row['name'] ?? null,
            'description' => $row['description'] ?? null,
            'parent_form_id' => $row['parent_form_id'] ?? 0,
            'logged_in' => $row['logged_in'] ?? null,
            'editable' => $row['editable'] ?? null,
            'is_template' => $row['is_template'] ?? false,
            'default_template' => $row['default_template'] ?? false,
            'status' => $row['status'] ?? null,
            'options' => $row['options'] ?? null,
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

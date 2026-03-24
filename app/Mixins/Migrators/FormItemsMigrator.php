<?php

namespace App\Mixins\Migrators;

use App\Repositories\Form\FormItemRepo;

class FormItemsMigrator extends AbstractMigrator
{
    protected FormItemRepo $repo;

    public function __construct()
    {
        $this->repo = new FormItemRepo();
    }

    protected function mapColumns(array $row): array
    {
        return [
            'id' => $row['id'],
            'item_key' => $row['item_key'] ?? null,
            'name' => $row['name'] ?? null,
            'description' => $row['description'] ?? null,
            'ip' => $row['ip'] ?? null,
            'form_id' => $row['form_id'] ?? null,
            'post_id' => $row['post_id'] ?? null,
            'user_id' => $row['user_id'] ?? null,
            'parent_item_id' => $row['parent_item_id'] ?? 0,
            'is_draft' => $row['is_draft'] ?? false,
            'updated_by' => $row['updated_by'] ?? null,
            'created_at' => $row['created_at'],
            'updated_at' => $row['updated_at'],
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

<?php

namespace App\Mixins\Migrators;

use App\Repositories\Form\FormItemMetaRepo;

class FormItemMetasMigrator extends AbstractMigrator
{
    protected FormItemMetaRepo $repo;

    public function __construct()
    {
        $this->repo = new FormItemMetaRepo();
    }

    protected function mapColumns(array $row): array
    {
        return [
            'id' => $row['id'],
            'meta_value' => $row['meta_value'] ?? null,
            'field_id' => $row['field_id'],
            'item_id' => $row['item_id'],
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

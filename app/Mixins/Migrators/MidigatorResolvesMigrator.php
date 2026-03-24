<?php

namespace App\Mixins\Migrators;

use App\Repositories\Midigator\MidigatorResolveRepo;

class MidigatorResolvesMigrator extends AbstractMigrator
{
    protected MidigatorResolveRepo $repo;

    public function __construct()
    {
        $this->repo = new MidigatorResolveRepo();
    }

    protected function mapColumns(array $row): array
    {
        return [
            'id' => $row['id'],
            'prevention_id' => $row['prevention_id'],
            'prevention_guid' => $row['prevention_guid'],
            'resolution_type' => $row['resolution_type'] ?? null,
            'description' => $row['description'] ?? null,
            'created_at' => $row['created_at'] ?? now(),
            'updated_at' => $row['updated_at'] ?? now(),
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

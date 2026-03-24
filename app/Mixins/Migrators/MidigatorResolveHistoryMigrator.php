<?php

namespace App\Mixins\Migrators;

use App\Repositories\Midigator\MidigatorResolveHistoryRepo;

class MidigatorResolveHistoryMigrator extends AbstractMigrator
{
    protected MidigatorResolveHistoryRepo $repo;

    public function __construct()
    {
        $this->repo = new MidigatorResolveHistoryRepo();
    }

    protected function mapColumns(array $row): array
    {
        return [
            'id' => $row['id'],
            'resolve_id' => $row['resolve_id'],
            'prevention_id' => $row['prevention_id'],
            'user_id' => $row['user_id'] ?? null,
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

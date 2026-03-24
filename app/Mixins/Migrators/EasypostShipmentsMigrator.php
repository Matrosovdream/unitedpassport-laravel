<?php

namespace App\Mixins\Migrators;

use App\Repositories\Easypost\EasypostShipmentRepo;

class EasypostShipmentsMigrator extends AbstractMigrator
{
    protected EasypostShipmentRepo $repo;

    public function __construct()
    {
        $this->repo = new EasypostShipmentRepo();
    }

    protected function mapColumns(array $row): array
    {
        return [
            'id' => $row['id'],
            'easypost_id' => $row['easypost_id'],
            'entry_id' => $row['entry_id'] ?? null,
            'is_return' => $row['is_return'] ?? false,
            'status' => $row['status'] ?? null,
            'tracking_code' => $row['tracking_code'] ?? null,
            'refund_status' => $row['refund_status'] ?? null,
            'mode' => $row['mode'] ?? 'test',
            'tracking_url' => $row['tracking_url'] ?? null,
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

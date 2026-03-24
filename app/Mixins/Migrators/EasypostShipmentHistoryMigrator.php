<?php

namespace App\Mixins\Migrators;

use App\Repositories\Easypost\EasypostShipmentHistoryRepo;

class EasypostShipmentHistoryMigrator extends AbstractMigrator
{
    protected EasypostShipmentHistoryRepo $repo;

    public function __construct()
    {
        $this->repo = new EasypostShipmentHistoryRepo();
    }

    protected function mapColumns(array $row): array
    {
        return [
            'id' => $row['id'],
            'shipment_id' => $row['shipment_id'] ?? null,
            'easypost_shipment_id' => $row['easypost_shipment_id'] ?? null,
            'user_id' => $row['user_id'] ?? null,
            'change_type' => $row['change_type'],
            'description' => $row['description'] ?? null,
            'created_at' => $row['created_at'] ?? now(),
            'updated_at' => $row['created_at'] ?? now(),
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

<?php

namespace App\Mixins\Migrators;

use App\Repositories\Easypost\EasypostShipmentParcelRepo;

class EasypostShipmentParcelsMigrator extends AbstractMigrator
{
    protected EasypostShipmentParcelRepo $repo;

    public function __construct()
    {
        $this->repo = new EasypostShipmentParcelRepo();
    }

    protected function mapColumns(array $row): array
    {
        return [
            'id' => $row['id'],
            'easypost_id' => $row['easypost_id'],
            'entry_id' => $row['entry_id'] ?? null,
            'length' => $row['length'] ?? null,
            'width' => $row['width'] ?? null,
            'height' => $row['height'] ?? null,
            'weight' => $row['weight'] ?? null,
            'easypost_shipment_id' => $row['easypost_shipment_id'],
            'created_at' => now(),
            'updated_at' => now(),
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

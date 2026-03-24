<?php

namespace App\Mixins\Migrators;

use App\Repositories\Easypost\EasypostShipmentAddressRepo;

class EasypostShipmentAddressesMigrator extends AbstractMigrator
{
    protected EasypostShipmentAddressRepo $repo;

    public function __construct()
    {
        $this->repo = new EasypostShipmentAddressRepo();
    }

    protected function mapColumns(array $row): array
    {
        return [
            'id' => $row['id'],
            'easypost_id' => $row['easypost_id'],
            'entry_id' => $row['entry_id'] ?? null,
            'address_type' => $row['address_type'],
            'name' => $row['name'] ?? null,
            'company' => $row['company'] ?? null,
            'street1' => $row['street1'] ?? null,
            'street2' => $row['street2'] ?? null,
            'city' => $row['city'] ?? null,
            'state' => $row['state'] ?? null,
            'zip' => $row['zip'] ?? null,
            'country' => $row['country'] ?? null,
            'phone' => $row['phone'] ?? null,
            'email' => $row['email'] ?? null,
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

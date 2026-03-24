<?php

namespace App\Mixins\Migrators;

use App\Repositories\Easypost\EasypostShipmentRateRepo;

class EasypostShipmentRatesMigrator extends AbstractMigrator
{
    protected EasypostShipmentRateRepo $repo;

    public function __construct()
    {
        $this->repo = new EasypostShipmentRateRepo();
    }

    protected function mapColumns(array $row): array
    {
        return [
            'id' => $row['id'],
            'easypost_id' => $row['easypost_id'],
            'entry_id' => $row['entry_id'] ?? null,
            'mode' => $row['mode'] ?? 'test',
            'service' => $row['service'] ?? null,
            'carrier' => $row['carrier'] ?? null,
            'rate' => $row['rate'] ?? null,
            'currency' => $row['currency'] ?? null,
            'retail_rate' => $row['retail_rate'] ?? null,
            'retail_currency' => $row['retail_currency'] ?? null,
            'list_rate' => $row['list_rate'] ?? null,
            'list_currency' => $row['list_currency'] ?? null,
            'billing_type' => $row['billing_type'] ?? null,
            'delivery_days' => $row['delivery_days'] ?? null,
            'delivery_date' => $row['delivery_date'] ?? null,
            'delivery_date_guaranteed' => $row['delivery_date_guaranteed'] ?? null,
            'est_delivery_days' => $row['est_delivery_days'] ?? null,
            'easypost_shipment_id' => $row['easypost_shipment_id'],
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

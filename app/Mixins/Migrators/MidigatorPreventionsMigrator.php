<?php

namespace App\Mixins\Migrators;

use App\Repositories\Midigator\MidigatorPreventionRepo;

class MidigatorPreventionsMigrator extends AbstractMigrator
{
    protected MidigatorPreventionRepo $repo;

    public function __construct()
    {
        $this->repo = new MidigatorPreventionRepo();
    }

    protected function mapColumns(array $row): array
    {
        return [
            'id' => $row['id'],
            'amount' => $row['amount'] ?? null,
            'arn' => $row['arn'] ?? null,
            'card_brand' => $row['card_brand'] ?? null,
            'card_first_6' => $row['card_first_6'] ?? null,
            'card_last_4' => $row['card_last_4'] ?? null,
            'currency' => $row['currency'] ?? null,
            'merchant_descriptor' => $row['merchant_descriptor'] ?? null,
            'mid' => $row['mid'] ?? null,
            'order_guid' => $row['order_guid'] ?? null,
            'order_id' => $row['order_id'] ?? null,
            'prevention_case_number' => $row['prevention_case_number'] ?? null,
            'prevention_guid' => $row['prevention_guid'],
            'prevention_timestamp' => $row['prevention_timestamp'] ?? null,
            'prevention_type' => $row['prevention_type'] ?? null,
            'transaction_timestamp' => $row['transaction_timestamp'] ?? null,
            'is_resolved' => $row['is_resolved'] ?? false,
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

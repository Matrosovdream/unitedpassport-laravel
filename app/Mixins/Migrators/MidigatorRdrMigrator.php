<?php

namespace App\Mixins\Migrators;

use App\Repositories\Midigator\MidigatorRdrRepo;

class MidigatorRdrMigrator extends AbstractMigrator
{
    protected MidigatorRdrRepo $repo;

    public function __construct()
    {
        $this->repo = new MidigatorRdrRepo();
    }

    protected function mapColumns(array $row): array
    {
        return [
            'id' => $row['id'],
            'amount' => $row['amount'] ?? null,
            'arn' => $row['arn'] ?? null,
            'auth_code' => $row['auth_code'] ?? null,
            'card_first_6' => $row['card_first_6'] ?? null,
            'card_last_4' => $row['card_last_4'] ?? null,
            'currency' => $row['currency'] ?? null,
            'merchant_descriptor' => $row['merchant_descriptor'] ?? null,
            'event_guid' => $row['event_guid'] ?? null,
            'event_timestamp' => $row['event_timestamp'] ?? null,
            'event_type' => $row['event_type'] ?? null,
            'rdr_guid' => $row['rdr_guid'],
            'rdr_case_number' => $row['rdr_case_number'] ?? null,
            'rdr_date' => $row['rdr_date'] ?? null,
            'rdr_resolution' => $row['rdr_resolution'] ?? null,
            'prevention_type' => $row['prevention_type'] ?? null,
            'transaction_date' => $row['transaction_date'] ?? null,
            'order_id' => $row['order_id'] ?? null,
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

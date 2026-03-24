<?php

namespace App\Mixins\Migrators;

use App\Repositories\Payment\PaymentFailedRepo;

class PaymentsFailedMigrator extends AbstractMigrator
{
    protected PaymentFailedRepo $repo;

    public function __construct()
    {
        $this->repo = new PaymentFailedRepo();
    }

    protected function mapColumns(array $row): array
    {
        return [
            'id' => $row['id'],
            'entry_id' => $row['entry_id'],
            'form_id' => $row['form_id'],
            'payment_id' => $row['payment_id'],
            'error_code' => $row['error_code'],
            'error_message' => $row['error_message'],
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

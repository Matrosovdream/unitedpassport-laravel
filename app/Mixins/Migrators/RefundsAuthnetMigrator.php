<?php

namespace App\Mixins\Migrators;

use App\Repositories\Payment\RefundAuthnetRepo;

class RefundsAuthnetMigrator extends AbstractMigrator
{
    protected RefundAuthnetRepo $repo;

    public function __construct()
    {
        $this->repo = new RefundAuthnetRepo();
    }

    protected function mapColumns(array $row): array
    {
        return [
            'id' => $row['id'],
            'sum' => $row['sum'],
            'payment_id' => $row['payment_id'],
            'created_at' => $row['created_at'],
            'updated_at' => $row['created_at'],
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

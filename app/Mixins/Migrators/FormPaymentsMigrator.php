<?php

namespace App\Mixins\Migrators;

use App\Repositories\Payment\FormPaymentRepo;

class FormPaymentsMigrator extends AbstractMigrator
{
    protected FormPaymentRepo $repo;

    public function __construct()
    {
        $this->repo = new FormPaymentRepo();
    }

    protected function mapColumns(array $row): array
    {
        return [
            'id' => $row['id'],
            'receipt_id' => $row['receipt_id'] ?? null,
            'invoice_id' => $row['invoice_id'] ?? null,
            'sub_id' => $row['sub_id'] ?? null,
            'item_id' => $row['item_id'],
            'amount' => $row['amount'] ?? 0,
            'status' => $row['status'] ?? null,
            'begin_date' => $row['begin_date'],
            'expire_date' => $row['expire_date'] ?? null,
            'payment_method' => 1, // authnet default
            'test' => $row['test'] ?? null,
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

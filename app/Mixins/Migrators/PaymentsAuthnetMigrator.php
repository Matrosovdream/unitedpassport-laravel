<?php

namespace App\Mixins\Migrators;

use App\Repositories\Payment\PaymentAuthnetRepo;

class PaymentsAuthnetMigrator extends AbstractMigrator
{
    protected PaymentAuthnetRepo $repo;

    public function __construct()
    {
        $this->repo = new PaymentAuthnetRepo();
    }

    protected function mapColumns(array $row): array
    {
        return [
            'id' => $row['id'],
            'amount' => $row['amount'],
            'payment_id' => $row['payment_id'],
            'invoice_id' => $row['invoice_id'] ?? null,
            'entry_id' => $row['entry_id'],
            'form_id' => $row['form_id'],
            'authnet_login_id' => $row['authnet_login_id'],
            'authnet_transaction_key' => $row['authnet_transaction_key'],
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

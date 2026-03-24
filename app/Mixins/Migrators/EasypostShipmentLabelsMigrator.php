<?php

namespace App\Mixins\Migrators;

use App\Repositories\Easypost\EasypostShipmentLabelRepo;

class EasypostShipmentLabelsMigrator extends AbstractMigrator
{
    protected EasypostShipmentLabelRepo $repo;

    public function __construct()
    {
        $this->repo = new EasypostShipmentLabelRepo();
    }

    protected function mapColumns(array $row): array
    {
        return [
            'id' => $row['id'],
            'easypost_id' => $row['easypost_id'],
            'easypost_shipment_id' => $row['easypost_shipment_id'],
            'entry_id' => $row['entry_id'] ?? null,
            'date_advance' => $row['date_advance'] ?? 0,
            'integrated_form' => $row['integrated_form'] ?? null,
            'label_date' => $row['label_date'] ?? null,
            'label_resolution' => $row['label_resolution'] ?? null,
            'label_size' => $row['label_size'] ?? null,
            'label_type' => $row['label_type'] ?? null,
            'label_file_type' => $row['label_file_type'] ?? null,
            'label_url' => $row['label_url'] ?? null,
            'label_pdf_url' => $row['label_pdf_url'] ?? null,
            'label_zpl_url' => $row['label_zpl_url'] ?? null,
            'label_epl2_url' => $row['label_epl2_url'] ?? null,
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

<?php

namespace App\Mixins\Migrators;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FormItemsMigrator extends AbstractMigrator
{
    protected const STATUS_FIELD_MAP = [
        1 => 7,
        6 => 164,
        12 => 422,
        11 => 277,
        7 => 193,
        4 => 150,
        10 => 259,
    ];

    // Cached lookups
    protected ?array $existingFormIds = null;
    protected ?array $existingFieldIds = null;
    protected ?array $fieldKeyLookup = null;
    protected ?array $existingItemIds = null;

    protected function getExistingFormIds(): array
    {
        if ($this->existingFormIds === null) {
            $this->existingFormIds = DB::table('forms')->pluck('id')->flip()->all();
        }
        return $this->existingFormIds;
    }

    protected function getExistingFieldIds(): array
    {
        if ($this->existingFieldIds === null) {
            $this->existingFieldIds = DB::table('form_fields')->pluck('id')->flip()->all();
        }
        return $this->existingFieldIds;
    }

    protected function getFieldKeyLookup(): array
    {
        if ($this->fieldKeyLookup === null) {
            $oldKeys = DB::table('form_old_keys')->get();
            $oldKeyMap = [];
            foreach ($oldKeys as $ok) {
                $oldKeyMap[$ok->old_field_id] = $ok->new_field_code;
            }

            $this->fieldKeyLookup = [];
            $fields = DB::table('form_fields')->select('id', 'field_key')->get();
            foreach ($fields as $f) {
                $this->fieldKeyLookup[$f->id] = $oldKeyMap[$f->id] ?? $f->field_key;
            }
        }
        return $this->fieldKeyLookup;
    }

    protected function getExistingItemIds(array $ids): array
    {
        return DB::table('form_items')->whereIn('id', $ids)->pluck('id')->flip()->all();
    }

    protected function sanitizeTimestamp(?string $value): ?string
    {
        if (!$value || $value === '0000-00-00 00:00:00') {
            return null;
        }
        return $value;
    }

    protected function mapColumns(array $row): array
    {
        $formId = !empty($row['form_id']) ? (int) $row['form_id'] : null;

        return [
            'id' => $row['id'],
            'item_key' => $row['item_key'] ?? null,
            'name' => $row['name'] ?? null,
            'browser_info' => $row['description'] ?? null,
            'ip' => $row['ip'] ?? null,
            'form_id' => $formId,
            'user_id' => !empty($row['user_id']) ? $row['user_id'] : null,
            'is_draft' => (bool) ($row['is_draft'] ?? false),
            'updated_by' => !empty($row['updated_by']) ? (int) $row['updated_by'] : null,
            'created_at' => $this->sanitizeTimestamp($row['created_at'] ?? null) ?? now(),
            'updated_at' => $this->sanitizeTimestamp($row['updated_at'] ?? null) ?? now(),
        ];
    }

    // Not used in bulk mode, but required by abstract
    protected function migrateRow(array $row): string
    {
        return 'skipped';
    }

    /**
     * Override importPage to process entire page in bulk.
     */
    public function importPage(string $sourceTable, int $page = 1): array
    {
        $result = [
            'table' => $sourceTable,
            'page' => $page,
            'imported' => 0,
            'updated' => 0,
            'skipped' => 0,
            'errors' => [],
        ];

        try {
            $data = $this->fetchPage($sourceTable, $page);
        } catch (\Throwable $e) {
            $result['errors'][] = $e->getMessage();
            return $result;
        }

        $rows = $data['data'] ?? $data['rows'] ?? $data ?? [];

        if (!is_array($rows) || empty($rows)) {
            $result['errors'][] = 'No rows returned';
            return $result;
        }

        $result['total_pages'] = $data['total_pages'] ?? null;
        $result['total_rows'] = $data['total'] ?? null;

        $formIds = $this->getExistingFormIds();
        $fieldIds = $this->getExistingFieldIds();
        $fieldKeys = $this->getFieldKeyLookup();

        // Collect valid items and metas
        $itemBatch = [];
        $metaBatch = [];
        $validItemIds = [];

        foreach ($rows as $row) {
            try {
                $mapped = $this->mapColumns($row);

                if ($mapped['form_id'] && !isset($formIds[$mapped['form_id']])) {
                    $result['skipped']++;
                    continue;
                }

                $itemBatch[] = $mapped;
                $validItemIds[] = $mapped['id'];

                // Collect metas
                if (!empty($row['metas']) && is_array($row['metas'])) {
                    foreach ($row['metas'] as $meta) {
                        $fieldId = (int) ($meta['field_id'] ?? 0);
                        if (!$fieldId || !isset($fieldIds[$fieldId])) {
                            continue;
                        }

                        // Only include metas that belong to this item
                        if (($meta['item_id'] ?? null) != $mapped['id']) {
                            continue;
                        }

                        $createdAt = $this->sanitizeTimestamp($meta['created_at'] ?? null) ?? now();

                        $metaBatch[] = [
                            'id' => $meta['id'],
                            'field_key' => $fieldKeys[$fieldId] ?? null,
                            'meta_value' => $meta['meta_value'] ?? null,
                            'field_id' => $fieldId,
                            'item_id' => $mapped['id'],
                            'created_at' => $createdAt,
                            'updated_at' => $createdAt,
                        ];
                    }
                }
            } catch (\Throwable $e) {
                $result['errors'][] = 'Row ID ' . ($row['id'] ?? '?') . ': ' . $e->getMessage();
                Log::warning('Migration error', ['table' => $sourceTable, 'error' => $e->getMessage()]);
            }
        }

        // Determine created vs updated counts
        if (!empty($validItemIds)) {
            $existingIds = $this->getExistingItemIds($validItemIds);
            $result['imported'] = count($validItemIds) - count($existingIds);
            $result['updated'] = count($existingIds);
        }

        // Bulk upsert items in chunks of 100
        $failedItemIds = [];
        foreach (array_chunk($itemBatch, 100) as $chunk) {
            try {
                DB::table('form_items')->upsert(
                    $chunk,
                    ['id'],
                    ['item_key', 'name', 'browser_info', 'ip', 'form_id', 'user_id', 'is_draft', 'updated_by', 'created_at', 'updated_at']
                );
            } catch (\Throwable $e) {
                $result['errors'][] = 'Items upsert: ' . $e->getMessage();
                Log::warning('Migration items upsert error', ['error' => $e->getMessage()]);
                foreach ($chunk as $item) {
                    $failedItemIds[$item['id']] = true;
                }
            }
        }

        // Filter out metas for failed items
        if (!empty($failedItemIds)) {
            $metaBatch = array_filter($metaBatch, fn($m) => !isset($failedItemIds[$m['item_id']]));
            $metaBatch = array_values($metaBatch);
        }

        // Bulk upsert metas in chunks of 100
        foreach (array_chunk($metaBatch, 100) as $chunk) {
            try {
                DB::table('form_item_metas')->upsert(
                    $chunk,
                    ['id'],
                    ['field_key', 'meta_value', 'field_id', 'item_id', 'created_at', 'updated_at']
                );
            } catch (\Throwable $e) {
                $result['errors'][] = 'Metas upsert: ' . $e->getMessage();
                Log::warning('Migration metas upsert error', ['error' => $e->getMessage()]);
            }
        }

        return $result;
    }

    public function postImport(): void
    {
        // Set status_id on items based on status field meta values
        $allStatuses = DB::table('form_statuses')->get();
        $statusLookup = [];
        foreach ($allStatuses as $s) {
            $statusLookup[$s->form_id][strtolower(trim($s->value))] = $s->id;
        }

        foreach (self::STATUS_FIELD_MAP as $formId => $fieldId) {
            if (!isset($statusLookup[$formId])) {
                continue;
            }

            $metas = DB::table('form_item_metas as m')
                ->join('form_items as i', 'i.id', '=', 'm.item_id')
                ->where('m.field_id', $fieldId)
                ->where('i.form_id', $formId)
                ->select('m.item_id', 'm.meta_value')
                ->get();

            foreach ($metas as $meta) {
                $value = strtolower(trim($meta->meta_value ?? ''));

                if ($value === '' || !isset($statusLookup[$formId][$value])) {
                    continue;
                }

                DB::table('form_items')
                    ->where('id', $meta->item_id)
                    ->update(['status_id' => $statusLookup[$formId][$value]]);
            }
        }
    }
}

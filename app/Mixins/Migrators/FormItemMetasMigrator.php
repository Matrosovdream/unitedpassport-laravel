<?php

namespace App\Mixins\Migrators;

use App\Repositories\Form\FormItemMetaRepo;
use Illuminate\Support\Facades\DB;

class FormItemMetasMigrator extends AbstractMigrator
{
    protected FormItemMetaRepo $repo;

    /**
     * Maps form_id => field_id that holds the status value.
     */
    protected const STATUS_FIELD_MAP = [
        1 => 7,
        6 => 164,
        12 => 422,
        11 => 277,
        7 => 193,
        4 => 150,
        10 => 259,
    ];

    public function __construct()
    {
        $this->repo = new FormItemMetaRepo();
    }

    protected function mapColumns(array $row): array
    {
        return [
            'id' => $row['id'],
            'meta_value' => $row['meta_value'] ?? null,
            'field_id' => $row['field_id'],
            'item_id' => $row['item_id'],
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

    public function postImport(): void
    {
        // Set field_key on all metas: use form_old_keys override, fallback to form_fields.field_key
        $oldKeys = DB::table('form_old_keys')->get();
        $oldKeyLookup = [];
        foreach ($oldKeys as $ok) {
            $oldKeyLookup[$ok->old_field_id] = $ok->new_field_code;
        }

        $fields = DB::table('form_fields')->select('id', 'field_key')->get();
        $fieldKeyLookup = [];
        foreach ($fields as $f) {
            $fieldKeyLookup[$f->id] = $oldKeyLookup[$f->id] ?? $f->field_key;
        }

        // Batch update field_key grouped by value
        $byKey = [];
        foreach ($fieldKeyLookup as $fieldId => $key) {
            if ($key) {
                $byKey[$key][] = $fieldId;
            }
        }
        foreach ($byKey as $key => $fieldIds) {
            DB::table('form_item_metas')
                ->whereIn('field_id', $fieldIds)
                ->update(['field_key' => $key]);
        }

        // Build a lookup of form_statuses: [form_id => [lowercase_value => status_id]]
        $allStatuses = DB::table('form_statuses')->get();
        $statusLookup = [];
        foreach ($allStatuses as $s) {
            $statusLookup[$s->form_id][strtolower(trim($s->value))] = $s->id;
        }

        foreach (self::STATUS_FIELD_MAP as $formId => $fieldId) {
            if (!isset($statusLookup[$formId])) {
                continue;
            }

            // Get all metas for this status field, joined with items to confirm form_id
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

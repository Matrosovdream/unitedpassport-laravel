<?php

namespace App\Mixins\Migrators;

use App\Repositories\Form\FormFieldRepo;
use Illuminate\Support\Facades\DB;

class FormFieldsMigrator extends AbstractMigrator
{
    protected FormFieldRepo $repo;

    public function __construct()
    {
        $this->repo = new FormFieldRepo();
    }

    protected function mapColumns(array $row): array
    {
        return [
            'id' => $row['id'],
            'field_key' => $row['field_key'] ?? null,
            'name' => $row['name'] ?? null,
            'description' => $row['description'] ?? null,
            'type' => $row['type'] ?? null,
            'default_value' => $row['default_value'] ?? null,
            'options' => $row['options'] ?? null,
            'field_order' => $row['field_order'] ?? 0,
            'page_num' => 1,
            'required' => $row['required'] ?? null,
            'field_options' => null,
            'form_id' => $row['form_id'] ?? null,
            'created_at' => $row['created_at'],
            'updated_at' => $row['created_at'],
        ];
    }

    protected function migrateRow(array $row): string
    {
        $mapped = $this->mapColumns($row);

        $formId = $mapped['form_id'] ? (int) $mapped['form_id'] : null;
        $mapped['form_id'] = $formId;

        if ($formId && !DB::table('forms')->where('id', $formId)->exists()) {
            return 'skipped';
        }

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
        $formIds = DB::table('form_fields')->distinct()->pluck('form_id')->filter();

        foreach ($formIds as $formId) {
            $fields = DB::table('form_fields')
                ->where('form_id', $formId)
                ->orderBy('field_order')
                ->get(['id', 'type']);

            $pageNum = 1;
            $pages = [];

            foreach ($fields as $field) {
                if ($field->type === 'break') {
                    $pageNum++;
                }

                $pages[$pageNum][] = $field->id;
            }

            foreach ($pages as $page => $ids) {
                DB::table('form_fields')
                    ->whereIn('id', $ids)
                    ->update(['page_num' => $page]);
            }
        }

        // Rename field_key based on form_old_keys mappings
        $oldKeys = DB::table('form_old_keys')->get();

        foreach ($oldKeys as $mapping) {
            DB::table('form_fields')
                ->where('id', $mapping->old_field_id)
                ->where('form_id', $mapping->form_id)
                ->update(['field_key' => $mapping->new_field_code]);
        }
    }
}

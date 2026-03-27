<?php

namespace Database\Seeders;

use App\Models\Form;
use App\Models\FormStatus;
use Illuminate\Database\Seeder;

class FormStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = self::getStatuses();

        foreach ($statuses as $formId => $formStatuses) {
            if (!Form::find($formId)) {
                continue;
            }
            foreach ($formStatuses as $status) {
                FormStatus::updateOrCreate(
                    ['form_id' => $formId, 'code' => $status['code']],
                    ['value' => $status['value'], 'description' => $status['description'] ?? null, 'color' => $status['color'] ?? null]
                );
            }
        }
    }

    public static function seedForForm(int $formId): void
    {
        $statuses = self::getStatuses();

        if (!isset($statuses[$formId]) || !Form::find($formId)) {
            return;
        }

        foreach ($statuses[$formId] as $status) {
            FormStatus::updateOrCreate(
                ['form_id' => $formId, 'code' => $status['code']],
                ['value' => $status['value'], 'description' => $status['description'] ?? null, 'color' => $status['color'] ?? null]
            );
        }
    }

    public static function getStatuses(): array
    {
        return [
            1 => [
                ['code' => 'failed', 'value' => 'Failed', 'color' => '#dc2626'],
                ['code' => 'processing', 'value' => 'Processing', 'color' => '#2563eb'],
                ['code' => 'processing-e', 'value' => 'Processing-E', 'color' => '#2563eb'],
                ['code' => 'processing-na', 'value' => 'Processing-NA', 'color' => '#2563eb'],
                ['code' => 'processing-x', 'value' => 'Processing-X', 'color' => '#2563eb'],
                ['code' => 'verified', 'value' => 'Verified', 'color' => '#059669'],
                ['code' => 'submitted', 'value' => 'Submitted', 'color' => '#7c3aed'],
                ['code' => 'submitted-c', 'value' => 'Submitted-C', 'color' => '#7c3aed'],
                ['code' => 'submitted-m', 'value' => 'Submitted-M', 'color' => '#7c3aed'],
                ['code' => 'mailed', 'value' => 'Mailed', 'color' => '#0891b2'],
                ['code' => 'on-hold', 'value' => 'On Hold', 'color' => '#d97706'],
                ['code' => 'on-hold-l', 'value' => 'On Hold-L', 'color' => '#d97706'],
                ['code' => 'on-hold-r', 'value' => 'On Hold-R', 'color' => '#d97706'],
                ['code' => 'issue', 'value' => 'Issue', 'color' => '#e11d48'],
                ['code' => 'refunded', 'value' => 'Refunded', 'color' => '#6b7280'],
                ['code' => 'complete', 'value' => 'Complete', 'color' => '#16a34a'],
                ['code' => 'complete-na', 'value' => 'Complete-NA', 'color' => '#16a34a'],
                ['code' => 'complete-m', 'value' => 'Complete-M', 'color' => '#16a34a'],
            ],
            6 => [
                ['code' => 'failed', 'value' => 'Failed', 'color' => '#dc2626'],
                ['code' => 'processing', 'value' => 'Processing', 'color' => '#2563eb'],
                ['code' => 'on-hold', 'value' => 'On Hold', 'color' => '#d97706'],
                ['code' => 'refunded', 'value' => 'Refunded', 'color' => '#6b7280'],
                ['code' => 'complete', 'value' => 'Complete', 'color' => '#16a34a'],
            ],
            12 => [
                ['code' => 'processing', 'value' => 'Processing', 'color' => '#2563eb'],
                ['code' => 'on-hold', 'value' => 'On Hold', 'color' => '#d97706'],
                ['code' => 'denied', 'value' => 'Denied', 'color' => '#dc2626'],
                ['code' => 'approved', 'value' => 'Approved', 'color' => '#16a34a'],
            ],
            11 => [
                ['code' => 'processing', 'value' => 'Processing', 'color' => '#2563eb'],
                ['code' => 'approved', 'value' => 'Approved', 'color' => '#16a34a'],
                ['code' => 'denied', 'value' => 'Denied', 'color' => '#dc2626'],
            ],
            7 => [
                ['code' => 'processing', 'value' => 'Processing', 'color' => '#2563eb'],
                ['code' => 'fix-photo', 'value' => 'Fix Photo', 'color' => '#d97706'],
                ['code' => 'on-hold', 'value' => 'On Hold', 'color' => '#d97706'],
                ['code' => 'approved', 'value' => 'Approved', 'color' => '#16a34a'],
                ['code' => 'denied', 'value' => 'Denied', 'color' => '#dc2626'],
            ],
            4 => [
                ['code' => 'processing', 'value' => 'Processing', 'color' => '#2563eb'],
                ['code' => 'on-hold', 'value' => 'On Hold', 'color' => '#d97706'],
                ['code' => 'issue', 'value' => 'Issue', 'color' => '#e11d48'],
                ['code' => 'complete', 'value' => 'Complete', 'color' => '#16a34a'],
            ],
            10 => [
                ['code' => 'processing', 'value' => 'Processing', 'color' => '#2563eb'],
                ['code' => 'on-hold', 'value' => 'On Hold', 'color' => '#d97706'],
                ['code' => 'completed', 'value' => 'Completed', 'color' => '#16a34a'],
            ],
        ];
    }
}

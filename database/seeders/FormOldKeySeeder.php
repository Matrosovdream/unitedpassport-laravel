<?php

namespace Database\Seeders;

use App\Models\Form;
use App\Models\FormOldKey;
use Illuminate\Database\Seeder;

class FormOldKeySeeder extends Seeder
{
    public function run(): void
    {
        $keys = self::getKeys();

        foreach ($keys as $formId => $mappings) {
            if (!Form::find($formId)) {
                continue;
            }
            foreach ($mappings as $oldFieldId => $newFieldCode) {
                FormOldKey::updateOrCreate(
                    ['form_id' => $formId, 'old_field_id' => $oldFieldId],
                    ['new_field_code' => $newFieldCode]
                );
            }
        }
    }

    public static function seedForForm(int $formId): void
    {
        $keys = self::getKeys();

        if (!isset($keys[$formId]) || !Form::find($formId)) {
            return;
        }

        foreach ($keys[$formId] as $oldFieldId => $newFieldCode) {
            FormOldKey::updateOrCreate(
                ['form_id' => $formId, 'old_field_id' => $oldFieldId],
                ['new_field_code' => $newFieldCode]
            );
        }
    }

    public static function getKeys(): array
    {
        return [
            1 => [
                7 => 'status',
                386 => 'application_status',
                5 => 'notes',
                344 => 'tracking_number',
                354 => 'carrier',
                12 => 'passport_type',
                4 => 'primary_contact_email',
                1 => 'firstname',
                2 => 'lastname',
                3 => 'middlename',
                232 => 'birthday',
                19 => 'social_security_number',
            ],
            6 => [
                164 => 'status',
            ],
            12 => [
                422 => 'status',
            ],
            11 => [
                277 => 'status',
            ],
            7 => [
                193 => 'status',
            ],
            4 => [
                150 => 'status',
            ],
            10 => [
                259 => 'status',
            ],
        ];
    }
}

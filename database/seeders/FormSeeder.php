<?php

namespace Database\Seeders;

use App\Models\Form;
use App\Models\FormField;
use Illuminate\Database\Seeder;

class FormSeeder extends Seeder
{
    public function run(): void
    {
        $form = Form::where('form_key', 'passport-application')->first();

        if (!$form) {
            $form = Form::create([
                'form_key' => 'passport-application',
                'name' => 'Passport Application',
                'description' => 'Apply for a U.S. passport online.',
                'status' => 'active',
                'logged_in' => false,
                'editable' => true,
                'is_template' => false,
            ]);
        } else {
            $form->update([
                'name' => 'Passport Application',
                'description' => 'Apply for a U.S. passport online.',
                'status' => 'active',
                'logged_in' => false,
                'editable' => true,
                'is_template' => false,
            ]);
        }

        // Delete existing fields to avoid duplicates on re-seed
        $form->fields()->delete();

        $fields = [
            // Page 1 — Application Type
            ['name' => 'Passport Type', 'field_key' => 'passport_type', 'type' => 'select', 'page_num' => 1, 'field_order' => 1, 'required' => 1, 'options' => json_encode(['New Adult Passport', 'Passport Renewal', 'Lost Passport', 'Stolen Passport', 'Child Passport', 'Damaged Passport', 'Name Change', 'Second Passport'])],
            ['name' => 'Primary Contact Email', 'field_key' => 'email', 'type' => 'email', 'page_num' => 1, 'field_order' => 2, 'required' => 1],

            // Page 2 — Personal Information
            ['name' => 'First Name', 'field_key' => 'first_name', 'type' => 'text', 'page_num' => 2, 'field_order' => 1, 'required' => 1],
            ['name' => 'Last Name', 'field_key' => 'last_name', 'type' => 'text', 'page_num' => 2, 'field_order' => 2, 'required' => 1],
            ['name' => 'Middle Name', 'field_key' => 'middle_name', 'type' => 'text', 'page_num' => 2, 'field_order' => 3, 'required' => 0],
            ['name' => 'Date of Birth', 'field_key' => 'dob', 'type' => 'date', 'page_num' => 2, 'field_order' => 4, 'required' => 1],
            ['name' => 'Sex', 'field_key' => 'sex', 'type' => 'select', 'page_num' => 2, 'field_order' => 5, 'required' => 1, 'options' => json_encode(['Male', 'Female', 'X (Unspecified)'])],
            ['name' => 'Social Security Number', 'field_key' => 'ssn', 'type' => 'text', 'page_num' => 2, 'field_order' => 6, 'required' => 0],
            ['name' => 'Place of Birth (City)', 'field_key' => 'birth_city', 'type' => 'text', 'page_num' => 2, 'field_order' => 7, 'required' => 1],
            ['name' => 'State / Country of Birth', 'field_key' => 'birth_state', 'type' => 'text', 'page_num' => 2, 'field_order' => 8, 'required' => 1],

            // Page 3 — Address & Contact
            ['name' => 'Street Address', 'field_key' => 'street', 'type' => 'text', 'page_num' => 3, 'field_order' => 1, 'required' => 1],
            ['name' => 'Apartment / Suite', 'field_key' => 'apt', 'type' => 'text', 'page_num' => 3, 'field_order' => 2, 'required' => 0],
            ['name' => 'City', 'field_key' => 'city', 'type' => 'text', 'page_num' => 3, 'field_order' => 3, 'required' => 1],
            ['name' => 'State', 'field_key' => 'state', 'type' => 'select', 'page_num' => 3, 'field_order' => 4, 'required' => 1, 'options' => json_encode(['Alabama','Alaska','Arizona','Arkansas','California','Colorado','Connecticut','Delaware','Florida','Georgia','Hawaii','Idaho','Illinois','Indiana','Iowa','Kansas','Kentucky','Louisiana','Maine','Maryland','Massachusetts','Michigan','Minnesota','Mississippi','Missouri','Montana','Nebraska','Nevada','New Hampshire','New Jersey','New Mexico','New York','North Carolina','North Dakota','Ohio','Oklahoma','Oregon','Pennsylvania','Rhode Island','South Carolina','South Dakota','Tennessee','Texas','Utah','Vermont','Virginia','Washington','West Virginia','Wisconsin','Wyoming','District of Columbia'])],
            ['name' => 'ZIP Code', 'field_key' => 'zip', 'type' => 'text', 'page_num' => 3, 'field_order' => 5, 'required' => 1],
            ['name' => 'Phone Number', 'field_key' => 'phone', 'type' => 'phone', 'page_num' => 3, 'field_order' => 6, 'required' => 1],

            // Page 4 — Travel Plans
            ['name' => 'Planned Travel Date', 'field_key' => 'travel_date', 'type' => 'date', 'page_num' => 4, 'field_order' => 1, 'required' => 0],
            ['name' => 'Destination Country', 'field_key' => 'destination', 'type' => 'text', 'page_num' => 4, 'field_order' => 2, 'required' => 0],
            ['name' => 'Return Date', 'field_key' => 'return_date', 'type' => 'date', 'page_num' => 4, 'field_order' => 3, 'required' => 0],
            ['name' => 'Emergency Contact Name', 'field_key' => 'emergency_name', 'type' => 'text', 'page_num' => 4, 'field_order' => 4, 'required' => 0],
            ['name' => 'Emergency Contact Phone', 'field_key' => 'emergency_phone', 'type' => 'phone', 'page_num' => 4, 'field_order' => 5, 'required' => 0],
            ['name' => 'Relationship', 'field_key' => 'emergency_relation', 'type' => 'text', 'page_num' => 4, 'field_order' => 6, 'required' => 0],

            // Page 5 — Documents
            ['name' => 'Passport Photo', 'field_key' => 'passport_photo', 'type' => 'file', 'page_num' => 5, 'field_order' => 1, 'required' => 0, 'description' => 'Upload a recent 2x2 inch color photo with a white background. JPG or PNG, max 10MB.'],
            ['name' => 'Supporting Documents', 'field_key' => 'supporting_docs', 'type' => 'file', 'page_num' => 5, 'field_order' => 2, 'required' => 0, 'description' => 'Upload any required documents (birth certificate, previous passport, legal name change documents, etc.)'],

            // Page 6 — Processing Speed
            ['name' => 'Processing Speed', 'field_key' => 'processing_speed', 'type' => 'select', 'page_num' => 6, 'field_order' => 1, 'required' => 1, 'options' => json_encode(['Standard (4-6 weeks) — $199', 'Rush (2-3 weeks) — $299', 'Super Rush (5-7 business days) — $449'])],
        ];

        foreach ($fields as $field) {
            $form->fields()->create($field);
        }
    }
}

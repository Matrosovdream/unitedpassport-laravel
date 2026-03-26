<?php

namespace App\Http\Actions\Api\V1\Form;

use App\Services\Forms\FormService;
use Illuminate\Http\JsonResponse;

class GetPublicFormAction
{
    public function __construct(
        protected FormService $formService,
    ) {}

    public function handle(string $formKey): JsonResponse
    {
        $form = $this->formService->getByKey($formKey);

        if (!$form) {
            return response()->json(['message' => 'Form not found'], 404);
        }

        $fields = $this->formService->getFieldsByFormId($form['id'])
            ->map(fn($field) => collect($field)->except('Model'));

        return response()->json([
            'form' => [
                'id' => $form['id'],
                'form_key' => $form['form_key'],
                'name' => $form['name'],
                'description' => $form['description'],
                'logged_in' => $form['logged_in'],
                'editable' => $form['editable'],
                'status' => $form['status'],
                'options' => $form['options'],
            ],
            'fields' => $fields,
        ]);
    }
}

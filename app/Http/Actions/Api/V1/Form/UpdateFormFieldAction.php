<?php

namespace App\Http\Actions\Api\V1\Form;

use App\Services\Forms\FormService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UpdateFormFieldAction
{
    public function __construct(
        protected FormService $formService,
    ) {}

    public function handle(Request $request, int $fieldId): JsonResponse
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'field_key' => ['sometimes', 'string', 'max:100'],
            'type' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'default_value' => ['nullable', 'string'],
            'options' => ['nullable', 'string'],
            'field_order' => ['nullable', 'integer'],
            'page_num' => ['nullable', 'integer', 'min:1'],
            'required' => ['nullable', 'integer'],
            'field_options' => ['nullable', 'string'],
        ]);

        $field = $this->formService->updateField($fieldId, $data);

        if (!$field) {
            return response()->json(['message' => 'Field not found'], 404);
        }

        return response()->json([
            'message' => 'Field updated',
            'field' => collect($field)->except('Model'),
        ]);
    }
}

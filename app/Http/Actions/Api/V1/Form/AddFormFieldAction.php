<?php

namespace App\Http\Actions\Api\V1\Form;

use App\Services\Forms\FormService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddFormFieldAction
{
    public function __construct(
        protected FormService $formService,
    ) {}

    public function handle(Request $request, int $formId): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'field_key' => ['nullable', 'string', 'max:100'],
            'type' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'default_value' => ['nullable', 'string'],
            'options' => ['nullable', 'string'],
            'field_order' => ['nullable', 'integer'],
            'page_num' => ['nullable', 'integer', 'min:1'],
            'required' => ['nullable', 'integer'],
            'field_options' => ['nullable', 'string'],
        ]);

        $field = $this->formService->addField($formId, $data);

        return response()->json([
            'message' => 'Field added',
            'field' => collect($field)->except('Model'),
        ], 201);
    }
}

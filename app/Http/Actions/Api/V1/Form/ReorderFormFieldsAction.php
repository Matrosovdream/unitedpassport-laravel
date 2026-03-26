<?php

namespace App\Http\Actions\Api\V1\Form;

use App\Services\Forms\FormService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReorderFormFieldsAction
{
    public function __construct(
        protected FormService $formService,
    ) {}

    public function handle(Request $request, int $formId): JsonResponse
    {
        $data = $request->validate([
            'fields' => ['required', 'array'],
            'fields.*.id' => ['required', 'integer', 'exists:form_fields,id'],
            'fields.*.field_order' => ['required', 'integer'],
            'fields.*.page_num' => ['required', 'integer', 'min:1'],
        ]);

        $this->formService->reorderFields($formId, $data['fields']);

        return response()->json(['message' => 'Fields reordered']);
    }
}

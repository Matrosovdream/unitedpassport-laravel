<?php

namespace App\Http\Actions\Api\V1\Form;

use App\Services\Forms\FormService;
use Illuminate\Http\JsonResponse;

class DeleteFormFieldAction
{
    public function __construct(
        protected FormService $formService,
    ) {}

    public function handle(int $fieldId): JsonResponse
    {
        $deleted = $this->formService->deleteField($fieldId);

        if (!$deleted) {
            return response()->json(['message' => 'Field not found'], 404);
        }

        return response()->json(['message' => 'Field deleted']);
    }
}

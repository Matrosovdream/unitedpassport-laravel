<?php

namespace App\Http\Actions\Api\V1\Form;

use App\Services\Forms\FormService;
use Illuminate\Http\JsonResponse;

class GetFormAction
{
    public function __construct(
        protected FormService $formService,
    ) {}

    public function handle(int $id): JsonResponse
    {
        $form = $this->formService->getFormWithFields($id);

        if (!$form) {
            return response()->json(['message' => 'Form not found'], 404);
        }

        $form = collect($form)->except('Model');

        return response()->json([
            'form' => $form,
        ]);
    }
}

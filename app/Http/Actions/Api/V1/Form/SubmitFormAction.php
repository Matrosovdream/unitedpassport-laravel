<?php

namespace App\Http\Actions\Api\V1\Form;

use App\Services\Forms\FormService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubmitFormAction
{
    public function __construct(
        protected FormService $formService,
    ) {}

    public function handle(Request $request, string $formKey): JsonResponse
    {
        $form = $this->formService->getByKey($formKey);

        if (!$form) {
            return response()->json(['message' => 'Form not found'], 404);
        }

        if ($form['logged_in'] && !$request->user()) {
            return response()->json(['message' => 'Authentication required'], 401);
        }

        $data = $request->validate([
            'values' => ['required', 'array'],
            'values.*' => ['nullable', 'string'],
        ]);

        $item = $this->formService->submitForm(
            $form['id'],
            $data['values'],
            $request->user()?->id,
            $request->ip(),
        );

        return response()->json([
            'message' => 'Form submitted',
            'item_key' => $item['item_key'],
        ], 201);
    }
}

<?php

namespace App\Http\Actions\Api\V1\Form;

use App\Services\Forms\FormService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UpdateFormAction
{
    public function __construct(
        protected FormService $formService,
    ) {}

    public function handle(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'form_key' => ['sometimes', 'string', 'max:100', 'unique:forms,form_key,' . $id],
            'description' => ['nullable', 'string'],
            'logged_in' => ['nullable', 'boolean'],
            'editable' => ['nullable', 'boolean'],
            'is_template' => ['nullable', 'boolean'],
            'status' => ['nullable', 'string', 'max:255'],
            'options' => ['nullable', 'string'],
        ]);

        $form = $this->formService->updateForm($id, $data);

        if (!$form) {
            return response()->json(['message' => 'Form not found'], 404);
        }

        return response()->json([
            'message' => 'Form updated',
            'form' => collect($form)->except('Model'),
        ]);
    }
}

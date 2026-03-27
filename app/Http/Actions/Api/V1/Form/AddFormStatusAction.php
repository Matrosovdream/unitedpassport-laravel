<?php

namespace App\Http\Actions\Api\V1\Form;

use App\Models\FormStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddFormStatusAction
{
    public function handle(Request $request, int $formId): JsonResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:100'],
            'value' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:50'],
        ]);

        $data['form_id'] = $formId;

        $status = FormStatus::create($data);

        return response()->json(['status' => $status], 201);
    }
}

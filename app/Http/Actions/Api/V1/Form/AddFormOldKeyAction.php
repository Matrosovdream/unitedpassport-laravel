<?php

namespace App\Http\Actions\Api\V1\Form;

use App\Models\FormOldKey;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddFormOldKeyAction
{
    public function handle(Request $request, int $formId): JsonResponse
    {
        $data = $request->validate([
            'old_field_id' => ['required', 'integer'],
            'new_field_code' => ['required', 'string', 'max:100'],
        ]);

        $data['form_id'] = $formId;

        $key = FormOldKey::create($data);

        return response()->json(['old_key' => $key], 201);
    }
}

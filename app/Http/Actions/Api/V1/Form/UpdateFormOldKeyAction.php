<?php

namespace App\Http\Actions\Api\V1\Form;

use App\Models\FormOldKey;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UpdateFormOldKeyAction
{
    public function handle(Request $request, int $id): JsonResponse
    {
        $key = FormOldKey::find($id);

        if (!$key) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $data = $request->validate([
            'old_field_id' => ['sometimes', 'integer'],
            'new_field_code' => ['sometimes', 'string', 'max:100'],
        ]);

        $key->update($data);

        return response()->json(['old_key' => $key->fresh()]);
    }
}

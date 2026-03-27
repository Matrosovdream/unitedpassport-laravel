<?php

namespace App\Http\Actions\Api\V1\Form;

use App\Models\FormOldKey;
use Illuminate\Http\JsonResponse;

class DeleteFormOldKeyAction
{
    public function handle(int $id): JsonResponse
    {
        $key = FormOldKey::find($id);

        if (!$key) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $key->delete();

        return response()->json(['message' => 'Deleted']);
    }
}

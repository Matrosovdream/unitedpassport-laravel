<?php

namespace App\Http\Actions\Api\V1\Form;

use App\Models\FormStatus;
use Illuminate\Http\JsonResponse;

class DeleteFormStatusAction
{
    public function handle(int $statusId): JsonResponse
    {
        $status = FormStatus::find($statusId);

        if (!$status) {
            return response()->json(['message' => 'Status not found'], 404);
        }

        if ($status->items()->exists()) {
            return response()->json([
                'message' => 'Cannot delete status: there are active items using it.',
            ], 422);
        }

        $status->delete();

        return response()->json(['message' => 'Status deleted']);
    }
}

<?php

namespace App\Http\Actions\Api\V1\Form;

use App\Models\FormStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UpdateFormStatusAction
{
    public function handle(Request $request, int $statusId): JsonResponse
    {
        $status = FormStatus::find($statusId);

        if (!$status) {
            return response()->json(['message' => 'Status not found'], 404);
        }

        $data = $request->validate([
            'code' => ['sometimes', 'string', 'max:100'],
            'value' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:50'],
        ]);

        $status->update($data);

        return response()->json(['status' => $status->fresh()]);
    }
}

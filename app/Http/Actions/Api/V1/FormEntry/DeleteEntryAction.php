<?php

namespace App\Http\Actions\Api\V1\FormEntry;

use App\Services\Forms\FormService;
use Illuminate\Http\JsonResponse;

class DeleteEntryAction
{
    public function __construct(
        protected FormService $formService,
    ) {}

    public function handle(int $id): JsonResponse
    {
        $deleted = $this->formService->deleteEntry($id);

        if (!$deleted) {
            return response()->json(['message' => 'Entry not found'], 404);
        }

        return response()->json(['message' => 'Entry deleted']);
    }
}

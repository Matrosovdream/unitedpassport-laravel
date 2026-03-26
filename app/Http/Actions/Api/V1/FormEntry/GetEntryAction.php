<?php

namespace App\Http\Actions\Api\V1\FormEntry;

use App\Services\Forms\FormService;
use Illuminate\Http\JsonResponse;

class GetEntryAction
{
    public function __construct(
        protected FormService $formService,
    ) {}

    public function handle(int $id): JsonResponse
    {
        $entry = $this->formService->getEntry($id);

        if (!$entry) {
            return response()->json(['message' => 'Entry not found'], 404);
        }

        return response()->json([
            'entry' => collect($entry)->except('Model'),
        ]);
    }
}

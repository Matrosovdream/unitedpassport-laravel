<?php

namespace App\Http\Actions\Api\V1\FormEntry;

use App\Services\Forms\FormService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UpdateEntryAction
{
    public function __construct(
        protected FormService $formService,
    ) {}

    public function handle(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'values' => ['required', 'array'],
            'values.*' => ['nullable', 'string'],
        ]);

        $entry = $this->formService->updateEntry($id, $data['values']);

        if (!$entry) {
            return response()->json(['message' => 'Entry not found'], 404);
        }

        return response()->json([
            'entry' => collect($entry)->except('Model'),
        ]);
    }
}

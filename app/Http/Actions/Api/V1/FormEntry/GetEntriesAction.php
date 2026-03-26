<?php

namespace App\Http\Actions\Api\V1\FormEntry;

use App\Services\Forms\FormService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetEntriesAction
{
    public function __construct(
        protected FormService $formService,
    ) {}

    public function handle(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 20);
        $sorting = $request->input('sort_field')
            ? [$request->input('sort_field') => $request->input('sort_order', 'desc')]
            : [];

        $filter = array_filter([
            'search'   => $request->input('search'),
            'form_id'  => $request->input('form_id'),
            'entry_id' => $request->input('entry_id'),
            'date_from' => $request->input('date_from'),
            'date_to'   => $request->input('date_to'),
        ], fn($v) => $v !== null && $v !== '');

        if ($request->has('is_draft') && $request->input('is_draft') !== '') {
            $filter['is_draft'] = $request->input('is_draft');
        }

        $result = $this->formService->getEntries($filter, $perPage, $sorting);
        $paginator = $result['Model'];
        $items = $result['items']->map(fn($item) => collect($item)->except('Model'));

        return response()->json([
            'entries' => $items,
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }
}

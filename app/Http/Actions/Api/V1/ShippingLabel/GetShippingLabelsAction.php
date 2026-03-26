<?php

namespace App\Http\Actions\Api\V1\ShippingLabel;

use App\Repositories\Easypost\EasypostShipmentRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetShippingLabelsAction
{
    public function __construct(
        protected EasypostShipmentRepo $repo,
    ) {}

    public function handle(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 20);
        $sorting = $request->input('sort_field')
            ? [$request->input('sort_field') => $request->input('sort_order', 'desc')]
            : [];

        $filter = array_filter([
            'search'    => $request->input('search'),
            'entry_id'  => $request->input('entry_id'),
            'status'    => $request->input('status'),
            'mode'      => $request->input('mode'),
            'date_from' => $request->input('date_from'),
            'date_to'   => $request->input('date_to'),
        ], fn($v) => $v !== null && $v !== '');

        if ($request->has('is_return') && $request->input('is_return') !== '') {
            $filter['is_return'] = $request->input('is_return');
        }

        $result = $this->repo->search($filter, $perPage, $sorting);
        $paginator = $result['Model'];
        $items = $result['items']->map(fn($item) => collect($item)->except('Model'));

        return response()->json([
            'labels' => $items,
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
            ],
        ]);
    }
}

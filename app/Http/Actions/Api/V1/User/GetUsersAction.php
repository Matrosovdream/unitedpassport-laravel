<?php

namespace App\Http\Actions\Api\V1\User;

use App\Services\Users\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetUsersAction
{
    public function __construct(
        protected UserService $userService,
    ) {}

    public function handle(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 20);
        $sorting = $request->input('sort_field')
            ? [$request->input('sort_field') => $request->input('sort_order', 'asc')]
            : [];

        $result = $this->userService->getAll([], $perPage, $sorting);
        $paginator = $result['Model'];
        $items = $result['items']->map(fn($item) => collect($item)->except('Model'));

        return response()->json([
            'users' => $items,
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }
}

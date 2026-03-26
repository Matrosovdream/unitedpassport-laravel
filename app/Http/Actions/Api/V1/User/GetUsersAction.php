<?php

namespace App\Http\Actions\Api\V1\User;

use App\Services\Users\UserService;
use Illuminate\Http\JsonResponse;

class GetUsersAction
{
    public function __construct(
        protected UserService $userService,
    ) {}

    public function handle(): JsonResponse
    {
        $result = $this->userService->getAll();
        $users = $result['items']->map(fn($item) => collect($item)->except('Model'));

        return response()->json(['users' => $users]);
    }
}

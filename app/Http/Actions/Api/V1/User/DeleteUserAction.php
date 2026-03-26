<?php

namespace App\Http\Actions\Api\V1\User;

use App\Services\Users\UserService;
use Illuminate\Http\JsonResponse;

class DeleteUserAction
{
    public function __construct(
        protected UserService $userService,
    ) {}

    public function handle(int $id): JsonResponse
    {
        $deleted = $this->userService->deleteUser($id);

        if (!$deleted) {
            return response()->json(['message' => 'User not found or cannot be deleted.'], 403);
        }

        return response()->json(['message' => 'User deleted.']);
    }
}

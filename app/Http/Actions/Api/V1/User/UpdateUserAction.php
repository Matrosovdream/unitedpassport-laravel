<?php

namespace App\Http\Actions\Api\V1\User;

use App\Services\Users\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class UpdateUserAction
{
    public function __construct(
        protected UserService $userService,
    ) {}

    public function handle(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'role_id' => ['nullable', 'exists:user_roles,id'],
            'user_status' => ['nullable', 'integer'],
        ]);

        $user = $this->userService->updateUser($id, $request->only([
            'name', 'password', 'role_id', 'user_status',
        ]));

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        return response()->json(['user' => $user]);
    }
}

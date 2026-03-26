<?php

namespace App\Http\Actions\Api\V1\User;

use App\Services\Users\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class AddUserAction
{
    public function __construct(
        protected UserService $userService,
    ) {}

    public function handle(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role_id' => ['nullable', 'exists:user_roles,id'],
            'user_status' => ['nullable', 'integer'],
        ]);

        $user = $this->userService->addUser($request->only([
            'name', 'email', 'password', 'role_id', 'user_status',
        ]));

        return response()->json(['user' => $user], 201);
    }
}

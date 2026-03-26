<?php

namespace App\Http\Actions\Api\V1\User;

use App\Services\Users\UserRoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddUserRoleAction
{
    public function __construct(
        protected UserRoleService $roleService,
    ) {}

    public function handle(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['required', 'string', 'max:100', 'unique:user_roles,slug'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'rights' => ['nullable', 'array'],
            'rights.*' => ['string'],
        ]);

        $role = $this->roleService->addRole(
            $request->only(['name', 'slug', 'description', 'is_active']),
            $request->input('rights', []),
        );

        return response()->json(['role' => $role], 201);
    }
}

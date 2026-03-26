<?php

namespace App\Http\Actions\Api\V1\User;

use App\Services\Users\UserRoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UpdateUserRoleAction
{
    public function __construct(
        protected UserRoleService $roleService,
    ) {}

    public function handle(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'name' => ['sometimes', 'string', 'max:100'],
            'slug' => ['sometimes', 'string', 'max:100', 'unique:user_roles,slug,' . $id],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'rights' => ['nullable', 'array'],
            'rights.*' => ['string'],
        ]);

        $role = $this->roleService->updateRole(
            $id,
            $request->only(['name', 'slug', 'description', 'is_active']),
            $request->input('rights', []),
        );

        if (!$role) {
            return response()->json(['message' => 'Role not found or not editable.'], 403);
        }

        return response()->json(['role' => $role]);
    }
}

<?php

namespace App\Http\Actions\Api\V1\User;

use App\Services\Users\UserRoleService;
use Illuminate\Http\JsonResponse;

class DeleteUserRoleAction
{
    public function __construct(
        protected UserRoleService $roleService,
    ) {}

    public function handle(int $id): JsonResponse
    {
        $deleted = $this->roleService->deleteRole($id);

        if (!$deleted) {
            return response()->json(['message' => 'Role not found or cannot be deleted.'], 403);
        }

        return response()->json(['message' => 'Role deleted.']);
    }
}

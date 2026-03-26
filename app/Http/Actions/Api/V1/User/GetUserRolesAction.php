<?php

namespace App\Http\Actions\Api\V1\User;

use App\Services\Users\UserRoleService;
use Illuminate\Http\JsonResponse;

class GetUserRolesAction
{
    public function __construct(
        protected UserRoleService $roleService,
    ) {}

    public function handle(): JsonResponse
    {
        $result = $this->roleService->getAll();
        $roles = $result['items']->map(fn($item) => collect($item)->except('Model'));

        return response()->json([
            'roles' => $roles,
            'available_rights' => $this->roleService->getAllRights(),
        ]);
    }
}

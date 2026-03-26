<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Actions\Api\V1\User\AddUserAction;
use App\Http\Actions\Api\V1\User\AddUserRoleAction;
use App\Http\Actions\Api\V1\User\DeleteUserAction;
use App\Http\Actions\Api\V1\User\DeleteUserRoleAction;
use App\Http\Actions\Api\V1\User\GetUserRolesAction;
use App\Http\Actions\Api\V1\User\GetUsersAction;
use App\Http\Actions\Api\V1\User\UpdateUserAction;
use App\Http\Actions\Api\V1\User\UpdateUserRoleAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request, GetUsersAction $action): JsonResponse
    {
        return $action->handle($request);
    }

    public function store(Request $request, AddUserAction $action): JsonResponse
    {
        return $action->handle($request);
    }

    public function update(Request $request, int $id, UpdateUserAction $action): JsonResponse
    {
        return $action->handle($request, $id);
    }

    public function destroy(int $id, DeleteUserAction $action): JsonResponse
    {
        return $action->handle($id);
    }

    public function roles(Request $request, GetUserRolesAction $action): JsonResponse
    {
        return $action->handle($request);
    }

    public function storeRole(Request $request, AddUserRoleAction $action): JsonResponse
    {
        return $action->handle($request);
    }

    public function updateRole(Request $request, int $id, UpdateUserRoleAction $action): JsonResponse
    {
        return $action->handle($request, $id);
    }

    public function destroyRole(int $id, DeleteUserRoleAction $action): JsonResponse
    {
        return $action->handle($id);
    }
}

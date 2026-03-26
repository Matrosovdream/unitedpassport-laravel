<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Actions\Api\V1\Auth\GetUserAction;
use App\Http\Actions\Api\V1\Auth\LoginAction;
use App\Http\Actions\Api\V1\Auth\LogoutAction;
use App\Http\Actions\Api\V1\Auth\RegisterAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request, RegisterAction $action): JsonResponse
    {
        return $action->handle($request);
    }

    public function login(Request $request, LoginAction $action): JsonResponse
    {
        return $action->handle($request);
    }

    public function logout(Request $request, LogoutAction $action): JsonResponse
    {
        return $action->handle($request);
    }

    public function user(Request $request, GetUserAction $action): JsonResponse
    {
        return $action->handle($request);
    }
}

<?php

namespace App\Http\Actions\Api\V1\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetUserAction
{
    public function handle(Request $request): JsonResponse
    {
        return response()->json(['user' => $request->user()]);
    }
}

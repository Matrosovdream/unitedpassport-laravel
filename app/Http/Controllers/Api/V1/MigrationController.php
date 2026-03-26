<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Actions\Api\V1\Migration\GetStatusAction;
use App\Http\Actions\Api\V1\Migration\GetTablesAction;
use App\Http\Actions\Api\V1\Migration\ImportAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MigrationController extends Controller
{
    public function tables(GetTablesAction $action): JsonResponse
    {
        return $action->handle();
    }

    public function import(Request $request, ImportAction $action): JsonResponse
    {
        return $action->handle($request);
    }

    public function status(GetStatusAction $action): JsonResponse
    {
        return $action->handle();
    }
}

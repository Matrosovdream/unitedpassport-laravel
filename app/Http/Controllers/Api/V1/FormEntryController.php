<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Actions\Api\V1\FormEntry\DeleteEntryAction;
use App\Http\Actions\Api\V1\FormEntry\GetEntriesAction;
use App\Http\Actions\Api\V1\FormEntry\GetEntryAction;
use App\Http\Actions\Api\V1\FormEntry\UpdateEntryAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FormEntryController extends Controller
{
    public function index(Request $request, GetEntriesAction $action): JsonResponse
    {
        return $action->handle($request);
    }

    public function show(int $id, GetEntryAction $action): JsonResponse
    {
        return $action->handle($id);
    }

    public function update(Request $request, int $id, UpdateEntryAction $action): JsonResponse
    {
        return $action->handle($request, $id);
    }

    public function destroy(int $id, DeleteEntryAction $action): JsonResponse
    {
        return $action->handle($id);
    }
}

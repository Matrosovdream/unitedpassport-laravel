<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Actions\Api\V1\ShippingLabel\GetShippingLabelAction;
use App\Http\Actions\Api\V1\ShippingLabel\GetShippingLabelsAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShippingLabelController extends Controller
{
    public function index(Request $request, GetShippingLabelsAction $action): JsonResponse
    {
        return $action->handle($request);
    }

    public function show(int $id, GetShippingLabelAction $action): JsonResponse
    {
        return $action->handle($id);
    }
}

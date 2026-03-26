<?php

namespace App\Http\Actions\Api\V1\ShippingLabel;

use App\Repositories\Easypost\EasypostShipmentRepo;
use Illuminate\Http\JsonResponse;

class GetShippingLabelAction
{
    public function __construct(
        protected EasypostShipmentRepo $repo,
    ) {}

    public function handle(int $id): JsonResponse
    {
        $item = $this->repo->getByID($id);

        if (!$item) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json([
            'label' => collect($item)->except('Model'),
        ]);
    }
}

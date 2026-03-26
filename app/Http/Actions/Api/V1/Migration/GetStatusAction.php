<?php

namespace App\Http\Actions\Api\V1\Migration;

use App\Models\MigrationJob;
use Illuminate\Http\JsonResponse;

class GetStatusAction
{
    public function handle(): JsonResponse
    {
        $jobs = MigrationJob::orderBy('id', 'desc')
            ->get()
            ->groupBy('table_key')
            ->map(fn($group) => $group->first());

        return response()->json(['jobs' => $jobs->values()]);
    }
}

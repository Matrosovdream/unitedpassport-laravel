<?php

namespace App\Http\Actions\Api\V1\Migration;

use App\Models\MigrationJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StopAction
{
    public function handle(Request $request): JsonResponse
    {
        $request->validate([
            'table' => ['required', 'string'],
        ]);

        $job = MigrationJob::where('table_key', $request->input('table'))
            ->whereIn('status', ['pending', 'running'])
            ->latest()
            ->first();

        if (!$job) {
            return response()->json(['message' => 'No active job found for this table'], 404);
        }

        $job->update([
            'status' => 'failed',
            'errors' => array_merge($job->errors ?? [], ['Stopped manually by admin']),
            'completed_at' => now(),
        ]);

        return response()->json(['message' => 'Migration stopped', 'job' => $job->fresh()]);
    }
}

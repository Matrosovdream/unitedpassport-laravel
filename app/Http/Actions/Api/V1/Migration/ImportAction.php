<?php

namespace App\Http\Actions\Api\V1\Migration;

use App\Jobs\RunMigrationImport;
use App\Mixins\Migrators\MigrationConfig;
use App\Models\MigrationJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ImportAction
{
    public function handle(Request $request): JsonResponse
    {
        $request->validate([
            'table' => 'required|string',
            'source_url' => 'nullable|string',
            'source_password' => 'nullable|string',
            'per_page' => 'nullable|integer|min:1|max:1000',
            'max_load' => 'nullable|integer|min:1',
        ]);

        $tableKey = $request->input('table');
        $tables = MigrationConfig::getTables();

        if (!isset($tables[$tableKey])) {
            return response()->json(['message' => 'Unknown table: ' . $tableKey], 422);
        }

        $running = MigrationJob::where('table_key', $tableKey)
            ->whereIn('status', ['pending', 'running'])
            ->first();

        if ($running) {
            return response()->json(['message' => 'Import already in progress for this table.'], 409);
        }

        $job = MigrationJob::create([
            'table_key' => $tableKey,
            'status' => 'pending',
            'source_url' => $request->input('source_url') ?: MigrationConfig::getBaseUrl(),
            'source_password' => $request->input('source_password') ?: MigrationConfig::getPassword(),
            'per_page' => $request->input('per_page', 100),
            'max_load' => $request->input('max_load', 1000),
        ]);

        RunMigrationImport::dispatch($job->id)->onQueue('default');

        return response()->json(['job' => $job]);
    }
}

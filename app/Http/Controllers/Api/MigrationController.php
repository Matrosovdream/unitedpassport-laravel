<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\RunMigrationImport;
use App\Mixins\Migrators\MigrationConfig;
use App\Models\MigrationJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MigrationController extends Controller
{
    public function tables(): JsonResponse
    {
        $tables = [];

        foreach (MigrationConfig::getTables() as $key => $config) {
            $tables[] = [
                'key' => $key,
                'order' => $config['order'],
                'label' => $config['label'],
                'local_table' => $config['local_table'],
            ];
        }

        return response()->json([
            'tables' => $tables,
            'config' => [
                'source_url' => MigrationConfig::getBaseUrl(),
                'source_password' => MigrationConfig::getPassword(),
            ],
        ]);
    }

    public function import(Request $request): JsonResponse
    {
        $request->validate([
            'table' => 'required|string',
            'source_url' => 'nullable|string',
            'source_password' => 'nullable|string',
        ]);

        $tableKey = $request->input('table');
        $tables = MigrationConfig::getTables();

        if (!isset($tables[$tableKey])) {
            return response()->json(['message' => 'Unknown table: ' . $tableKey], 422);
        }

        // Check if already running
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
        ]);

        RunMigrationImport::dispatch($job->id)->onQueue('default');

        return response()->json(['job' => $job]);
    }

    public function status(): JsonResponse
    {
        $jobs = MigrationJob::orderBy('id', 'desc')
            ->get()
            ->groupBy('table_key')
            ->map(fn($group) => $group->first());

        return response()->json(['jobs' => $jobs->values()]);
    }
}

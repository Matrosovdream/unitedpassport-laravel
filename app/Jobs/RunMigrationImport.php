<?php

namespace App\Jobs;

use App\Mixins\Migrators\MigrationConfig;
use App\Models\MigrationJob;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RunMigrationImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 3600;
    public int $tries = 1;

    public function __construct(
        protected int $migrationJobId
    ) {}

    public function handle(): void
    {
        $job = MigrationJob::find($this->migrationJobId);

        if (!$job) {
            return;
        }

        $tables = MigrationConfig::getTables();

        if (!isset($tables[$job->table_key])) {
            $job->update(['status' => 'failed', 'errors' => ['Unknown table: ' . $job->table_key]]);
            return;
        }

        // Set runtime config from the saved values
        if ($job->source_url) {
            MigrationConfig::setBaseUrl($job->source_url);
        }
        if ($job->source_password) {
            MigrationConfig::setPassword($job->source_password);
        }

        $migratorClass = $tables[$job->table_key]['migrator'];
        $migrator = new $migratorClass();

        $job->update([
            'status' => 'running',
            'started_at' => now(),
        ]);

        $page = 1;
        $allErrors = [];

        try {
            while (true) {
                $result = $migrator->importPage($job->table_key, $page);

                $allErrors = array_merge($allErrors, $result['errors'] ?? []);

                $job->update([
                    'current_page' => $page,
                    'total_pages' => $result['total_pages'] ?? $job->total_pages,
                    'total_rows' => $result['total_rows'] ?? $job->total_rows,
                    'imported' => $job->imported + ($result['imported'] ?? 0),
                    'updated' => $job->updated + ($result['updated'] ?? 0),
                    'skipped' => $job->skipped + ($result['skipped'] ?? 0),
                    'errors' => array_slice($allErrors, -50), // keep last 50 errors
                ]);

                // Refresh model to get updated counters
                $job->refresh();

                // Stop if no more pages
                $totalPages = $result['total_pages'] ?? null;
                if ($totalPages !== null && $page >= $totalPages) {
                    break;
                }

                // Stop if the page returned no rows (no pagination info from source)
                $rowCount = ($result['imported'] ?? 0) + ($result['updated'] ?? 0) + ($result['skipped'] ?? 0);
                if ($rowCount === 0) {
                    break;
                }

                $page++;
            }

            $job->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Migration job failed', [
                'job_id' => $this->migrationJobId,
                'table' => $job->table_key,
                'page' => $page,
                'error' => $e->getMessage(),
            ]);

            $allErrors[] = 'Fatal: ' . $e->getMessage();

            $job->update([
                'status' => 'failed',
                'errors' => array_slice($allErrors, -50),
                'completed_at' => now(),
            ]);
        }
    }
}

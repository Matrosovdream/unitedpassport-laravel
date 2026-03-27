<?php

namespace App\Jobs;

use App\Mixins\Migrators\MigrationConfig;
use App\Models\MigrationJob;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
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

        // Clear cached code so the queue worker always uses the latest
        Artisan::call('optimize:clear');

        $migratorClass = $tables[$job->table_key]['migrator'];
        $migrator = new $migratorClass();
        $migrator->setPerPage($job->per_page ?? 100);

        $maxLoad = $job->max_load ?? 1000;
        $totalLoaded = 0;

        $job->update([
            'status' => 'running',
            'started_at' => now(),
        ]);

        $page = 1;
        $allErrors = [];

        try {
            while (true) {
                $result = $migrator->importPage($job->table_key, $page);

                // Keep only the last 50 errors to avoid memory buildup
                foreach ($result['errors'] ?? [] as $err) {
                    $allErrors[] = $err;
                    if (count($allErrors) > 50) {
                        array_shift($allErrors);
                    }
                }

                $job->update([
                    'current_page' => $page,
                    'total_pages' => $result['total_pages'] ?? $job->total_pages,
                    'total_rows' => $result['total_rows'] ?? $job->total_rows,
                    'imported' => $job->imported + ($result['imported'] ?? 0),
                    'updated' => $job->updated + ($result['updated'] ?? 0),
                    'skipped' => $job->skipped + ($result['skipped'] ?? 0),
                    'errors' => $allErrors,
                ]);

                // Extract values before freeing memory
                $totalPages = $result['total_pages'] ?? null;
                $rowCount = ($result['imported'] ?? 0) + ($result['updated'] ?? 0) + ($result['skipped'] ?? 0);
                unset($result);

                // Refresh model to get updated counters and check if stopped
                $job->refresh();

                if ($job->status === 'failed') {
                    break;
                }

                // Stop if no more pages
                if ($totalPages !== null && $page >= $totalPages) {
                    break;
                }

                // Stop if the page returned no rows (no pagination info from source)
                if ($rowCount === 0) {
                    break;
                }

                $totalLoaded += $rowCount;
                if ($totalLoaded >= $maxLoad) {
                    break;
                }

                $page++;

                // Delay between pages to avoid overwhelming the source
                sleep(2);
            }

            $migrator->postImport();

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

<?php

namespace App\Mixins\Migrators;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

abstract class AbstractMigrator
{
    /**
     * Fetch rows from the source WP site.
     */
    public function fetchPage(string $table, int $page = 1): array
    {
        $url = MigrationConfig::getBaseUrl()
            . '/wp-json/wp-data-migration/v1/export?'
            . http_build_query([
                'password' => MigrationConfig::getPassword(),
                'table' => $table,
                'page' => $page,
            ]);

        $response = Http::timeout(60)->get($url);

        if (!$response->successful()) {
            throw new \RuntimeException("Failed to fetch {$table} page {$page}: HTTP {$response->status()}");
        }

        return $response->json();
    }

    /**
     * Import a single page of data for the given source table.
     * Returns summary of what happened.
     */
    public function importPage(string $sourceTable, int $page = 1): array
    {
        $result = [
            'table' => $sourceTable,
            'page' => $page,
            'imported' => 0,
            'updated' => 0,
            'skipped' => 0,
            'errors' => [],
        ];

        try {
            $data = $this->fetchPage($sourceTable, $page);
        } catch (\Throwable $e) {
            $result['errors'][] = $e->getMessage();
            return $result;
        }

        $rows = $data['data'] ?? $data['rows'] ?? $data ?? [];

        if (!is_array($rows) || empty($rows)) {
            $result['errors'][] = 'No rows returned';
            return $result;
        }

        // If response wraps rows, extract pagination info
        $result['total_pages'] = $data['total_pages'] ?? null;
        $result['total_rows'] = $data['total'] ?? null;

        foreach ($rows as $row) {
            try {
                $status = $this->migrateRow($row);

                if ($status === 'created') {
                    $result['imported']++;
                } elseif ($status === 'updated') {
                    $result['updated']++;
                } else {
                    $result['skipped']++;
                }
            } catch (\Throwable $e) {
                $result['errors'][] = 'Row ID ' . ($row['id'] ?? $row['ID'] ?? '?') . ': ' . $e->getMessage();
                Log::warning('Migration error', [
                    'table' => $sourceTable,
                    'row' => $row,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $result;
    }

    /**
     * Migrate a single row. Return 'created', 'updated', or 'skipped'.
     */
    abstract protected function migrateRow(array $row): string;

    /**
     * Map source row columns to local columns.
     */
    abstract protected function mapColumns(array $row): array;
}

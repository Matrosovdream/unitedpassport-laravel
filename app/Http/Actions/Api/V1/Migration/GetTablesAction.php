<?php

namespace App\Http\Actions\Api\V1\Migration;

use App\Mixins\Migrators\MigrationConfig;
use Illuminate\Http\JsonResponse;

class GetTablesAction
{
    public function handle(): JsonResponse
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
}

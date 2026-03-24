<?php

namespace App\Mixins\Migrators;

use App\Repositories\User\UserRepo;

class UsersMigrator extends AbstractMigrator
{
    protected UserRepo $repo;

    public function __construct()
    {
        $this->repo = new UserRepo();
    }

    protected function mapColumns(array $row): array
    {
        return [
            'id' => $row['ID'],
            'legacy_id' => $row['ID'],
            'name' => $row['display_name'] ?: $row['user_login'],
            'email' => $row['user_email'],
            'password' => $row['user_pass'],
            'user_login' => $row['user_login'],
            'display_name' => $row['display_name'],
            'user_url' => $row['user_url'] ?? '',
            'user_status' => $row['user_status'] ?? 0,
            'created_at' => $row['user_registered'],
            'updated_at' => $row['user_registered'],
        ];
    }

    protected function migrateRow(array $row): string
    {
        // Skip ID 1 — reserved for local admin
        if (($row['ID'] ?? null) == 1) {
            return 'skipped';
        }

        $mapped = $this->mapColumns($row);
        $existing = $this->repo->getByID($mapped['id']);

        if ($existing) {
            $this->repo->update($mapped['id'], $mapped);
            return 'updated';
        }

        $this->repo->getModel()->forceCreate($mapped);
        return 'created';
    }
}

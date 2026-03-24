<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MigrationJob extends Model
{
    protected $fillable = [
        'table_key', 'status', 'current_page', 'total_pages', 'total_rows',
        'imported', 'updated', 'skipped', 'errors',
        'source_url', 'source_password', 'started_at', 'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'errors' => 'array',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }
}

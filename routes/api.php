<?php

/**
 * API routes for the SPA application.
 * Prefix: /api/v{n}
 *
 * Route files are loaded from routes/api/v{n}/ directories.
 */

use Illuminate\Support\Facades\Route;

// V1 routes (current)
Route::prefix('v1')->group(function () {
    foreach (glob(__DIR__ . '/api/v1/*.php') as $file) {
        require $file;
    }
});

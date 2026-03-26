<?php

use App\Http\Controllers\Api\V1\MigrationController;
use Illuminate\Support\Facades\Route;

Route::get('/migration/tables', [MigrationController::class, 'tables']);
Route::get('/migration/status', [MigrationController::class, 'status']);
Route::post('/migration/import', [MigrationController::class, 'import']);

<?php

use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);

Route::get('/user-roles', [UserController::class, 'roles']);
Route::post('/user-roles', [UserController::class, 'storeRole']);
Route::put('/user-roles/{id}', [UserController::class, 'updateRole']);
Route::delete('/user-roles/{id}', [UserController::class, 'destroyRole']);

<?php

use App\Http\Controllers\Api\V1\FormController;
use App\Http\Controllers\Api\V1\FormEntryController;
use Illuminate\Support\Facades\Route;

// Public form endpoints
Route::get('/public/forms/{formKey}', [FormController::class, 'publicShow']);
Route::post('/public/forms/{formKey}/submit', [FormController::class, 'submit']);

Route::get('/forms', [FormController::class, 'index']);
Route::get('/forms/{id}', [FormController::class, 'show']);
Route::put('/forms/{id}', [FormController::class, 'update']);

Route::post('/forms/{formId}/fields', [FormController::class, 'addField']);
Route::put('/form-fields/{fieldId}', [FormController::class, 'updateField']);
Route::delete('/form-fields/{fieldId}', [FormController::class, 'deleteField']);
Route::post('/forms/{formId}/fields/reorder', [FormController::class, 'reorderFields']);

Route::post('/forms/{formId}/statuses', [FormController::class, 'addStatus']);
Route::put('/form-statuses/{statusId}', [FormController::class, 'updateStatus']);
Route::delete('/form-statuses/{statusId}', [FormController::class, 'deleteStatus']);

Route::get('/form-entries', [FormEntryController::class, 'index']);
Route::get('/form-entries/{id}', [FormEntryController::class, 'show']);
Route::put('/form-entries/{id}', [FormEntryController::class, 'update']);
Route::delete('/form-entries/{id}', [FormEntryController::class, 'destroy']);

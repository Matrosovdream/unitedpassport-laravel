<?php

use App\Http\Controllers\Api\V1\ShippingLabelController;
use Illuminate\Support\Facades\Route;

Route::get('/shipping-labels', [ShippingLabelController::class, 'index']);
Route::get('/shipping-labels/{id}', [ShippingLabelController::class, 'show']);

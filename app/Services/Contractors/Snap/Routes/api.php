<?php

use App\Services\Contractors\Snap\Controllers\SnapFoodController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->prefix('api')->group(function () {
    Route::post('/SnapFood/SendOrder/Daily/{storeId}', [SnapFoodController::class, 'processOrder']);
});


<?php

use App\Services\Contractors\FoodSnap\Controllers\FoodSnapController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->prefix('api')->group(function () {
    Route::post('/FoodSnap/SendOrder/Daily/{storeId}', [FoodSnapController::class, 'processOrder']);
});


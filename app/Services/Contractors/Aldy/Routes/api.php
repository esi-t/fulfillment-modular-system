<?php

use App\Services\Contractors\Aldy\Controllers\AldyController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->prefix('api')->group(function () {
    Route::post('/Aldy/SendOrder/Daily/{storeId}', [AldyController::class, 'processOrder']);
    Route::put('/Aldy/Order/Daily/{orderCode}/reject', [AldyController::class, 'changeStatus']);
});


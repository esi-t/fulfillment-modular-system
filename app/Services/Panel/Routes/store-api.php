<?php

use App\Services\Panel\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->prefix('api')->group(function () {
    Route::middleware('auth:sanctum')->prefix('store')->name('store.')->group(function () {
        Route::prefix('orders')->name('orders.')->group(function () {
            // endpoits ...
        });
    });
});

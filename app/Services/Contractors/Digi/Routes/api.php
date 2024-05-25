<?php

use App\Services\Contractors\Digi\Controllers\DigiController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->prefix('api/v1')->group(function () {
    Route::post('/Digi/SendOrder/Daily', [DigiController::class, 'processOrder']);
});


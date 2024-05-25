<?php

use App\Services\Authentication\Controllers\AuthenticationController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->prefix('api')->group(function () {
    Route::prefix('store')->name('store.')->group(function () {
        Route::post('login', [AuthenticationController::class, 'login'])->name('login');
        Route::middleware('auth:sanctum')->post('logout', [AuthenticationController::class, 'logout'])->name('logout');
    });

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::post('login', [AuthenticationController::class, 'adminLogin'])->name('login');
        Route::middleware(['auth:sanctum', 'ability:admin'])->post('logout', [AuthenticationController::class, 'logout'])->name('logout');
    });
});


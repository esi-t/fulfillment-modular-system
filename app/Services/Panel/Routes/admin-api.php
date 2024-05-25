<?php

use App\Services\Panel\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->prefix('api')->group(function () {
    Route::middleware(['auth:sanctum', 'ability:admin'])->prefix('admin')->name('admin.')->group(function () {

        // other endpoint ...

        Route::middleware(['auth:sanctum', 'ability:admin'])->prefix('users')->name('users.')->group(function () {
            Route::get('index', [AdminController::class, 'index'])->name('index');
            Route::post('', [AdminController::class, 'createUser'])->name('create');
            Route::patch('', [AdminController::class, 'updateUser'])->name('update');
            Route::delete('{user}', [AdminController::class, 'deleteUser'])->name('delete');
            Route::get('{user}', [AdminController::class, 'getUser'])->name('user');
        });
    });
});

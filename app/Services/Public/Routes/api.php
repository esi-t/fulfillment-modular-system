<?php

use App\Services\Panel\Controllers\AdminController;
use App\Services\Public\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->prefix('api/public')->name('public.')->group(function () {
        Route::put('invoice/{order}/update', [PublicController::class, 'updateInvoice'])->name('invoice.update');
});

<?php

namespace App\Services\Contractors\Aldy\Providers;

use Illuminate\Support\ServiceProvider;

class AldyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
         $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');
    }
}

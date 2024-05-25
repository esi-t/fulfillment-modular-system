<?php

namespace App\Services\Contractors\Base\Providers;

use App\Services\Contractors\Aldy\Providers\AldyServiceProvider;
use App\Services\Contractors\Digi\Providers\DigiServiceProvider;
use App\Services\Contractors\FoodSnap\Providers\FoodSnapServiceProvider;
use App\Services\Contractors\Snap\Providers\SnapServiceProvider;
use Illuminate\Support\ServiceProvider;

class ContractorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->register(SnapServiceProvider::class);
        $this->app->register(AldyServiceProvider::class);
        $this->app->register(DigiServiceProvider::class);
        $this->app->register(FoodSnapServiceProvider::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/migrations');
    }
}

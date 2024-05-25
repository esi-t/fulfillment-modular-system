<?php

namespace App\Services\Panel\Providers;

use App\Services\Panel\Repositories\AdminRepository;
use App\Services\Panel\Repositories\AdminServiceInterface;
use App\Services\Panel\Repositories\StoreRepository;
use App\Services\Panel\Repositories\StoreServiceInterface;
use Illuminate\Support\ServiceProvider;

class PanelServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(StoreServiceInterface::class, StoreRepository::class);
        $this->app->bind(AdminServiceInterface::class, AdminRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin-api.php');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/store-api.php');
        $this->loadTranslationsFrom(__DIR__ . '/../Lang', 'panel');
    }
}

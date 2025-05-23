<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/api.php', 'api'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config/api.php' => config_path('api.php'),
        ], 'api-config');
    }
} 
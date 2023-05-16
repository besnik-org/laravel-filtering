<?php
declare(strict_types=1);

namespace Besnik\LaravelFiltering;

use Illuminate\Support\ServiceProvider;

class LaravelFilteringServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        //   $this->loadViewsFrom(__DIR__.'/resources/views', 'besnikFilter');

        $this->publishes([
            __DIR__.'/config/laravel-filtering.php' => config_path('laravel-filtering.php'),
        ], 'besnik-filtering-config');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/laravel-filtering.php',
            'laravel-filtering'
        );
    }
}

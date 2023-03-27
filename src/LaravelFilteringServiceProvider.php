<?php

namespace Besnik\LaravelFiltering;

use Illuminate\Support\ServiceProvider;

class LaravelFilteringServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'besnikFilter');

        $this->publishes([
            __DIR__.'/config/laravel-filtering.php' => config_path('laravel-filtering.php'),
        ]);
    }

    public function register()
    {

    }

}
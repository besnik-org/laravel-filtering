<?php
declare(strict_types=1);

namespace Besnik\LaravelInertiaCrud;

use Besnik\LaravelInertiaCrud\Commands\PublishCommand;
use Illuminate\Support\ServiceProvider;

class LaravelInertiaCrudServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'inertiaCrudView');

        $this->offerPublishing();

        $this->registerCommands();
    }

    protected function offerPublishing()
    {
        if ($this->app->runningInConsole()) {
//            $this->publishes([
//                __DIR__.'/' => config_path('horizon.php'),
//            ], 'horizon-config');
//
            $this->publishes([
                __DIR__.'/public/assets' => public_path('vendor/besnik-crud'),
            ], ['inertia-crud-assets', 'laravel-assets']);
        }
    }

    /**
     * Register the Horizon Artisan commands.
     *
     * @return void
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                PublishCommand::class,
            ]);
        }
    }

    public function register() {}
}

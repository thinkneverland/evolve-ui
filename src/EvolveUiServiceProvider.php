<?php

namespace Thinkneverland\Evolve\UI;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class EvolveUiServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerConfig();
        $this->registerRoutes();
        $this->registerViews();
        $this->registerPublishing();
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/evolve-ui.php', 'evolve-ui'
        );
    }

    protected function registerConfig()
    {
        $this->publishes([
            __DIR__ . '/../config/evolve-ui.php' => config_path('evolve-ui.php'),
        ], 'evolve-ui-config');
    }

    protected function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        });
    }

    protected function routeConfiguration()
    {
        return [
            'prefix' => trim(config('evolve-ui.prefix'), '/'),
            'middleware' => config('evolve-ui.middleware', ['web', 'auth']),
        ];
    }

    protected function registerViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'evolve-ui');
    }

    protected function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/evolve-ui'),
            ], 'evolve-ui-views');
        }
    }
}

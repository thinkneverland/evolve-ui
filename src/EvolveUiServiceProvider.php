<?php

namespace Thinkneverland\EvolveUi;

use Illuminate\Support\ServiceProvider;

class EvolveUiServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'thinkneverland');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'thinkneverland');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/evolve-ui.php', 'evolve-ui');

        // Register the service the package provides.
        $this->app->singleton('evolve-ui', function ($app) {
            return new EvolveUi;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['evolve-ui'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/evolve-ui.php' => config_path('evolve-ui.php'),
        ], 'evolve-ui.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/thinkneverland'),
        ], 'evolve-ui.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/thinkneverland'),
        ], 'evolve-ui.assets');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/thinkneverland'),
        ], 'evolve-ui.lang');*/

        // Registering package commands.
        // $this->commands([]);
    }
}

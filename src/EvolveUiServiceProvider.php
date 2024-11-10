<?php

namespace Evolve\UI;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class EvolveUiServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerRoutes();
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'evolve-ui');
    }

    protected function registerRoutes()
    {
        Route::group([
            'middleware' => ['web', 'auth'],
        ], function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        });
    }
}

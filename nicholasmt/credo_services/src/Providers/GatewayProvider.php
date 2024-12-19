<?php

namespace CorexTech\CredoServices\Providers;

use Illuminate\Support\ServiceProvider;

class GatewayProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadControllersFrom(__DIR__.'/../Controllers/CredoController.php');
    }
}
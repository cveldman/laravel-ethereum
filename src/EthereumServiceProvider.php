<?php

namespace Appbakkers\Ethereum;

use Illuminate\Support\ServiceProvider;

class EthereumServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/ethereum.php', 'ethereum');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }
        //
    }

}
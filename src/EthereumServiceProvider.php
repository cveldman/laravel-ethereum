<?php

namespace Appbakkers\Ethereum;

use Illuminate\Support\ServiceProvider;

class EthereumServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/ethereum.php', 'ethereum');
        $this->mergeConfigFrom(__DIR__.'/../config/contract.php', 'contract');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/ethereum.php' => config_path('ethereum.php'),
                __DIR__.'/../config/contract.php' => config_path('contract.php')
            ], 'ethereum-config');

            $this->publishes([
                __DIR__.'/../database/migrations/' => database_path('migrations')
            ], 'ethereum-migrations');
        }
    }
}

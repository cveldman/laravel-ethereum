<?php

namespace Appbakkers\Ethereum\Tests;

use Appbakkers\Ethereum\EthereumServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            EthereumServiceProvider::class
        ];
    }

    /* protected function getEnvironmentSetUp($app)
    {
        $app['migrator']->path(__DIR__.'/../database/migrations');

    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->loadLaravelMigrations();
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->artisan('migrate')->run();

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    } */
}
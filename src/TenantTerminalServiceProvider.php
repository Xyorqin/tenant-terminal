<?php

namespace Blaze\TenantTerminal;

use Blaze\TenantTerminal\Commands\TenantCommand;
use Illuminate\Support\ServiceProvider;

class TenantTerminalServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Merge package configuration
        $this->mergeConfigFrom(
            __DIR__.'/../config/tenant-terminal.php',
            'tenant-terminal'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish configuration file
        $this->publishes([
            __DIR__.'/../config/tenant-terminal.php' => config_path('tenant-terminal.php'),
        ], 'tenant-terminal-config');

        // Register the tenant command
        if ($this->app->runningInConsole()) {
            $this->commands([
                TenantCommand::class,
            ]);
        }
    }
}

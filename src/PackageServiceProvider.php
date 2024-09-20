<?php

namespace KenNebula\KBZPaymentIntegration;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use KenNebula\KBZPaymentIntegration\KBZ;

class PackageServiceProvider extends ServiceProvider
{
    public function register() : void 
    {
        // Bind the KBZ class to the service container
        $this->app->singleton(KBZ::class, function($app) {
            return new KBZ();
        });
    }

    public function boot() : void 
    {
        if ($this->app->runningInConsole()) {
            // Example: Publishing configuration file
            $this->publishes([
              __DIR__.'/config/config.php' => config_path('kbz.php'),
          ], 'config');
        
          }
    }

}
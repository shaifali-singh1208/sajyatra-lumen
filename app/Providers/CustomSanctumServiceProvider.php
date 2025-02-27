<?php

namespace App\Providers;

use Laravel\Sanctum\Sanctum;
use Laravel\Sanctum\SanctumServiceProvider as BaseSanctumServiceProvider;

class CustomSanctumServiceProvider extends BaseSanctumServiceProvider
{
    public function register()
    {
        // Manually load Sanctum configuration
        $this->app->configure('sanctum');

        // Bypass the configurationIsCached() check
        Sanctum::ignoreMigrations();

        // Call the parent register method
        parent::register();
    }
}
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SanctumLumenServiceProvider extends ServiceProvider
{
public function boot()
{
    if (!method_exists($this->app, 'configurationIsCached')) {
        return;
    }
}

}
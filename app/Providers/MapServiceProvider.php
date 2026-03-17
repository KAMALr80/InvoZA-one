<?php
// app/Providers/MapServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\GeocodingService;
use App\Services\OSRMService;

class MapServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(GeocodingService::class, function ($app) {
            return new GeocodingService();
        });

        $this->app->singleton(OSRMService::class, function ($app) {
            return new OSRMService();
        });
    }
}

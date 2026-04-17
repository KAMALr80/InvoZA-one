<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\URL;
use App\Services\AttendanceService;
use App\Services\LeaveService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Force register files if not already registered
        if (!$this->app->bound('files')) {
            $this->app->singleton('files', function ($app) {
                return new Filesystem();
            });
        }

        // ================= REGISTER SERVICES =================
        $this->app->singleton(AttendanceService::class, function ($app) {
            return new AttendanceService();
        });

        $this->app->singleton(LeaveService::class, function ($app) {
            return new LeaveService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS in production (Render / Cloud hosting)
        if (env('APP_FORCE_HTTPS', true)) {
            URL::forceScheme('https');
        }
    }
}

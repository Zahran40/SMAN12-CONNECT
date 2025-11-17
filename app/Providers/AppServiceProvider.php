<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use App\Models\JadwalPelajaran;
use App\Observers\JadwalPelajaranObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS when FORCE_HTTPS is true or in production
        if ($this->app->environment('production') || env('FORCE_HTTPS', false)) {
            URL::forceScheme('https');
        }
        
        // Register JadwalPelajaran Observer
        JadwalPelajaran::observe(JadwalPelajaranObserver::class);
    }
}

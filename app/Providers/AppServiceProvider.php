<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
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
        // Register JadwalPelajaran Observer
        JadwalPelajaran::observe(JadwalPelajaranObserver::class);
    }
}

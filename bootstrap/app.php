<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php', // Pastikan ini ada jika webhook di routes/api.php
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        
        // 1. Alias Middleware (Role kamu)
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);

        // 2. MATIKAN CSRF KHUSUS MIDTRANS (WAJIB!)
        // Agar Laravel tidak menolak notifikasi dari server Midtrans
        $middleware->validateCsrfTokens(except: [
            'midtrans/callback',      // URL Webhook Notifikasi
            'pembayaran/notification', // Jaga-jaga jika pakai URL ini
            'api/midtrans/*',          // Jika rute ada di api.php
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

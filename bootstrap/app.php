<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php', // Pastikan file routes/api.php SUDAH ADA
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        
        // 1. Alias Middleware
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'guru.jadwal.access' => \App\Http\Middleware\CheckGuruJadwalAccess::class,
            'siswa.jadwal.access' => \App\Http\Middleware\CheckSiswaJadwalAccess::class,
            'log.activity' => \App\Http\Middleware\LogUserActivity::class,
        ]);

        // 2. Global Middleware untuk set database connection berdasarkan role
        $middleware->append(\App\Http\Middleware\SetDatabaseConnection::class);
        
        // 2b. Global Middleware untuk set session variables MySQL (untuk trigger logging)
        $middleware->append(\App\Http\Middleware\SetDatabaseSession::class);

        // 3. MATIKAN CSRF KHUSUS MIDTRANS
        // Sesuaikan dengan route yang ada di api.php
        $middleware->validateCsrfTokens(except: [
            'api/payment/midtrans/notification', // <--- INI YANG PALING PENTING (Sesuai api.php)
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Siswa\PembayaranController;
use App\Http\Controllers\OrangTuaController;
use App\Http\Controllers\PimpinanController;
use App\Http\Controllers\BendaharaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ==========================
// MIDTRANS WEBHOOK ROUTE
// ==========================
// URL: domain.com/api/payment/midtrans/notification
Route::post('/payment/midtrans/notification', [PembayaranController::class, 'handleNotification']);

// Orangtua API routes dipindah ke web.php agar bisa menggunakan session authentication

// ==========================
// PIMPINAN API ROUTES
// ==========================
// Pimpinan API routes dipindah ke web.php agar bisa menggunakan session authentication

// ==========================
// BENDAHARA API ROUTES
// ==========================
// Bendahara API routes dipindah ke web.php agar bisa menggunakan session authentication

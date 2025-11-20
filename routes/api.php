<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Siswa\PembayaranController;

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
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

// ==========================
// ORANG TUA API ROUTES
// ==========================
Route::middleware(['auth:sanctum'])->prefix('orangtua')->group(function () {
    Route::middleware('role:orangtua')->group(function () {
        // Dashboard
        Route::get('/dashboard', [OrangTuaController::class, 'apiDashboard']);
        
        // Presensi Real-time
        Route::get('/presensi', [OrangTuaController::class, 'apiPresensi']);
        
        // Monitoring Nilai
        Route::get('/nilai', [OrangTuaController::class, 'apiNilai']);
        
        // Jadwal Pelajaran
        Route::get('/jadwal', [OrangTuaController::class, 'apiJadwal']);
        
        // Detail Tugas & Materi
        Route::get('/tugas-materi', [OrangTuaController::class, 'apiTugasMateri']);
        
        // Riwayat Pembayaran SPP
        Route::get('/pembayaran', [OrangTuaController::class, 'apiPembayaran']);
        
        // Laporan Perilaku
        Route::get('/perilaku', [OrangTuaController::class, 'apiPerilaku']);
        
        // Notifikasi Pengumuman
        Route::get('/pengumuman', [OrangTuaController::class, 'apiPengumuman']);
        Route::post('/pengumuman/{id}/read', [OrangTuaController::class, 'apiMarkPengumumanRead']);
        
        // Perizinan Online
        Route::get('/perizinan', [OrangTuaController::class, 'apiPerizinan']);
        Route::post('/perizinan', [OrangTuaController::class, 'apiAjukanIzin']);
        
        // Grafik Perkembangan
        Route::get('/grafik-perkembangan', [OrangTuaController::class, 'apiGrafikPerkembangan']);
    });
});

// ==========================
// PIMPINAN API ROUTES
// ==========================
Route::middleware(['auth:sanctum'])->prefix('pimpinan')->group(function () {
    Route::middleware('role:pimpinan,kepala_sekolah,kepsek')->group(function () {
        // Dashboard Executive
        Route::get('/dashboard', [PimpinanController::class, 'apiDashboard']);
        
        // Laporan Akademik Global
        Route::get('/laporan-akademik', [PimpinanController::class, 'apiLaporanAkademik']);
        
        // Monitoring Presensi
        Route::get('/monitoring-presensi', [PimpinanController::class, 'apiMonitoringPresensi']);
        
        // Laporan Keuangan
        Route::get('/laporan-keuangan', [PimpinanController::class, 'apiLaporanKeuangan']);
        
        // Evaluasi Kinerja Guru
        Route::get('/evaluasi-guru', [PimpinanController::class, 'apiEvaluasiGuru']);
        
        // Manajemen Pengumuman
        Route::get('/pengumuman', [PimpinanController::class, 'apiPengumuman']);
        Route::put('/pengumuman/{id}/approve', [PimpinanController::class, 'apiApprovePengumuman']);
        
        // Analisis Trending
        Route::get('/analisis-trending', [PimpinanController::class, 'apiAnalisisTrending']);
        
        // Target Sekolah - Goal Tracking
        Route::get('/target-sekolah', [PimpinanController::class, 'apiTargetSekolah']);
        
        // Export Reports
        Route::get('/export-reports', [PimpinanController::class, 'apiExportReports']);
        
        // Digital Signature - Approve dokumen
        Route::post('/dokumen/{id}/approve', [PimpinanController::class, 'apiApproveDokumen']);
    });
});

// ==========================
// BENDAHARA API ROUTES
// ==========================
Route::middleware(['auth:sanctum'])->prefix('bendahara')->group(function () {
    Route::middleware('role:bendahara,admin_keuangan')->group(function () {
        // Dashboard Keuangan
        Route::get('/dashboard', [BendaharaController::class, 'apiDashboard']);
        
        // Manajemen Tagihan SPP
        Route::get('/tagihan', [BendaharaController::class, 'apiTagihan']);
        Route::post('/tagihan', [BendaharaController::class, 'apiCreateTagihan']);
        Route::put('/tagihan/{id}/verify', [BendaharaController::class, 'apiVerifyPembayaran']);
        
        // Rekap Pembayaran
        Route::get('/rekap-pembayaran', [BendaharaController::class, 'apiRekapPembayaran']);
        
        // Tunggakan & Reminder
        Route::get('/tunggakan', [BendaharaController::class, 'apiTunggakan']);
        Route::post('/send-reminder', [BendaharaController::class, 'apiSendReminder']);
        
        // Manajemen Diskon/Beasiswa
        Route::get('/diskon-beasiswa', [BendaharaController::class, 'apiDiskonBeasiswa']);
        Route::post('/diskon-beasiswa', [BendaharaController::class, 'apiCreateDiskonBeasiswa']);
        
        // Refund Management
        Route::get('/refund', [BendaharaController::class, 'apiRefund']);
        Route::put('/refund/{id}/process', [BendaharaController::class, 'apiProcessRefund']);
        
        // Rekonsiliasi Bank
        Route::get('/rekonsiliasi', [BendaharaController::class, 'apiRekonsiliasi']);
        Route::post('/rekonsiliasi', [BendaharaController::class, 'apiCreateRekonsiliasi']);
        
        // Financial Forecasting
        Route::get('/forecasting', [BendaharaController::class, 'apiForecasting']);
        
        // Revenue Analytics
        Route::get('/revenue-analytics', [BendaharaController::class, 'apiRevenueAnalytics']);
        
        // Generate QR Code Payment
        Route::post('/generate-qr', [BendaharaController::class, 'apiGenerateQR']);
    });
});

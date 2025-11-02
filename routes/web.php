<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;


Route::get('/', function () {
    return view('home');
});

// Route GET untuk menampilkan halaman login
Route::get('/login', [LoginController::class, 'login'])->name('login');

// Routes untuk Siswa
Route::prefix('siswa')->group(function () {
    Route::get('/beranda', function () {
        return view('siswa.beranda');
    })->name('siswa.beranda');

    Route::get('/presensi', function () {
        return view('siswa.presensi');
    })->name('siswa.presensi');

    Route::get('/materi', function () {
        return view('siswa.materi');
    })->name('siswa.materi');

    Route::get('/nilai', function () {
        return view('siswa.nilai');
    })->name('siswa.nilai');

    Route::get('/tagihan', function () {
        return view('siswa.tagihan');
    })->name('siswa.tagihan');

    Route::get('/tagihan/sudah', function () {
        return view('siswa.tagihanSudahDibayar');
    })->name('siswa.tagihan_sudah_dibayar');

    Route::get('/pengumuman', function () {
        return view('siswa.pengumuman');
    })->name('siswa.pengumuman');
      Route::get('/profil', function () {
        return view('siswa.profil');
    })->name('siswa.profil');
        // Tambahan routes untuk tampilan lain di folder siswa
    Route::get('/detail-materi', function () {
        return view('siswa.detailMateri');
    })->name('siswa.detail_materi');

    Route::get('/detail-presensi', function () {
        return view('siswa.detailpresensi');
    })->name('siswa.detail_presensi');

    Route::get('/detail-raport', function () {
        return view('siswa.detailRaport');
    })->name('siswa.detail_raport');

    Route::get('/detail-tagihan', function () {
        return view('siswa.detailTagihan');
    })->name('siswa.detail_tagihan');

    Route::get('/detail-tagihan-sudah-dibayar', function () {
        return view('siswa.detailTagihanSudahDibayar');
    })->name('siswa.detail_tagihan_sudah_dibayar');

    Route::get('/tagihan-sudah-dibayar', function () {
        return view('siswa.tagihanSudahDibayar');
    })->name('siswa.tagihan_sudah_dibayar');

    Route::get('/upload-tugas', function () {
        return view('siswa.uploadTugas');
    })->name('siswa.upload_tugas');
});


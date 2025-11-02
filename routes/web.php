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

    Route::get('/pengumuman', function () {
        return view('siswa.pengumuman');
    })->name('siswa.pengumuman');
      Route::get('/profil', function () {
        return view('siswa.profil');
    })->name('siswa.profil');
});


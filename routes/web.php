<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

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
});


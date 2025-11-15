<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\SiswaController;

// ============================================
// PUBLIC ROUTES
// ============================================

Route::get('/', function () {
    return view('home');
})->name('home');

// Login Routes
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ============================================
// SISWA ROUTES (Protected by role middleware)
// ============================================

Route::prefix('siswa')->middleware(['auth', 'role:siswa'])->name('siswa.')->group(function () {
    Route::get('/beranda', [SiswaController::class, 'beranda'])->name('beranda');

    Route::get('/presensi', function () {
        return view('siswa.presensi');
    })->name('presensi');

    Route::get('/materi', function () {
        return view('siswa.materi');
    })->name('materi');

    Route::get('/nilai', function () {
        return view('siswa.nilai');
    })->name('nilai');

    Route::get('/tagihan', function () {
        return view('siswa.tagihan');
    })->name('tagihan');

    Route::get('/tagihan/sudah', function () {
        return view('siswa.tagihanSudahDibayar');
    })->name('tagihan_sudah_dibayar');

    Route::get('/pengumuman', function () {
        return view('siswa.pengumuman');
    })->name('pengumuman');

    Route::get('/profil', [SiswaController::class, 'profil'])->name('profil');

    Route::get('/detail-materi', function () {
        return view('siswa.detailMateri');
    })->name('detail_materi');

    Route::get('/detail-presensi', function () {
        return view('siswa.detailpresensi');
    })->name('detail_presensi');

    Route::get('/detail-raport', function () {
        return view('siswa.detailRaport');
    })->name('detail_raport');

    Route::get('/detail-tagihan', function () {
        return view('siswa.detailTagihan');
    })->name('detail_tagihan');

    Route::get('/detail-tagihan-sudah-dibayar', function () {
        return view('siswa.detailTagihanSudahDibayar');
    })->name('detail_tagihan_sudah_dibayar');

    Route::get('/upload-tugas', function () {
        return view('siswa.uploadTugas');
    })->name('upload_tugas');
});

// ============================================
// GURU ROUTES (Protected by role middleware)
// ============================================

Route::prefix('guru')->middleware(['auth', 'role:guru'])->name('guru.')->group(function () {
    Route::get('/beranda', [GuruController::class, 'beranda'])->name('beranda');

    Route::get('/presensi', function () {
        return view('Guru.presensi');
    })->name('presensi');

    Route::get('/detail-presensi', function () {
        return view('Guru.detailpresensi');
    })->name('detail_presensi');

    Route::get('/materi', function () {
        return view('Guru.materi');
    })->name('materi');

    Route::get('/detail-materi', function () {
        return view('Guru.detailMateri');
    })->name('detail_materi');

    Route::get('/detail-tugas', function () {
        return view('Guru.detailTugas');
    })->name('detail_tugas');

    Route::get('/edit-materi', function () {
        return view('Guru.editMateri');
    })->name('edit_materi');

    Route::get('/upload-materi', function () {
        return view('Guru.uploadMateri');
    })->name('upload_materi');

    Route::get('/upload-materi-step-2', function () {
        return view('Guru.upload2Materi');
    })->name('upload_materi_step2');

    Route::get('/raport-siswa', function () {
        return view('Guru.raportSiswa');
    })->name('raport_siswa');

    Route::get('/detail-raport-siswa', function () {
        return view('Guru.detailRaportSiswa');
    })->name('detail_raport_siswa');

    Route::get('/chart-raport-siswa-semester-1', function () {
        return view('Guru.chartRaportSiswaS1');
    })->name('chart_raport_siswa_s1');

    Route::get('/chart-raport-siswa-semester-2', function () {
        return view('Guru.chartRaportSiswaS2');
    })->name('chart_raport_siswa_s2');

    Route::get('/pengumuman', function () {
        return view('Guru.pengumuman');
    })->name('pengumuman');

    Route::get('/profil', [GuruController::class, 'profil'])->name('profil');

    Route::get('/presensi-mapel-detail', function () {
        return view('Guru.presensiMapelDetail');
    })->name('presensi_mapel_detail');
});

// ============================================
// ADMIN ROUTES (Protected by role middleware)
// ============================================

Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    Route::get('/tahun-ajaran', function () {
        return view('Admin.tahunAjaran');
    })->name('tahun_ajaran');

    Route::get('/buat-tahun-ajaran', function () {
        return view('Admin.buatTahunAjaran');
    })->name('buat_tahun_ajaran');

    Route::get('/buat-mapel', function () {
        return view('Admin.buatMapel');
    })->name('buat_mapel');

    Route::get('/buat-pengumuman', function () {
        return view('Admin.buatPengumuman');
    })->name('buat_pengumuman');

    Route::get('/data-master', function () {
        return view('Admin.dataMaster');
    })->name('data_master');

    Route::get('/data-master-siswa', function () {
        return view('Admin.dataMaster_Siswa');
    })->name('data_master_siswa');

    Route::get('/data-master-guru', function () {
        return view('Admin.dataMaster_Guru');
    })->name('data_master_guru');

    Route::get('/data-master-mapel', function () {
        return view('Admin.dataMaster_Mapel');
    })->name('data_master_mapel');

    Route::get('/data-master-siswa1', function () {
        return view('Admin.dataMasterSiswa');
    })->name('data_master_siswa1');

    Route::get('/data-master-guru1', function () {
        return view('Admin.dataMasterGuru');
    })->name('data_master_guru1');

    Route::get('/data-master-mapel1', function () {
        return view('Admin.dataMasterMapel');
    })->name('data_master_mapel1');

    Route::get('/detail-mapel', function () {
        return view('Admin.detailMapel');
    })->name('detail_mapel');

    Route::get('/detail-guru', function () {
        return view('Admin.detailGuru');
    })->name('detail_guru');

    Route::get('/detail-pembayaran', function () {
        return view('Admin.detailPembayaran');
    })->name('detail_pembayaran');

    Route::get('/detail-siswa', function () {
        return view('Admin.detailSiswa');
    })->name('detail_siswa');

    Route::get('/akademik', function () {
        return view('Admin.akademik');
    })->name('akademik');

    Route::get('/pengumuman', function () {
        return view('Admin.pengumuman');
    })->name('pengumuman');

    Route::get('/pembayaran', function () {
        return view('Admin.pembayaran');
    })->name('pembayaran');
});



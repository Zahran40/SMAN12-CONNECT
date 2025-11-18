<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\Admin\PengumumanController;


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

    // PRESENSI ROUTES - Using Controller
    // Presensi (Absensi Siswa)
    Route::get('/presensi', [App\Http\Controllers\Siswa\PresensiController::class, 'index'])->name('presensi');
    Route::get('/presensi/{jadwal_id}/list', [App\Http\Controllers\Siswa\PresensiController::class, 'listPertemuan'])->name('list_presensi');
    Route::get('/presensi/detail/{pertemuan_id}', [App\Http\Controllers\Siswa\PresensiController::class, 'detail'])->name('detail_presensi');
    Route::post('/presensi/absen/{pertemuan_id}', [App\Http\Controllers\Siswa\PresensiController::class, 'absen'])->name('absen');

    // MATERI ROUTES - Using Controller
    Route::get('/materi', [App\Http\Controllers\Siswa\MateriController::class, 'index'])->name('materi');
    Route::get('/materi/{jadwal_id}', [App\Http\Controllers\Siswa\MateriController::class, 'detail'])->name('detail_materi');
    Route::get('/materi/download-materi/{id}', [App\Http\Controllers\Siswa\MateriController::class, 'downloadMateri'])->name('download_materi');
    Route::get('/materi/download-tugas/{id}', [App\Http\Controllers\Siswa\MateriController::class, 'downloadTugas'])->name('download_tugas');
    Route::post('/materi/upload-tugas/{tugas_id}', [App\Http\Controllers\Siswa\MateriController::class, 'uploadTugas'])->name('upload_tugas');

    // RAPORT ROUTES
    Route::get('/nilai', [App\Http\Controllers\Siswa\RaportController::class, 'index'])->name('nilai');
    Route::get('/detail-raport', [App\Http\Controllers\Siswa\RaportController::class, 'detailAll'])->name('detail_raport');
    Route::get('/detail-raport/{mapel_id}', [App\Http\Controllers\Siswa\RaportController::class, 'detail'])->name('detail_raport_mapel');

    Route::get('/tagihan', function () {
        return view('siswa.tagihan');
    })->name('tagihan');

    Route::get('/tagihan/sudah', function () {
        return view('siswa.tagihanSudahDibayar');
    })->name('tagihan_sudah_dibayar');

    Route::get('/pengumuman', [App\Http\Controllers\Siswa\MateriController::class, 'pengumuman'])->name('pengumuman');

    Route::get('/profil', [SiswaController::class, 'profil'])->name('profil');

    Route::get('/detail-tagihan', function () {
        return view('siswa.detailTagihan');
    })->name('detail_tagihan');

    Route::get('/detail-tagihan-sudah-dibayar', function () {
        return view('siswa.detailTagihanSudahDibayar');
    })->name('detail_tagihan_sudah_dibayar');
});

// ============================================
// GURU ROUTES (Protected by role middleware)
// ============================================

Route::prefix('guru')->middleware(['auth', 'role:guru'])->name('guru.')->group(function () {
    Route::get('/beranda', [GuruController::class, 'beranda'])->name('beranda');

    // PRESENSI ROUTES - Using Controller
    Route::get('/presensi', [App\Http\Controllers\Guru\PresensiController::class, 'index'])->name('presensi');
    Route::get('/presensi/list/{jadwal_id}', [App\Http\Controllers\Guru\PresensiController::class, 'listPertemuan'])->name('list_pertemuan');
    Route::get('/presensi/slot-tersedia/{jadwal_id}', [App\Http\Controllers\Guru\PresensiController::class, 'getSlotTersedia'])->name('slot_tersedia');
    Route::get('/presensi/{pertemuan_id}', [App\Http\Controllers\Guru\PresensiController::class, 'detail'])->name('detail_presensi');
    Route::post('/presensi/{pertemuan_id}/update', [App\Http\Controllers\Guru\PresensiController::class, 'updateStatus'])->name('update_status_presensi');
    Route::post('/presensi/{pertemuan_id}/submit', [App\Http\Controllers\Guru\PresensiController::class, 'submit'])->name('submit_presensi');
    Route::post('/presensi/{pertemuan_id}/unlock', [App\Http\Controllers\Guru\PresensiController::class, 'unlock'])->name('unlock_presensi');
    Route::post('/presensi/buat-pertemuan/{jadwal_id}', [App\Http\Controllers\Guru\PresensiController::class, 'buatPertemuan'])->name('buat_pertemuan');

    // MATERI ROUTES - Using Controller
    Route::get('/materi', [App\Http\Controllers\Guru\MateriController::class, 'index'])->name('materi');
    Route::get('/materi/{jadwal_id}', [App\Http\Controllers\Guru\MateriController::class, 'detail'])->name('detail_materi');
    Route::get('/materi/{jadwal_id}/create', [App\Http\Controllers\Guru\MateriController::class, 'create'])->name('upload_materi');
    Route::get('/materi/{jadwal_id}/create-multiple', [App\Http\Controllers\Guru\MateriController::class, 'createMultiple'])->name('upload_materi_step2');
    Route::post('/materi/{jadwal_id}/store', [App\Http\Controllers\Guru\MateriController::class, 'store'])->name('store_materi');
    
    // TUGAS ROUTES (Terpisah dari Materi)
    Route::get('/materi/{jadwal_id}/create-tugas', [App\Http\Controllers\Guru\MateriController::class, 'createTugas'])->name('upload_tugas');
    Route::post('/materi/{jadwal_id}/store-tugas', [App\Http\Controllers\Guru\MateriController::class, 'storeTugas'])->name('store_tugas');
    Route::get('/materi/{jadwal_id}/materi/{id}/edit', [App\Http\Controllers\Guru\MateriController::class, 'edit'])->name('edit_materi');
    Route::put('/materi/{jadwal_id}/materi/{id}', [App\Http\Controllers\Guru\MateriController::class, 'update'])->name('update_materi');
    Route::delete('/materi/{jadwal_id}/{type}/{id}', [App\Http\Controllers\Guru\MateriController::class, 'destroy'])->name('delete_materi');
    Route::get('/materi/download/{type}/{id}', [App\Http\Controllers\Guru\MateriController::class, 'download'])->name('download_materi');

    Route::get('/detail-tugas/{tugas_id}', [App\Http\Controllers\Guru\MateriController::class, 'detailTugas'])->name('detail_tugas');
    Route::get('/edit-tugas/{tugas_id}', [App\Http\Controllers\Guru\MateriController::class, 'editTugas'])->name('edit_tugas');
    Route::put('/update-tugas/{tugas_id}', [App\Http\Controllers\Guru\MateriController::class, 'updateTugas'])->name('update_tugas');
    Route::post('/detail-tugas/nilai/{detail_tugas_id}', [App\Http\Controllers\Guru\MateriController::class, 'updateNilaiTugas'])->name('update_nilai_tugas');
    Route::post('/detail-tugas/komentar/{detail_tugas_id}', [App\Http\Controllers\Guru\MateriController::class, 'updateKomentarTugas'])->name('update_komentar_tugas');

    // RAPORT ROUTES
    Route::get('/raport-siswa', [App\Http\Controllers\Guru\RaportController::class, 'index'])->name('raport_siswa');
    Route::get('/raport-siswa/{jadwal_id}/input-nilai', [App\Http\Controllers\Guru\RaportController::class, 'inputNilai'])->name('input_nilai');
    Route::get('/raport-siswa/{jadwal_id}/siswa/{siswa_id}/detail', [App\Http\Controllers\Guru\RaportController::class, 'detailNilai'])->name('detail_raport_siswa');
    Route::get('/raport-siswa/{jadwal_id}/siswa/{siswa_id}/semester-2', [App\Http\Controllers\Guru\RaportController::class, 'detailNilaiS2'])->name('chart_raport_siswa_s2');
    Route::post('/raport-siswa/{jadwal_id}/siswa/{siswa_id}/simpan', [App\Http\Controllers\Guru\RaportController::class, 'simpanNilai'])->name('simpan_nilai');

    Route::get('/pengumuman', [App\Http\Controllers\Guru\MateriController::class, 'pengumuman'])->name('pengumuman');

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
    })->name('buatPengumuman');

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

    Route::get('/pendataan-siswa', function () {
        return view('Admin.pendataanSiswa');
    })->name('pendataan_siswa');

    Route::get('/pendataan-guru', function () {
        return view('Admin.pendataanGuru');
    })->name('pendataan_guru');

    Route::get('/akademik', function () {
        return view('Admin.akademik');
    })->name('akademik');

    Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman');
    Route::post('/pengumuman', [PengumumanController::class, 'store'])->name('pengumuman.store');
    Route::put('/pengumuman/{id}', [PengumumanController::class, 'update'])->name('pengumuman.update');
    Route::delete('/pengumuman/{id}', [PengumumanController::class, 'destroy'])->name('pengumuman.destroy');

    Route::get('/pembayaran', function () {
        return view('Admin.pembayaran');
    })->name('pembayaran');
});



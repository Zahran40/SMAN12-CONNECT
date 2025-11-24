<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PengumumanController;
use App\Http\Controllers\Admin\TahunAjaranController;
use App\Http\Controllers\Admin\DataMasterController;
use App\Http\Controllers\Admin\AkademikController;


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

// Forgot Password Routes
Route::get('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showRequestForm'])->name('password.request');
Route::post('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendOtp'])->name('password.send-otp');
Route::get('/reset-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showResetForm'])->name('password.reset.form');
Route::post('/reset-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'resetPassword'])->name('password.update');

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
    // AJAX routes for modal
    Route::get('/pertemuan/{pertemuan_id}/detail', [App\Http\Controllers\Siswa\PresensiController::class, 'getPertemuanDetail']);
    Route::post('/presensi/{pertemuan_id}/absen', [App\Http\Controllers\Siswa\PresensiController::class, 'absenAjax']);

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

    Route::get('/pengumuman', [App\Http\Controllers\Siswa\MateriController::class, 'pengumuman'])->name('pengumuman');

    // TAGIHAN & PEMBAYARAN ROUTES
    Route::get('/tagihan', [App\Http\Controllers\Siswa\PembayaranController::class, 'index'])->name('tagihan');
    Route::get('/tagihan/sudah-dibayar', [App\Http\Controllers\Siswa\PembayaranController::class, 'tagihanSudahDibayar'])->name('tagihan_sudah_dibayar');
    Route::get('/tagihan/{id}', [App\Http\Controllers\Siswa\PembayaranController::class, 'detailTagihan'])->name('detail_tagihan');
    Route::get('/tagihan/{id}/sudah-dibayar', [App\Http\Controllers\Siswa\PembayaranController::class, 'detailTagihanSudahDibayar'])->name('detail_tagihan_sudah_dibayar');
    Route::post('/tagihan/{id}/bayar', [App\Http\Controllers\Siswa\PembayaranController::class, 'createPayment'])->name('tagihan.bayar');
    Route::get('/tagihan/{id}/check-status', [App\Http\Controllers\Siswa\PembayaranController::class, 'checkStatus'])->name('tagihan.check_status');
    Route::get('/tagihan/callback', [App\Http\Controllers\Siswa\PembayaranController::class, 'callback'])->name('tagihan.callback');

    // PROFIL ROUTES
    Route::get('/profil', [SiswaController::class, 'profil'])->name('profil');
});

// Midtrans Notification Handler (tidak perlu auth karena dari server Midtrans)
Route::post('/payment/midtrans/notification', [App\Http\Controllers\Siswa\PembayaranController::class, 'handleNotification'])->name('payment.midtrans.notification');

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
    
    // DASHBOARD
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // TAHUN AJARAN ROUTES
    Route::get('/tahun-ajaran', [TahunAjaranController::class, 'index'])->name('tahun-ajaran.index');
    Route::get('/tahun-ajaran/create', [TahunAjaranController::class, 'create'])->name('tahun-ajaran.create');
    Route::post('/tahun-ajaran', [TahunAjaranController::class, 'store'])->name('tahun-ajaran.store');
    Route::get('/tahun-ajaran/{id}', [TahunAjaranController::class, 'show'])->name('tahun-ajaran.show');
    Route::put('/tahun-ajaran/{id}/status', [TahunAjaranController::class, 'updateStatus'])->name('tahun-ajaran.update-status');
    Route::delete('/tahun-ajaran/{id}', [TahunAjaranController::class, 'destroy'])->name('tahun-ajaran.destroy');
    // Hapus seluruh tahun ajaran (kedua semester) berdasarkan tahun mulai/selesai
    Route::delete('/tahun-ajaran/{tahunMulai}/{tahunSelesai}', [TahunAjaranController::class, 'destroyYear'])->name('tahun-ajaran.destroy-year');

    // KELAS ROUTES
    Route::get('/kelas', [App\Http\Controllers\Admin\KelasController::class, 'all'])->name('kelas.all');
    Route::get('/tahun-ajaran/{tahunAjaranId}/kelas', [App\Http\Controllers\Admin\KelasController::class, 'index'])->name('kelas.index');
    Route::get('/tahun-ajaran/{tahunAjaranId}/kelas/create', [App\Http\Controllers\Admin\KelasController::class, 'create'])->name('kelas.create');
    Route::post('/tahun-ajaran/{tahunAjaranId}/kelas', [App\Http\Controllers\Admin\KelasController::class, 'store'])->name('kelas.store');
    Route::get('/tahun-ajaran/{tahunAjaranId}/kelas/{kelasId}', [App\Http\Controllers\Admin\KelasController::class, 'show'])->name('kelas.show');
    Route::post('/tahun-ajaran/{tahunAjaranId}/kelas/{kelasId}/add-siswa', [App\Http\Controllers\Admin\KelasController::class, 'addSiswa'])->name('kelas.add-siswa');
    Route::delete('/tahun-ajaran/{tahunAjaranId}/kelas/{kelasId}/siswa/{siswaId}', [App\Http\Controllers\Admin\KelasController::class, 'removeSiswa'])->name('kelas.remove-siswa');
    Route::post('/tahun-ajaran/{tahunAjaranId}/kelas/{kelasId}/add-mapel', [App\Http\Controllers\Admin\KelasController::class, 'addMapel'])->name('kelas.add-mapel');
    Route::delete('/tahun-ajaran/{tahunAjaranId}/kelas/{kelasId}/mapel/{jadwalId}', [App\Http\Controllers\Admin\KelasController::class, 'removeMapel'])->name('kelas.remove-mapel');
    Route::put('/tahun-ajaran/{tahunAjaranId}/kelas/{kelasId}/wali-kelas', [App\Http\Controllers\Admin\KelasController::class, 'updateWaliKelas'])->name('kelas.update-wali');
    Route::delete('/tahun-ajaran/{tahunAjaranId}/kelas/{kelasId}', [App\Http\Controllers\Admin\KelasController::class, 'destroy'])->name('kelas.destroy');

    // DATA MASTER ROUTES
    Route::get('/data-master', [DataMasterController::class, 'index'])->name('data-master.index');
    
    // Siswa
    Route::get('/data-master/siswa/create', [DataMasterController::class, 'createSiswa'])->name('data-master.siswa.create');
    Route::get('/data-master/siswa/{id}/edit', [DataMasterController::class, 'editSiswa'])->name('data-master.siswa.edit');
    Route::post('/data-master/siswa', [DataMasterController::class, 'storeSiswa'])->name('data-master.siswa.store');
    Route::put('/data-master/siswa/{id}', [DataMasterController::class, 'storeSiswa'])->name('data-master.siswa.update');
    Route::get('/data-master/siswa/{id}', [DataMasterController::class, 'detailSiswa'])->name('data-master.siswa.show');
    Route::delete('/data-master/siswa/{id}', [DataMasterController::class, 'deleteSiswa'])->name('data-master.siswa.destroy');
    
    // Siswa Kelas (untuk assign siswa ke kelas per tahun ajaran)
    Route::post('/data-master/siswa/{siswa_id}/assign-kelas', [DataMasterController::class, 'assignSiswaKelas'])->name('data-master.siswa.assign-kelas');
    Route::put('/data-master/siswa-kelas/{id}', [DataMasterController::class, 'updateSiswaKelas'])->name('data-master.siswa-kelas.update');
    Route::delete('/data-master/siswa-kelas/{id}', [DataMasterController::class, 'deleteSiswaKelas'])->name('data-master.siswa-kelas.destroy');
    
    // Guru
    Route::get('/data-master/guru/create', [DataMasterController::class, 'createGuru'])->name('data-master.guru.create');
    Route::get('/data-master/guru/{id}/edit', [DataMasterController::class, 'editGuru'])->name('data-master.guru.edit');
    Route::post('/data-master/guru', [DataMasterController::class, 'storeGuru'])->name('data-master.guru.store');
    Route::put('/data-master/guru/{id}', [DataMasterController::class, 'storeGuru'])->name('data-master.guru.update');
    Route::get('/data-master/guru/{id}', [DataMasterController::class, 'detailGuru'])->name('data-master.guru.show');
    Route::delete('/data-master/guru/{id}', [DataMasterController::class, 'deleteGuru'])->name('data-master.guru.destroy');

    // Detail Kelas (Siswa/Guru/Mapel per kelas)
    Route::get('/data-master/kelas/{id}/siswa', [DataMasterController::class, 'detailKelasSiswa'])->name('data-master.kelas.siswa');
    Route::get('/data-master/kelas/{id}/guru', [DataMasterController::class, 'detailKelasGuru'])->name('data-master.kelas.guru');
    Route::get('/data-master/kelas/{id}/mapel', [DataMasterController::class, 'detailKelasMapel'])->name('data-master.kelas.mapel');

    // List view (underscore files - semua siswa/guru/mapel)
    Route::get('/data-master/list-siswa', [DataMasterController::class, 'listSiswa'])->name('data-master.list-siswa');
    Route::get('/data-master/list-guru', [DataMasterController::class, 'listGuru'])->name('data-master.list-guru');
    Route::get('/data-master/list-mapel', [DataMasterController::class, 'listMapel'])->name('data-master.list-mapel');

    // AKADEMIK ROUTES
    Route::get('/akademik', [AkademikController::class, 'index'])->name('akademik.index');
    Route::get('/akademik/mapel/create', [AkademikController::class, 'createMapel'])->name('akademik.mapel.create');
    Route::post('/akademik/mapel', [AkademikController::class, 'storeMapel'])->name('akademik.mapel.store');
    Route::get('/akademik/mapel/{id}', [AkademikController::class, 'detailMapel'])->name('akademik.mapel.show');
    Route::get('/akademik/mapel/{id}/edit', [AkademikController::class, 'editMapel'])->name('akademik.mapel.edit');
    Route::put('/akademik/mapel/{id}', [AkademikController::class, 'updateMapel'])->name('akademik.mapel.update');
    Route::delete('/akademik/mapel/{id}', [AkademikController::class, 'deleteMapel'])->name('akademik.mapel.destroy');
    
    // Jadwal
    Route::get('/akademik/jadwal', [AkademikController::class, 'jadwal'])->name('akademik.jadwal.index');
    Route::post('/akademik/jadwal', [AkademikController::class, 'storeJadwal'])->name('akademik.jadwal.store');
    Route::put('/akademik/jadwal/{id}', [AkademikController::class, 'updateJadwal'])->name('akademik.jadwal.update');
    Route::delete('/akademik/jadwal/{id}', [AkademikController::class, 'deleteJadwal'])->name('akademik.jadwal.destroy');

    // PENGUMUMAN ROUTES (sudah ada)
    Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman');
    Route::get('/pengumuman/create', [PengumumanController::class, 'create'])->name('pengumuman.create');
    Route::post('/pengumuman', [PengumumanController::class, 'store'])->name('pengumuman.store');
    Route::put('/pengumuman/{id}', [PengumumanController::class, 'update'])->name('pengumuman.update');
    Route::delete('/pengumuman/{id}', [PengumumanController::class, 'destroy'])->name('pengumuman.destroy');

    // PEMBAYARAN ROUTES
    Route::get('/pembayaran', [App\Http\Controllers\Admin\PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::get('/pembayaran/create', [App\Http\Controllers\Admin\PembayaranController::class, 'create'])->name('pembayaran.create');
    Route::post('/pembayaran', [App\Http\Controllers\Admin\PembayaranController::class, 'store'])->name('pembayaran.store');
    Route::get('/pembayaran/{id}', [App\Http\Controllers\Admin\PembayaranController::class, 'show'])->name('pembayaran.show');
    Route::put('/pembayaran/{id}/status', [App\Http\Controllers\Admin\PembayaranController::class, 'updateStatus'])->name('pembayaran.update_status');
    Route::delete('/pembayaran/{id}', [App\Http\Controllers\Admin\PembayaranController::class, 'destroy'])->name('pembayaran.destroy');

    // LOG AKTIVITAS ROUTES
    Route::get('/log-aktivitas', [App\Http\Controllers\Admin\LogAktivitasController::class, 'index'])->name('log-aktivitas.index');
    Route::post('/log-aktivitas/cleanup', [App\Http\Controllers\Admin\LogAktivitasController::class, 'cleanup'])->name('log-aktivitas.cleanup');
    Route::get('/log-aktivitas/export', [App\Http\Controllers\Admin\LogAktivitasController::class, 'export'])->name('log-aktivitas.export');

    // Legacy routes untuk compatibility (redirect ke yang baru)
    Route::get('/tahun-ajaran-old', function() {
        return redirect()->route('admin.tahun-ajaran.index');
    });
    Route::get('/buat-tahun-ajaran', function() {
        return redirect()->route('admin.tahun-ajaran.create');
    });
    Route::get('/buat-mapel', function() {
        return redirect()->route('admin.akademik.mapel.create');
    });
    Route::get('/pendataan-siswa', function() {
        return redirect()->route('admin.data-master.siswa.create');
    });
    Route::get('/pendataan-guru', function() {
        return redirect()->route('admin.data-master.guru.create');
    });
});



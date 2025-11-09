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

    // Routes untuk Admin
    Route::prefix('admin')->name('admin.')->group(function () {
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

    // Routes untuk Guru
    Route::prefix('guru')->name('guru.')->group(function () {
        Route::get('/beranda', function () {
            return view('Guru.beranda');
        })->name('beranda');

        Route::get('/presensi', function () {
            return view('Guru.presensi');
        })->name('presensi');

        // Detail presensi (rename route name to be consistent without duplicate prefix)
        Route::get('/detail-presensi', function () {
            return view('Guru.detailpresensi');
        })->name('detail_presensi');

        Route::get('/materi', function () {
            return view('Guru.materi');
        })->name('materi');

        // Detail Materi
        Route::get('/detail-materi', function () {
            return view('Guru.detailMateri');
        })->name('detail_materi');

        // Detail Tugas terkait materi
        Route::get('/detail-tugas', function () {
            return view('Guru.detailTugas');
        })->name('detail_tugas');

        // Edit Materi
        Route::get('/edit-materi', function () {
            return view('Guru.editMateri');
        })->name('edit_materi');

        // Upload Materi langkah 1
        Route::get('/upload-materi', function () {
            return view('Guru.uploadMateri');
        })->name('upload_materi');

        // Upload Materi langkah 2 / lanjutan
        Route::get('/upload-materi-step-2', function () {
            return view('Guru.upload2Materi');
        })->name('upload_materi_step2');

        // Route Nilai dihapus; gunakan menu Raport Siswa

        // Raport Siswa (daftar / ringkasan)
        Route::get('/raport-siswa', function () {
            return view('Guru.raportSiswa');
        })->name('raport_siswa');

        // Detail Raport Siswa (per siswa)
        Route::get('/detail-raport-siswa', function () {
            return view('Guru.detailRaportSiswa');
        })->name('detail_raport_siswa');

        // Chart Raport Siswa Semester 1
        Route::get('/chart-raport-siswa-semester-1', function () {
            return view('Guru.chartRaportSiswaS1');
        })->name('chart_raport_siswa_s1');

        // Chart Raport Siswa Semester 2
        Route::get('/chart-raport-siswa-semester-2', function () {
            return view('Guru.chartRaportSiswaS2');
        })->name('chart_raport_siswa_s2');

        Route::get('/pengumuman', function () {
            return view('Guru.pengumuman');
        })->name('pengumuman');

        Route::get('/profil', function () {
            return view('Guru.profil');
        })->name('profil');  
        Route::get('/presensi-mapel-detail', function () {
            return view('Guru.presensiMapelDetail');
        })->name('presensi_mapel_detail');  
    });


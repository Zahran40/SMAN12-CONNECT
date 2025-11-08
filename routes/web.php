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

        Route::get('/detail-presensi-guru', function () {
        return view('Guru.detailpresensi');
        })->name('Guru.detail_presensi');

        Route::get('/materi', function () {
            return view('Guru.materi');
        })->name('materi');

        Route::get('/nilai', function () {
            return view('Guru.nilai');
        })->name('nilai');

        Route::get('/pengumuman', function () {
            return view('Guru.pengumuman');
        })->name('pengumuman');

        Route::get('/profil', function () {
            return view('Guru.profil');
        })->name('profil');

        
    });


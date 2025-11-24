<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    public function up(): void
    {
        DB::statement("CREATE OR REPLACE VIEW view_siswa_kelas AS
            SELECT 
                s.id_siswa,
                s.user_id,
                s.nis,
                s.nisn,
                s.nama_lengkap,
                s.tgl_lahir,
                s.tempat_lahir,
                s.alamat,
                s.jenis_kelamin,
                s.no_telepon,
                s.email,
                s.agama,
                s.golongan_darah,
                sk.kelas_id,
                k.nama_kelas,
                k.tingkat,
                k.jurusan,
                sk.tahun_ajaran_id,
                ta.tahun_mulai,
                ta.tahun_selesai,
                ta.semester,
                sk.status as status_kelas,
                sk.tanggal_masuk,
                sk.tanggal_keluar
            FROM siswa s
            LEFT JOIN siswa_kelas sk ON s.id_siswa = sk.siswa_id AND sk.status = 'Aktif'
            LEFT JOIN kelas k ON sk.kelas_id = k.id_kelas
            LEFT JOIN tahun_ajaran ta ON sk.tahun_ajaran_id = ta.id_tahun_ajaran
        ");
        DB::statement("CREATE OR REPLACE VIEW view_guru_mengajar AS
            SELECT DISTINCT
                g.id_guru,
                g.user_id,
                g.nip,
                g.nama_lengkap,
                g.tgl_lahir,
                g.tempat_lahir,
                g.alamat,
                g.jenis_kelamin,
                g.no_telepon,
                g.email,
                g.agama,
                g.golongan_darah,
                jp.mapel_id,
                mp.nama_mapel,
                mp.kode_mapel,
                jp.kelas_id,
                k.nama_kelas,
                k.tingkat,
                jp.tahun_ajaran_id,
                ta.tahun_mulai,
                ta.tahun_selesai,
                ta.semester
            FROM guru g
            LEFT JOIN jadwal_pelajaran jp ON g.id_guru = jp.guru_id
            LEFT JOIN mata_pelajaran mp ON jp.mapel_id = mp.id_mapel
            LEFT JOIN kelas k ON jp.kelas_id = k.id_kelas
            LEFT JOIN tahun_ajaran ta ON jp.tahun_ajaran_id = ta.id_tahun_ajaran
        ");
        DB::statement("CREATE OR REPLACE VIEW view_mapel_diajarkan AS
            SELECT DISTINCT
                mp.id_mapel,
                mp.kode_mapel,
                mp.nama_mapel,
                mp.kategori,
                jp.guru_id,
                g.nama_lengkap as nama_guru,
                g.nip,
                jp.kelas_id,
                k.nama_kelas,
                k.tingkat,
                k.jurusan,
                jp.tahun_ajaran_id,
                ta.tahun_mulai,
                ta.tahun_selesai,
                ta.semester,
                jp.hari,
                jp.jam_mulai,
                jp.jam_selesai
            FROM mata_pelajaran mp
            LEFT JOIN jadwal_pelajaran jp ON mp.id_mapel = jp.mapel_id
            LEFT JOIN guru g ON jp.guru_id = g.id_guru
            LEFT JOIN kelas k ON jp.kelas_id = k.id_kelas
            LEFT JOIN tahun_ajaran ta ON jp.tahun_ajaran_id = ta.id_tahun_ajaran
        ");
        DB::statement("CREATE OR REPLACE VIEW view_kelas_detail AS
            SELECT 
                k.id_kelas,
                k.nama_kelas,
                k.tingkat,
                k.jurusan,
                k.tahun_ajaran_id,
                ta.tahun_mulai,
                ta.tahun_selesai,
                ta.semester,
                k.wali_kelas_id,
                g.nama_lengkap as nama_wali_kelas,
                g.nip as nip_wali_kelas,
                (SELECT COUNT(DISTINCT sk.siswa_id) 
                 FROM siswa_kelas sk 
                 WHERE sk.kelas_id = k.id_kelas 
                 AND sk.status = 'Aktif') as jumlah_siswa,
                (SELECT COUNT(DISTINCT jp.mapel_id) 
                 FROM jadwal_pelajaran jp 
                 WHERE jp.kelas_id = k.id_kelas 
                 AND jp.tahun_ajaran_id = k.tahun_ajaran_id) as jumlah_mapel,
                (SELECT COUNT(DISTINCT jp.guru_id) 
                 FROM jadwal_pelajaran jp 
                 WHERE jp.kelas_id = k.id_kelas 
                 AND jp.tahun_ajaran_id = k.tahun_ajaran_id) as jumlah_guru
            FROM kelas k
            LEFT JOIN tahun_ajaran ta ON k.tahun_ajaran_id = ta.id_tahun_ajaran
            LEFT JOIN guru g ON k.wali_kelas_id = g.id_guru
        ");
    }
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS view_siswa_kelas");
        DB::statement("DROP VIEW IF EXISTS view_guru_mengajar");
        DB::statement("DROP VIEW IF EXISTS view_mapel_diajarkan");
        DB::statement("DROP VIEW IF EXISTS view_kelas_detail");
    }
};
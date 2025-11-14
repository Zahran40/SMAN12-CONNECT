<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared("
            CREATE OR REPLACE VIEW view_dashboard_siswa AS
            SELECT 
                s.id_siswa,
                s.nis,
                s.nisn,
                s.nama_lengkap,
                k.nama_kelas,
                k.tingkat,
                ta.tahun_mulai,
                ta.tahun_selesai,
                ta.semester,
                COUNT(DISTINCT jp.id_jadwal) AS total_mapel,
                COUNT(DISTINCT CASE WHEN ps.status = 'Belum Lunas' THEN ps.id_pembayaran END) AS tagihan_belum_lunas,
                COUNT(DISTINCT CASE WHEN ps.status = 'Lunas' THEN ps.id_pembayaran END) AS tagihan_lunas,
                AVG(n.nilai_akhir) AS rata_rata_nilai
            FROM siswa s
            LEFT JOIN kelas k ON s.kelas_id = k.id_kelas
            LEFT JOIN tahun_ajaran ta ON k.tahun_ajaran_id = ta.id_tahun_ajaran
            LEFT JOIN jadwal_pelajaran jp ON k.id_kelas = jp.kelas_id AND ta.id_tahun_ajaran = jp.tahun_ajaran_id
            LEFT JOIN pembayaran_spp ps ON s.id_siswa = ps.siswa_id AND ta.id_tahun_ajaran = ps.tahun_ajaran_id
            LEFT JOIN nilai n ON s.id_siswa = n.siswa_id AND ta.id_tahun_ajaran = n.tahun_ajaran_id
            WHERE ta.status = 'Aktif' OR ta.status IS NULL
            GROUP BY s.id_siswa, s.nis, s.nisn, s.nama_lengkap, k.nama_kelas, k.tingkat, ta.tahun_mulai, ta.tahun_selesai, ta.semester
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP VIEW IF EXISTS view_dashboard_siswa");
    }
};

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
        DB::statement("
            CREATE VIEW view_tunggakan_siswa AS
            SELECT 
                s.id_siswa,
                s.nisn,
                s.nama_lengkap as nama,
                s.user_id,
                ta.id_tahun_ajaran,
                ta.tahun_mulai,
                ta.tahun_selesai,
                ta.semester,
                COUNT(CASE WHEN ps.status = 'Belum Lunas' THEN 1 END) as jumlah_tunggakan,
                SUM(CASE WHEN ps.status = 'Belum Lunas' THEN ps.jumlah_bayar ELSE 0 END) as total_tunggakan,
                MIN(CASE WHEN ps.status = 'Belum Lunas' THEN CONCAT(ps.tahun, '-', LPAD(ps.bulan, 2, '0')) END) as bulan_tertunggak_pertama,
                MAX(CASE WHEN ps.status = 'Belum Lunas' THEN CONCAT(ps.tahun, '-', LPAD(ps.bulan, 2, '0')) END) as bulan_tertunggak_terakhir
            FROM siswa s
            INNER JOIN tahun_ajaran ta ON ta.status = 'Aktif'
            LEFT JOIN pembayaran_spp ps ON s.id_siswa = ps.siswa_id AND ps.tahun_ajaran_id = ta.id_tahun_ajaran
            GROUP BY s.id_siswa, s.nisn, s.nama_lengkap, s.user_id, ta.id_tahun_ajaran, ta.tahun_mulai, ta.tahun_selesai, ta.semester
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS view_tunggakan_siswa');
    }
};

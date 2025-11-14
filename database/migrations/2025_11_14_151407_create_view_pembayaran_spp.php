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
            CREATE OR REPLACE VIEW view_pembayaran_spp AS
            SELECT 
                ps.id_pembayaran,
                ps.nama_tagihan,
                ps.bulan,
                ps.tahun,
                ps.jumlah_bayar,
                ps.tgl_bayar,
                ps.metode_pembayaran,
                ps.nomor_va,
                ps.status,
                s.id_siswa,
                s.nis,
                s.nisn,
                s.nama_lengkap AS nama_siswa,
                k.id_kelas,
                k.nama_kelas,
                k.tingkat,
                ta.id_tahun_ajaran,
                ta.tahun_mulai,
                ta.tahun_selesai,
                ta.semester
            FROM pembayaran_spp ps
            JOIN siswa s ON ps.siswa_id = s.id_siswa
            JOIN kelas k ON s.kelas_id = k.id_kelas
            JOIN tahun_ajaran ta ON ps.tahun_ajaran_id = ta.id_tahun_ajaran
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP VIEW IF EXISTS view_pembayaran_spp");
    }
};

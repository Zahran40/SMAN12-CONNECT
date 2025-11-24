<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            CREATE OR REPLACE VIEW view_data_siswa AS
            SELECT 
                s.id_siswa,
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
                k.id_kelas,
                k.nama_kelas,
                k.tingkat,
                ta.id_tahun_ajaran,
                ta.tahun_mulai,
                ta.tahun_selesai,
                ta.semester,
                u.id AS user_id,
                u.name AS username,
                u.email AS user_email,
                u.role
            FROM siswa s
            LEFT JOIN kelas k ON s.kelas_id = k.id_kelas
            LEFT JOIN tahun_ajaran ta ON k.tahun_ajaran_id = ta.id_tahun_ajaran
            LEFT JOIN users u ON s.user_id = u.id
        ");
    }
    public function down(): void
    {
        DB::unprepared("DROP VIEW IF EXISTS view_data_siswa");
    }
};
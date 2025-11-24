<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            CREATE OR REPLACE VIEW view_nilai_siswa AS
            SELECT 
                n.id_nilai,
                n.nilai_tugas,
                n.nilai_uts,
                n.nilai_uas,
                n.nilai_akhir,
                s.id_siswa,
                s.nis,
                s.nisn,
                s.nama_lengkap AS nama_siswa,
                k.nama_kelas,
                k.tingkat,
                mp.id_mapel,
                mp.nama_mapel,
                mp.kode_mapel,
                ta.id_tahun_ajaran,
                ta.tahun_mulai,
                ta.tahun_selesai,
                ta.semester,
                CASE 
                    WHEN n.nilai_akhir >= 90 THEN 'A'
                    WHEN n.nilai_akhir >= 80 THEN 'B'
                    WHEN n.nilai_akhir >= 70 THEN 'C'
                    WHEN n.nilai_akhir >= 60 THEN 'D'
                    ELSE 'E'
                END AS grade
            FROM nilai n
            JOIN siswa s ON n.siswa_id = s.id_siswa
            JOIN kelas k ON s.kelas_id = k.id_kelas
            JOIN mata_pelajaran mp ON n.mapel_id = mp.id_mapel
            JOIN tahun_ajaran ta ON n.tahun_ajaran_id = ta.id_tahun_ajaran
        ");
    }
    public function down(): void
    {
        DB::unprepared("DROP VIEW IF EXISTS view_nilai_siswa");
    }
};
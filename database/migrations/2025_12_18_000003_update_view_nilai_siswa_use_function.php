<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Update view_nilai_siswa untuk menggunakan function fn_calculate_nilai_akhir
     * dan fn_convert_grade_letter untuk menghitung nilai akhir dan grade secara dynamic.
     */
    public function up(): void
    {
        DB::unprepared("
            CREATE OR REPLACE VIEW view_nilai_siswa AS
            SELECT 
                n.id_nilai,
                n.nilai_tugas,
                n.nilai_uts,
                n.nilai_uas,
                -- Computed column: hitung nilai akhir menggunakan function
                fn_calculate_nilai_akhir(n.nilai_tugas, n.nilai_uts, n.nilai_uas) AS nilai_akhir,
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
                -- Computed column: konversi nilai akhir ke grade menggunakan function
                fn_convert_grade_letter(
                    fn_calculate_nilai_akhir(n.nilai_tugas, n.nilai_uts, n.nilai_uas)
                ) AS grade
            FROM nilai n
            JOIN siswa s ON n.siswa_id = s.id_siswa
            JOIN kelas k ON s.kelas_id = k.id_kelas
            JOIN mata_pelajaran mp ON n.mapel_id = mp.id_mapel
            JOIN tahun_ajaran ta ON n.tahun_ajaran_id = ta.id_tahun_ajaran
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore view dengan kolom nilai_akhir dari tabel
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
};

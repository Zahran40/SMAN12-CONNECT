<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_rekap_absensi_kelas");
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_rekap_absensi_kelas`(IN `id_jadwal_param` BIGINT)
BEGIN
  SELECT s.id_siswa,
         s.nama_lengkap,
         s.nis,
         COUNT(DISTINCT CASE WHEN p.is_submitted = 1 THEN p.id_pertemuan END) AS total_pertemuan,
         SUM(CASE WHEN da.status_kehadiran = 'Hadir' AND p.is_submitted = 1 THEN 1 ELSE 0 END) AS hadir,
         SUM(CASE WHEN da.status_kehadiran = 'Izin' AND p.is_submitted = 1 THEN 1 ELSE 0 END) AS izin,
         SUM(CASE WHEN da.status_kehadiran = 'Sakit' AND p.is_submitted = 1 THEN 1 ELSE 0 END) AS sakit,
         SUM(CASE WHEN da.status_kehadiran = 'Alfa' AND p.is_submitted = 1 THEN 1 ELSE 0 END) AS alfa
  FROM siswa s
  INNER JOIN jadwal_pelajaran j ON s.kelas_id = j.kelas_id
  LEFT JOIN pertemuan p ON p.jadwal_id = j.id_jadwal
  LEFT JOIN detail_absensi da ON da.pertemuan_id = p.id_pertemuan AND da.siswa_id = s.id_siswa
  WHERE j.id_jadwal = id_jadwal_param
  GROUP BY s.id_siswa, s.nama_lengkap, s.nis
  ORDER BY s.nama_lengkap;
END");
    }
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_rekap_absensi_kelas");
    }
};
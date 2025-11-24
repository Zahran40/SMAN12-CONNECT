<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_rekap_absensi_kelas");
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_rekap_absensi_kelas`(IN `id_kelas_param` BIGINT, IN `tanggal_awal` DATE, IN `tanggal_akhir` DATE)
BEGIN
  SELECT s.nama_lengkap,
         s.nis,
         COUNT(DISTINCT p.id_pertemuan) AS total_pertemuan,
         SUM(CASE WHEN da.status_kehadiran = 'Hadir' THEN 1 ELSE 0 END) AS hadir,
         SUM(CASE WHEN da.status_kehadiran = 'Izin' THEN 1 ELSE 0 END) AS izin,
         SUM(CASE WHEN da.status_kehadiran = 'Sakit' THEN 1 ELSE 0 END) AS sakit,
         SUM(CASE WHEN da.status_kehadiran = 'Alfa' THEN 1 ELSE 0 END) AS alfa
  FROM siswa s
  LEFT JOIN detail_absensi da ON s.id_siswa = da.siswa_id
  LEFT JOIN pertemuan p ON da.pertemuan_id = p.id_pertemuan
  LEFT JOIN jadwal_pelajaran j ON p.jadwal_id = j.id_jadwal
  WHERE s.kelas_id = id_kelas_param
    AND p.tanggal_pertemuan BETWEEN tanggal_awal AND tanggal_akhir
  GROUP BY s.id_siswa, s.nama_lengkap, s.nis;
END");
    }
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_rekap_absensi_kelas");
    }
};
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
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_rekap_absensi_kelas`(IN `id_kelas_param` BIGINT, IN `tanggal_awal` DATE, IN `tanggal_akhir` DATE)
BEGIN
  SELECT s.nama_lengkap,
         COUNT(a.id_absensi) AS total_pertemuan,
         SUM(CASE WHEN a.status_kehadiran = 'Hadir' THEN 1 ELSE 0 END) AS hadir,
         SUM(CASE WHEN a.status_kehadiran = 'Izin' THEN 1 ELSE 0 END) AS izin,
         SUM(CASE WHEN a.status_kehadiran = 'Sakit' THEN 1 ELSE 0 END) AS sakit,
         SUM(CASE WHEN a.status_kehadiran = 'Alfa' THEN 1 ELSE 0 END) AS alfa
  FROM absensi a
  JOIN siswa s ON a.siswa_id = s.id_siswa
  WHERE s.kelas_id = id_kelas_param
    AND a.tanggal BETWEEN tanggal_awal AND tanggal_akhir
  GROUP BY s.id_siswa;
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_rekap_absensi_kelas");
    }
};

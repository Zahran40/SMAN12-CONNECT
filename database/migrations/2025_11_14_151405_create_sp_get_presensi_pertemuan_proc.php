<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_get_presensi_pertemuan");
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_presensi_pertemuan`(IN `pertemuan_id_param` BIGINT)
BEGIN
  SELECT s.id_siswa,
         s.nama_lengkap,
         s.nis,
         COALESCE(da.status_kehadiran, 'Alfa') AS status_kehadiran,
         da.keterangan,
         da.dicatat_pada
  FROM pertemuan p
  JOIN jadwal_pelajaran jp ON p.jadwal_id = jp.id_jadwal
  JOIN kelas k ON jp.kelas_id = k.id_kelas
  JOIN siswa s ON k.id_kelas = s.kelas_id
  LEFT JOIN detail_absensi da ON s.id_siswa = da.siswa_id AND p.id_pertemuan = da.pertemuan_id
  WHERE p.id_pertemuan = id_pertemuan_param
  ORDER BY s.nama_lengkap;
END");
    }
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_get_presensi_pertemuan");
    }
};
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_rekap_nilai_siswa");
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_rekap_nilai_siswa`(IN `siswa_id_param` BIGINT, IN `tahun_ajaran_id_param` BIGINT)
BEGIN
  SELECT s.nama_lengkap,
         m.nama_mapel,
         n.nilai_tugas,
         n.nilai_uts,
         n.nilai_uas,
         n.nilai_akhir
  FROM nilai n
  JOIN siswa s ON n.siswa_id = s.id_siswa
  JOIN mata_pelajaran m ON n.mapel_id = m.id_mapel
  WHERE s.kelas_id = id_kelas_param
    AND n.tahun_ajaran_id = id_tahun_ajaran_param;
END");
    }
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_rekap_nilai_siswa");
    }
};
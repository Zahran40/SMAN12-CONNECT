<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_rekap_nilai_siswa");
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_rekap_nilai_siswa`(IN `siswa_id_param` BIGINT, IN `tahun_ajaran_id_param` BIGINT, IN `semester_param` VARCHAR(10))
BEGIN
  SELECT s.nama_lengkap,
         m.nama_mapel,
         n.nilai_tugas,
         n.nilai_uts,
         n.nilai_uas,
         n.nilai_akhir,
         n.semester
  FROM nilai n
  JOIN siswa s ON n.siswa_id = s.id_siswa
  JOIN mata_pelajaran m ON n.mapel_id = m.id_mapel
  WHERE n.siswa_id = siswa_id_param
    AND n.tahun_ajaran_id = tahun_ajaran_id_param
    AND n.semester = semester_param
  ORDER BY m.nama_mapel;
END");
    }
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_rekap_nilai_siswa");
    }
};
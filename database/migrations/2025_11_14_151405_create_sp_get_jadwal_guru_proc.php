<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_get_jadwal_guru");
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_jadwal_guru`(IN `id_guru_param` BIGINT, IN `hari_param` VARCHAR(20))
BEGIN
  SELECT jp.id_jadwal,
         mp.nama_mapel,
         k.nama_kelas,
         jp.hari,
         jp.jam_mulai,
         jp.jam_selesai,
         ta.tahun_mulai,
         ta.tahun_selesai,
         ta.semester
  FROM jadwal_pelajaran jp
  JOIN mata_pelajaran mp ON jp.mapel_id = mp.id_mapel
  JOIN kelas k ON jp.kelas_id = k.id_kelas
  JOIN tahun_ajaran ta ON jp.tahun_ajaran_id = ta.id_tahun_ajaran
  WHERE jp.guru_id = id_guru_param
    AND (hari_param IS NULL OR jp.hari = hari_param)
    AND ta.status = 'Aktif'
  ORDER BY 
    FIELD(jp.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'),
    jp.jam_mulai;
END");
    }
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_get_jadwal_guru");
    }
};
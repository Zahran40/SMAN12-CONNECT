<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_get_jadwal_siswa");
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_jadwal_siswa`(IN `id_siswa_param` BIGINT, IN `hari_param` VARCHAR(20))
BEGIN
  SELECT jp.id_jadwal,
         mp.nama_mapel,
         g.nama_lengkap AS nama_guru,
         jp.hari,
         jp.jam_mulai,
         jp.jam_selesai
  FROM siswa s
  JOIN kelas k ON s.kelas_id = k.id_kelas
  JOIN jadwal_pelajaran jp ON k.id_kelas = jp.kelas_id
  JOIN mata_pelajaran mp ON jp.mapel_id = mp.id_mapel
  JOIN guru g ON jp.guru_id = g.id_guru
  JOIN tahun_ajaran ta ON jp.tahun_ajaran_id = ta.id_tahun_ajaran
  WHERE s.id_siswa = id_siswa_param
    AND (hari_param IS NULL OR jp.hari = hari_param)
    AND ta.status = 'Aktif'
  ORDER BY 
    FIELD(jp.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'),
    jp.jam_mulai;
END");
    }
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_get_jadwal_siswa");
    }
};
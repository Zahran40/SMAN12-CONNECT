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
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_get_materi_by_pertemuan");
        
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_materi_by_pertemuan`(IN `pertemuan_id_param` BIGINT)
BEGIN
  SELECT p.id_pertemuan,
         p.tanggal_pertemuan,
         p.topik_bahasan,
         p.status_sesi,
         m.id_materi,
         m.judul_materi,
         m.deskripsi,
         m.file_path AS file_materi,
         t.id_tugas,
         t.judul_tugas,
         t.deskripsi AS deskripsi_tugas,
         t.file_path AS file_tugas,
         t.deadline
  FROM pertemuan p
  LEFT JOIN materi m ON p.id_pertemuan = m.pertemuan_id
  LEFT JOIN tugas t ON p.id_pertemuan = t.pertemuan_id
  WHERE p.jadwal_id = id_jadwal_param
  ORDER BY p.tanggal_pertemuan, m.id_materi, t.id_tugas;
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_get_materi_by_pertemuan");
    }
};

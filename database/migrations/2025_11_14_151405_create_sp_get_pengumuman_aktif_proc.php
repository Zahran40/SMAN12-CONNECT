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
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_get_pengumuman_aktif");
        
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_pengumuman_aktif`(IN `target_role_param` VARCHAR(20))
BEGIN
  SELECT p.id_pengumuman,
         p.judul,
         p.isi_pengumuman,
         p.tgl_publikasi,
         p.hari,
         p.target_role,
         p.file_lampiran,
         u.name AS author_name,
         u.role AS author_role
  FROM pengumuman p
  JOIN users u ON p.author_id = u.id
  WHERE p.status = 'aktif' 
    AND (p.target_role COLLATE utf8mb4_unicode_ci = target_role_param COLLATE utf8mb4_unicode_ci 
         OR p.target_role = 'Semua')
  ORDER BY p.tgl_publikasi DESC;
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_get_pengumuman_aktif");
    }
};

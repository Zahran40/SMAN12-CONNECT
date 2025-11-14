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
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_tambah_pengumuman`(IN `judul` VARCHAR(250), IN `isi` TEXT, IN `author` BIGINT)
BEGIN
  INSERT INTO pengumuman (judul, isi_pengumuman, tgl_publikasi, author_id)
  VALUES (judul, isi, CURDATE(), author);
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_tambah_pengumuman");
    }
};

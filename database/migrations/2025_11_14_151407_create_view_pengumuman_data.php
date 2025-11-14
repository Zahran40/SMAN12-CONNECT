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
        DB::unprepared("
            CREATE OR REPLACE VIEW view_pengumuman_data AS
            SELECT 
                p.id_pengumuman,
                p.judul,
                p.isi_pengumuman,
                p.tgl_publikasi,
                p.hari,
                p.target_role,
                u.id AS author_id,
                u.name AS author_name,
                u.email AS author_email,
                u.role AS author_role
            FROM pengumuman p
            JOIN users u ON p.author_id = u.id
            ORDER BY p.tgl_publikasi DESC
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP VIEW IF EXISTS view_pengumuman_data");
    }
};

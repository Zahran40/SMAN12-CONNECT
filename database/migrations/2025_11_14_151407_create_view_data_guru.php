<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            CREATE OR REPLACE VIEW view_data_guru AS
            SELECT 
                g.id_guru,
                g.nip,
                g.nama_lengkap,
                g.tgl_lahir,
                g.tempat_lahir,
                g.alamat,
                g.jenis_kelamin,
                g.no_telepon,
                g.email,
                g.agama,
                g.golongan_darah,
                u.id AS user_id,
                u.name AS username,
                u.email AS user_email,
                u.role
            FROM guru g
            LEFT JOIN users u ON g.user_id = u.id
        ");
    }
    public function down(): void
    {
        DB::unprepared("DROP VIEW IF EXISTS view_data_guru");
    }
};
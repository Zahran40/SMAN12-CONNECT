<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            CREATE OR REPLACE VIEW view_user_permissions AS
            SELECT 
                u.id AS user_id,
                u.name,
                u.email,
                u.role,
                CASE 
                    WHEN u.role = 'admin' THEN g_admin.nama_lengkap
                    WHEN u.role = 'guru' THEN g.nama_lengkap
                    WHEN u.role = 'siswa' THEN s.nama_lengkap
                END AS nama_lengkap,
                CASE 
                    WHEN u.role = 'guru' THEN g.id_guru
                    WHEN u.role = 'siswa' THEN s.id_siswa
                END AS ref_id,
                CASE 
                    WHEN u.role = 'admin' THEN 1
                    ELSE 0
                END AS is_admin,
                CASE 
                    WHEN u.role = 'guru' THEN 1
                    ELSE 0
                END AS is_guru,
                CASE 
                    WHEN u.role = 'siswa' THEN 1
                    ELSE 0
                END AS is_siswa
            FROM users u
            LEFT JOIN guru g ON u.id = g.user_id AND u.role = 'guru'
            LEFT JOIN siswa s ON u.id = s.user_id AND u.role = 'siswa'
            LEFT JOIN guru g_admin ON u.id = g_admin.user_id AND u.role = 'admin'
        ");
    }
    public function down(): void
    {
        DB::unprepared("DROP VIEW IF EXISTS view_user_permissions");
    }
};
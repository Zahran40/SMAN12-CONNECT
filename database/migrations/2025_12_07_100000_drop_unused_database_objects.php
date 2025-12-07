<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * DROP UNUSED DATABASE OBJECTS:
     * - 3 Views yang tidak terpakai
     * - 3 Stored Procedures untuk security (belum diimplementasi)
     * 
     * TOTAL: 6 objects di-drop
     * 
     * VIEWS YANG DI-DROP (3):
     * 1. view_pengumuman_data - Tidak digunakan (sp_get_pengumuman_aktif lebih efisien)
     * 2. view_tunggakan_siswa - Tidak digunakan (fitur belum diimplementasi)
     * 3. view_status_absensi_siswa - Deprecated (sudah tidak relevan setelah refactor)
     * 
     * STORED PROCEDURES YANG DI-DROP (4):
     * 1. sp_check_login_attempts - Security feature belum diimplementasi
     * 2. sp_check_user_permission - RBAC feature belum diimplementasi
     * 3. sp_log_login_attempt - Audit trail belum diimplementasi
     * 4. sp_tambah_pengumuman - Tidak digunakan (menggunakan Eloquent model)
     * 
     * NOTE: SP security bisa dibuat ulang nanti jika diperlukan
     */
    public function up(): void
    {
        // ========================================
        // DROP UNUSED VIEWS (3 total)
        // ========================================
        
        // 1. view_pengumuman_data
        // Alasan: Tidak digunakan di codebase, sp_get_pengumuman_aktif lebih efisien
        DB::statement('DROP VIEW IF EXISTS view_pengumuman_data');
        
        // 2. view_tunggakan_siswa
        // Alasan: Tidak digunakan, fitur laporan tunggakan belum diimplementasi
        DB::statement('DROP VIEW IF EXISTS view_tunggakan_siswa');
        
        // 3. view_status_absensi_siswa
        // Alasan: Deprecated, sudah tidak relevan setelah refactor notification system
        DB::statement('DROP VIEW IF EXISTS view_status_absensi_siswa');
        
        
        // ========================================
        // DROP UNUSED STORED PROCEDURES (4 total)
        // ========================================
        
        // 1. sp_check_login_attempts
        // Alasan: Security feature belum diimplementasi di LoginController
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_check_login_attempts');
        
        // 2. sp_check_user_permission
        // Alasan: RBAC feature belum diimplementasi
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_check_user_permission');
        
        // 3. sp_log_login_attempt
        // Alasan: Audit trail belum diimplementasi
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_log_login_attempt');
        
        // 4. sp_tambah_pengumuman
        // Alasan: Tidak digunakan, menggunakan Eloquent model Pengumuman
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_tambah_pengumuman');
    }

    /**
     * Reverse the migrations.
     * 
     * RECREATE VIEWS jika rollback
     */
    public function down(): void
    {
        // ========================================
        // RECREATE view_pengumuman_data
        // ========================================
        DB::statement("
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
        
        
        // ========================================
        // RECREATE view_tunggakan_siswa
        // ========================================
        DB::statement("
            CREATE OR REPLACE VIEW view_tunggakan_siswa AS
            SELECT 
                s.id_siswa,
                s.nisn,
                s.nama_lengkap AS nama,
                s.user_id,
                ta.id_tahun_ajaran,
                ta.tahun_mulai,
                ta.tahun_selesai,
                ta.semester,
                COUNT(CASE WHEN ps.status = 'Belum Lunas' THEN 1 END) AS jumlah_tunggakan,
                SUM(CASE WHEN ps.status = 'Belum Lunas' THEN ps.jumlah_bayar ELSE 0 END) AS total_tunggakan,
                MIN(CASE WHEN ps.status = 'Belum Lunas' THEN CONCAT(ps.tahun, '-', LPAD(ps.bulan, 2, '0')) END) AS bulan_tertunggak_pertama,
                MAX(CASE WHEN ps.status = 'Belum Lunas' THEN CONCAT(ps.tahun, '-', LPAD(ps.bulan, 2, '0')) END) AS bulan_tertunggak_terakhir
            FROM siswa s
            JOIN tahun_ajaran ta ON ta.status = 'Aktif'
            LEFT JOIN pembayaran_spp ps ON s.id_siswa = ps.siswa_id AND ps.tahun_ajaran_id = ta.id_tahun_ajaran
            GROUP BY s.id_siswa, s.nisn, s.nama_lengkap, s.user_id, ta.id_tahun_ajaran, ta.tahun_mulai, ta.tahun_selesai, ta.semester
        ");
        
        
        // ========================================
        // RECREATE view_status_absensi_siswa
        // ========================================
        DB::statement("
            CREATE OR REPLACE VIEW view_status_absensi_siswa AS
            SELECT 
                s.id_siswa,
                s.nisn,
                s.nama_lengkap,
                da.id_detail_absensi,
                da.status_kehadiran,
                da.keterangan,
                da.dicatat_pada,
                p.id_pertemuan,
                p.tanggal_pertemuan,
                j.id_jadwal,
                mp.nama_mapel,
                k.nama_kelas
            FROM siswa s
            LEFT JOIN detail_absensi da ON s.id_siswa = da.siswa_id
            LEFT JOIN pertemuan p ON da.pertemuan_id = p.id_pertemuan
            LEFT JOIN jadwal_pelajaran j ON p.jadwal_id = j.id_jadwal
            LEFT JOIN mata_pelajaran mp ON j.mapel_id = mp.id_mapel
            LEFT JOIN kelas k ON j.kelas_id = k.id_kelas
            WHERE DATE(p.tanggal_pertemuan) = CURDATE()
            AND da.status_kehadiran IN ('Sakit', 'Izin', 'Alfa')
            ORDER BY da.dicatat_pada DESC
        ");
        
        
        // ========================================
        // RECREATE STORED PROCEDURES
        // ========================================
        
        // 1. sp_check_login_attempts
        DB::unprepared("
            CREATE PROCEDURE sp_check_login_attempts(
                IN p_user_id BIGINT UNSIGNED
            )
            BEGIN
                SELECT COUNT(*) as total_attempts
                FROM login_attempts
                WHERE user_id = p_user_id
                AND status = 'failed'
                AND created_at >= DATE_SUB(NOW(), INTERVAL 15 MINUTE);
            END
        ");
        
        // 2. sp_check_user_permission
        DB::unprepared("
            CREATE PROCEDURE sp_check_user_permission(
                IN p_user_id BIGINT UNSIGNED,
                IN p_permission VARCHAR(50)
            )
            BEGIN
                SELECT COUNT(*) as can_access
                FROM user_permissions
                WHERE user_id = p_user_id
                AND permission_name = p_permission
                AND is_active = 1;
            END
        ");
        
        // 3. sp_log_login_attempt
        DB::unprepared("
            CREATE PROCEDURE sp_log_login_attempt(
                IN p_user_id BIGINT UNSIGNED,
                IN p_ip_address VARCHAR(45),
                IN p_status ENUM('success', 'failed')
            )
            BEGIN
                INSERT INTO login_attempts (user_id, ip_address, status, created_at)
                VALUES (p_user_id, p_ip_address, p_status, NOW());
            END
        ");
    }
};

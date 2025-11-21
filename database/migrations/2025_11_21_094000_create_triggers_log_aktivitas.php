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
        // Trigger untuk tabel nilai (raport) - INSERT
        DB::unprepared('
            DROP TRIGGER IF EXISTS `log_insert_nilai`;
        ');
        
        DB::unprepared('
            CREATE TRIGGER `log_insert_nilai` AFTER INSERT ON `nilai`
            FOR EACH ROW
            BEGIN
                DECLARE v_nama_siswa VARCHAR(255);
                DECLARE v_nama_mapel VARCHAR(255);
                DECLARE v_user_id BIGINT;
                DECLARE v_role VARCHAR(20);
                
                -- Ambil nama siswa dan mapel
                SELECT s.nama_lengkap INTO v_nama_siswa FROM siswa s WHERE s.id_siswa = NEW.siswa_id;
                SELECT mp.nama_mapel INTO v_nama_mapel FROM mata_pelajaran mp WHERE mp.id_mapel = NEW.mapel_id;
                
                -- Ambil user_id dari session atau default ke guru yang input
                SET v_user_id = @current_user_id;
                SET v_role = @current_user_role;
                
                IF v_user_id IS NULL THEN
                    SET v_user_id = 0;
                    SET v_role = "sistem";
                END IF;
                
                INSERT INTO log_aktivitas (
                    jenis_aktivitas, 
                    deskripsi, 
                    user_id, 
                    role, 
                    nama_tabel, 
                    id_referensi, 
                    aksi,
                    ip_address,
                    user_agent
                ) VALUES (
                    "raport",
                    CONCAT("Input nilai ", v_nama_mapel, " untuk siswa ", v_nama_siswa, " - Nilai Akhir: ", NEW.nilai_akhir),
                    v_user_id,
                    v_role,
                    "nilai",
                    NEW.id_nilai,
                    "insert",
                    @current_ip_address,
                    @current_user_agent
                );
            END
        ');

        // Trigger untuk tabel nilai (raport) - UPDATE
        DB::unprepared('
            DROP TRIGGER IF EXISTS `log_update_nilai`;
        ');
        
        DB::unprepared('
            CREATE TRIGGER `log_update_nilai` AFTER UPDATE ON `nilai`
            FOR EACH ROW
            BEGIN
                DECLARE v_nama_siswa VARCHAR(255);
                DECLARE v_nama_mapel VARCHAR(255);
                DECLARE v_user_id BIGINT;
                DECLARE v_role VARCHAR(20);
                
                SELECT s.nama_lengkap INTO v_nama_siswa FROM siswa s WHERE s.id_siswa = NEW.siswa_id;
                SELECT mp.nama_mapel INTO v_nama_mapel FROM mata_pelajaran mp WHERE mp.id_mapel = NEW.mapel_id;
                
                SET v_user_id = @current_user_id;
                SET v_role = @current_user_role;
                
                IF v_user_id IS NULL THEN
                    SET v_user_id = 0;
                    SET v_role = "sistem";
                END IF;
                
                INSERT INTO log_aktivitas (
                    jenis_aktivitas, 
                    deskripsi, 
                    user_id, 
                    role, 
                    nama_tabel, 
                    id_referensi, 
                    aksi,
                    ip_address,
                    user_agent
                ) VALUES (
                    "raport",
                    CONCAT("Update nilai ", v_nama_mapel, " untuk siswa ", v_nama_siswa, " dari ", OLD.nilai_akhir, " menjadi ", NEW.nilai_akhir),
                    v_user_id,
                    v_role,
                    "nilai",
                    NEW.id_nilai,
                    "update",
                    @current_ip_address,
                    @current_user_agent
                );
            END
        ');

        // Trigger untuk tabel pembayaran_spp - INSERT
        DB::unprepared('
            DROP TRIGGER IF EXISTS `log_insert_pembayaran_spp`;
        ');
        
        DB::unprepared('
            CREATE TRIGGER `log_insert_pembayaran_spp` AFTER INSERT ON `pembayaran_spp`
            FOR EACH ROW
            BEGIN
                DECLARE v_nama_siswa VARCHAR(255);
                DECLARE v_user_id BIGINT;
                DECLARE v_role VARCHAR(20);
                
                SELECT s.nama_lengkap INTO v_nama_siswa FROM siswa s WHERE s.id_siswa = NEW.siswa_id;
                
                SET v_user_id = @current_user_id;
                SET v_role = @current_user_role;
                
                IF v_user_id IS NULL THEN
                    SET v_user_id = NEW.siswa_id;
                    SET v_role = "siswa";
                END IF;
                
                INSERT INTO log_aktivitas (
                    jenis_aktivitas, 
                    deskripsi, 
                    user_id, 
                    role, 
                    nama_tabel, 
                    id_referensi, 
                    aksi,
                    ip_address,
                    user_agent
                ) VALUES (
                    "pembayaran_spp",
                    CONCAT("Pembayaran SPP oleh ", v_nama_siswa, " sebesar Rp ", FORMAT(NEW.jumlah_bayar, 0), " - Status: ", NEW.status),
                    v_user_id,
                    v_role,
                    "pembayaran_spp",
                    NEW.id_pembayaran,
                    "insert",
                    @current_ip_address,
                    @current_user_agent
                );
            END
        ');

        // Trigger untuk tabel pembayaran_spp - UPDATE
        DB::unprepared('
            DROP TRIGGER IF EXISTS `log_update_pembayaran_spp`;
        ');
        
        DB::unprepared('
            CREATE TRIGGER `log_update_pembayaran_spp` AFTER UPDATE ON `pembayaran_spp`
            FOR EACH ROW
            BEGIN
                DECLARE v_nama_siswa VARCHAR(255);
                DECLARE v_user_id BIGINT;
                DECLARE v_role VARCHAR(20);
                
                SELECT s.nama_lengkap INTO v_nama_siswa FROM siswa s WHERE s.id_siswa = NEW.siswa_id;
                
                SET v_user_id = @current_user_id;
                SET v_role = @current_user_role;
                
                IF v_user_id IS NULL THEN
                    SET v_user_id = 0;
                    SET v_role = "sistem";
                END IF;
                
                INSERT INTO log_aktivitas (
                    jenis_aktivitas, 
                    deskripsi, 
                    user_id, 
                    role, 
                    nama_tabel, 
                    id_referensi, 
                    aksi,
                    ip_address,
                    user_agent
                ) VALUES (
                    "pembayaran_spp",
                    CONCAT("Update pembayaran SPP ", v_nama_siswa, " - Status: ", OLD.status, " → ", NEW.status),
                    v_user_id,
                    v_role,
                    "pembayaran_spp",
                    NEW.id_pembayaran,
                    "update",
                    @current_ip_address,
                    @current_user_agent
                );
            END
        ');

        // Trigger untuk tabel detail_absensi - INSERT
        DB::unprepared('
            DROP TRIGGER IF EXISTS `log_insert_absensi`;
        ');
        
        DB::unprepared('
            CREATE TRIGGER `log_insert_absensi` AFTER INSERT ON `detail_absensi`
            FOR EACH ROW
            BEGIN
                DECLARE v_nama_siswa VARCHAR(255);
                DECLARE v_nama_mapel VARCHAR(255);
                DECLARE v_user_id BIGINT;
                DECLARE v_role VARCHAR(20);
                
                SELECT s.nama_lengkap INTO v_nama_siswa FROM siswa s WHERE s.id_siswa = NEW.siswa_id;
                SELECT mp.nama_mapel INTO v_nama_mapel 
                FROM mata_pelajaran mp 
                JOIN jadwal_pelajaran jp ON jp.mapel_id = mp.id_mapel
                JOIN pertemuan p ON p.jadwal_id = jp.id_jadwal
                WHERE p.id_pertemuan = NEW.pertemuan_id;
                
                SET v_user_id = @current_user_id;
                SET v_role = @current_user_role;
                
                IF v_user_id IS NULL THEN
                    SET v_user_id = 0;
                    SET v_role = "guru";
                END IF;
                
                INSERT INTO log_aktivitas (
                    jenis_aktivitas, 
                    deskripsi, 
                    user_id, 
                    role, 
                    nama_tabel, 
                    id_referensi, 
                    aksi,
                    ip_address,
                    user_agent
                ) VALUES (
                    "absensi",
                    CONCAT("Input absensi ", v_nama_mapel, " untuk siswa ", v_nama_siswa, " - Status: ", NEW.status_kehadiran),
                    v_user_id,
                    v_role,
                    "detail_absensi",
                    NEW.id_detail_absensi,
                    "insert",
                    @current_ip_address,
                    @current_user_agent
                );
            END
        ');

        // Trigger untuk tabel detail_tugas - INSERT
        DB::unprepared('
            DROP TRIGGER IF EXISTS `log_insert_detail_tugas`;
        ');
        
        DB::unprepared('
            CREATE TRIGGER `log_insert_detail_tugas` AFTER INSERT ON `detail_tugas`
            FOR EACH ROW
            BEGIN
                DECLARE v_nama_siswa VARCHAR(255);
                DECLARE v_judul_tugas VARCHAR(255);
                DECLARE v_user_id BIGINT;
                DECLARE v_role VARCHAR(20);
                
                SELECT s.nama_lengkap INTO v_nama_siswa FROM siswa s WHERE s.id_siswa = NEW.siswa_id;
                SELECT t.judul_tugas INTO v_judul_tugas FROM tugas t WHERE t.id_tugas = NEW.tugas_id;
                
                SET v_user_id = @current_user_id;
                SET v_role = @current_user_role;
                
                IF v_user_id IS NULL THEN
                    SET v_user_id = NEW.siswa_id;
                    SET v_role = "siswa";
                END IF;
                
                INSERT INTO log_aktivitas (
                    jenis_aktivitas, 
                    deskripsi, 
                    user_id, 
                    role, 
                    nama_tabel, 
                    id_referensi, 
                    aksi,
                    ip_address,
                    user_agent
                ) VALUES (
                    "tugas",
                    CONCAT("Siswa ", v_nama_siswa, " mengumpulkan tugas: ", v_judul_tugas),
                    v_user_id,
                    v_role,
                    "detail_tugas",
                    NEW.id_detail_tugas,
                    "insert",
                    @current_ip_address,
                    @current_user_agent
                );
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS `log_insert_nilai`');
        DB::unprepared('DROP TRIGGER IF EXISTS `log_update_nilai`');
        DB::unprepared('DROP TRIGGER IF EXISTS `log_insert_pembayaran_spp`');
        DB::unprepared('DROP TRIGGER IF EXISTS `log_update_pembayaran_spp`');
        DB::unprepared('DROP TRIGGER IF EXISTS `log_insert_absensi`');
        DB::unprepared('DROP TRIGGER IF EXISTS `log_insert_detail_tugas`');
    }
};

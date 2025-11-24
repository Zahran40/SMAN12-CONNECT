<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    public function up(): void
    {
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
                    "input_nilai",
                    CONCAT("Input nilai untuk ", v_nama_siswa, " - ", v_nama_mapel),
                    v_user_id,
                    v_role,
                    "nilai",
                    NEW.id_nilai,
                    "INSERT",
                    @current_ip_address,
                    @current_user_agent
                );
            END
        ');
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
                    "update_nilai",
                    CONCAT("Update nilai untuk ", v_nama_siswa, " - ", v_nama_mapel),
                    v_user_id,
                    v_role,
                    "nilai",
                    NEW.id_nilai,
                    "UPDATE",
                    @current_ip_address,
                    @current_user_agent
                );
            END
        ');
        DB::unprepared('
            DROP TRIGGER IF EXISTS `log_delete_nilai`;
        ');
        DB::unprepared('
            CREATE TRIGGER `log_delete_nilai` AFTER DELETE ON `nilai`
            FOR EACH ROW
            BEGIN
                DECLARE v_nama_siswa VARCHAR(255);
                DECLARE v_nama_mapel VARCHAR(255);
                DECLARE v_user_id BIGINT;
                DECLARE v_role VARCHAR(20);
                SELECT s.nama_lengkap INTO v_nama_siswa FROM siswa s WHERE s.id_siswa = OLD.siswa_id;
                SELECT mp.nama_mapel INTO v_nama_mapel FROM mata_pelajaran mp WHERE mp.id_mapel = OLD.mapel_id;
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
                    "hapus_nilai",
                    CONCAT("Hapus nilai untuk ", v_nama_siswa, " - ", v_nama_mapel),
                    v_user_id,
                    v_role,
                    "nilai",
                    OLD.id_nilai,
                    "DELETE",
                    @current_ip_address,
                    @current_user_agent
                );
            END
        ');
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
                SELECT s.nama_lengkap INTO v_nama_siswa 
                FROM siswa s 
                WHERE s.id_siswa = NEW.siswa_id;
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
                    "buat_tagihan_spp",
                    CONCAT("Buat tagihan SPP untuk ", v_nama_siswa, " - ", NEW.nama_tagihan, " Rp ", FORMAT(NEW.jumlah_bayar, 0)),
                    v_user_id,
                    v_role,
                    "pembayaran_spp",
                    NEW.id_pembayaran,
                    "INSERT",
                    @current_ip_address,
                    @current_user_agent
                );
            END
        ');
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
                SELECT s.nama_lengkap INTO v_nama_siswa 
                FROM siswa s 
                WHERE s.id_siswa = NEW.siswa_id;
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
                    "update_pembayaran_spp",
                    CONCAT("Update pembayaran SPP ", v_nama_siswa, " - Status: ", NEW.status),
                    v_user_id,
                    v_role,
                    "pembayaran_spp",
                    NEW.id_pembayaran,
                    "UPDATE",
                    @current_ip_address,
                    @current_user_agent
                );
            END
        ');
    }
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS `log_insert_nilai`');
        DB::unprepared('DROP TRIGGER IF EXISTS `log_update_nilai`');
        DB::unprepared('DROP TRIGGER IF EXISTS `log_delete_nilai`');
        DB::unprepared('DROP TRIGGER IF EXISTS `log_insert_pembayaran_spp`');
        DB::unprepared('DROP TRIGGER IF EXISTS `log_update_pembayaran_spp`');
    }
};
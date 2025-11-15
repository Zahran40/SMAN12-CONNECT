-- ============================================
-- DATABASE GRANTS FOR SMAN12-CONNECT
-- Role-Based Access Control (RBAC)
-- ============================================
-- 
-- FILE INI HANYA UNTUK MEMBUAT USER TEMPLATE
-- Grants untuk user individu akan otomatis diterapkan oleh Laravel User Model
-- 
-- ============================================

-- 1. CREATE TEMPLATE USERS (Opsional - hanya untuk reference)
CREATE USER IF NOT EXISTS 'guru_sia'@'localhost' IDENTIFIED BY 'PasswordGuru123!';
CREATE USER IF NOT EXISTS 'siswa_sia'@'localhost' IDENTIFIED BY 'PasswordSiswa123!';
CREATE USER IF NOT EXISTS 'admin_sia'@'localhost' IDENTIFIED BY 'PasswordAdmin123!';

-- Select Database
USE `sman12-connect`;

-- ============================================
-- 2. TEMPLATE GRANTS (Otomatis via Laravel)
-- ============================================

-- NOTE: Grants ini hanya reference!
-- Setiap user baru akan otomatis mendapat grants via User Model
-- Format username: {role}_user_{id}
-- Contoh: guru_user_1, siswa_user_2, admin_user_3

-- ADMIN TEMPLATE
GRANT ALL PRIVILEGES ON `sman12-connect`.* TO 'admin_sia'@'localhost';

-- GURU TEMPLATE (sebagai reference saja)
-- Actual grants akan dibuat dengan username: guru_user_{id}
GRANT SELECT, INSERT, UPDATE, DELETE ON `sman12-connect`.`materi` TO 'guru_sia'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON `sman12-connect`.`tugas` TO 'guru_sia'@'localhost';
GRANT SELECT, UPDATE ON `sman12-connect`.`detail_tugas` TO 'guru_sia'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON `sman12-connect`.`nilai` TO 'guru_sia'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON `sman12-connect`.`detail_absensi` TO 'guru_sia'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON `sman12-connect`.`pertemuan` TO 'guru_sia'@'localhost';
GRANT SELECT ON `sman12-connect`.`siswa` TO 'guru_sia'@'localhost';
GRANT SELECT ON `sman12-connect`.`kelas` TO 'guru_sia'@'localhost';
GRANT SELECT ON `sman12-connect`.`jadwal_pelajaran` TO 'guru_sia'@'localhost';
GRANT SELECT ON `sman12-connect`.`pengumuman` TO 'guru_sia'@'localhost';
GRANT SELECT ON `sman12-connect`.`guru` TO 'guru_sia'@'localhost';
GRANT SELECT ON `sman12-connect`.`tahun_ajaran` TO 'guru_sia'@'localhost';
GRANT SELECT ON `sman12-connect`.`mata_pelajaran` TO 'guru_sia'@'localhost';
GRANT SELECT ON `sman12-connect`.`users` TO 'guru_sia'@'localhost';

-- SISWA TEMPLATE (sebagai reference saja)
-- Actual grants akan dibuat dengan username: siswa_user_{id}
GRANT SELECT ON `sman12-connect`.`materi` TO 'siswa_sia'@'localhost';
GRANT SELECT ON `sman12-connect`.`tugas` TO 'siswa_sia'@'localhost';
GRANT SELECT, INSERT, UPDATE ON `sman12-connect`.`detail_tugas` TO 'siswa_sia'@'localhost';
GRANT SELECT ON `sman12-connect`.`nilai` TO 'siswa_sia'@'localhost';
GRANT SELECT ON `sman12-connect`.`detail_absensi` TO 'siswa_sia'@'localhost';
GRANT SELECT ON `sman12-connect`.`pembayaran_spp` TO 'siswa_sia'@'localhost';
GRANT SELECT ON `sman12-connect`.`jadwal_pelajaran` TO 'siswa_sia'@'localhost';
GRANT SELECT ON `sman12-connect`.`pengumuman` TO 'siswa_sia'@'localhost';
GRANT SELECT ON `sman12-connect`.`siswa` TO 'siswa_sia'@'localhost';
GRANT SELECT ON `sman12-connect`.`kelas` TO 'siswa_sia'@'localhost';
GRANT SELECT ON `sman12-connect`.`pertemuan` TO 'siswa_sia'@'localhost';
GRANT SELECT ON `sman12-connect`.`tahun_ajaran` TO 'siswa_sia'@'localhost';
GRANT SELECT ON `sman12-connect`.`mata_pelajaran` TO 'siswa_sia'@'localhost';
GRANT SELECT ON `sman12-connect`.`users` TO 'siswa_sia'@'localhost';

-- ============================================
-- 3. APPLY PRIVILEGES
-- ============================================

FLUSH PRIVILEGES;

-- ============================================
-- 4. VERIFY TEMPLATE GRANTS
-- ============================================

SHOW GRANTS FOR 'guru_sia'@'localhost';
SHOW GRANTS FOR 'siswa_sia'@'localhost';
SHOW GRANTS FOR 'admin_sia'@'localhost';

-- ============================================
-- NOTES:
-- ============================================
-- 1. User template (guru_sia, siswa_sia, admin_sia) hanya untuk reference
-- 2. Setiap user Laravel baru akan otomatis mendapat MySQL user sendiri
-- 3. Format: {role}_user_{id} (contoh: guru_user_1, siswa_user_5)
-- 4. Grants otomatis diterapkan via User Model Events
-- 5. Saat user dihapus, MySQL user juga otomatis dihapus
-- 6. Saat role berubah, grants otomatis di-update
-- ============================================

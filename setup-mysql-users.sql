-- =====================================================
-- SETUP MySQL Users untuk SMAN12-CONNECT
-- Role-Based Database Access Control
-- =====================================================
-- 
-- File ini otomatis membuat 3 MySQL users:
-- 1. admin_sia  - Full access untuk role admin
-- 2. guru_sia   - Limited access untuk role guru
-- 3. siswa_sia  - Read-only + limited write untuk role siswa
--
-- Cara pakai:
-- mysql -u root -p < setup-mysql-users.sql
--
-- =====================================================

-- Hapus user lama jika ada (untuk clean install)
DROP USER IF EXISTS 'admin_sia'@'localhost';
DROP USER IF EXISTS 'guru_sia'@'localhost';
DROP USER IF EXISTS 'siswa_sia'@'localhost';

-- =====================================================
-- BUAT 3 MYSQL USERS
-- =====================================================

CREATE USER 'admin_sia'@'localhost' IDENTIFIED BY 'admin123';
CREATE USER 'guru_sia'@'localhost' IDENTIFIED BY 'guru123';
CREATE USER 'siswa_sia'@'localhost' IDENTIFIED BY 'siswa123';

-- =====================================================
-- GRANTS UNTUK ADMIN (Full Access)
-- =====================================================

GRANT ALL PRIVILEGES ON sman_connect.* TO 'admin_sia'@'localhost';

-- =====================================================
-- GRANTS UNTUK GURU
-- =====================================================

-- Read-only untuk semua tabel
GRANT SELECT ON sman_connect.* TO 'guru_sia'@'localhost';

-- Full CRUD untuk tabel pembelajaran
GRANT SELECT, INSERT, UPDATE, DELETE ON sman_connect.detail_absensi TO 'guru_sia'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON sman_connect.nilai TO 'guru_sia'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON sman_connect.materi TO 'guru_sia'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON sman_connect.tugas TO 'guru_sia'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON sman_connect.pertemuan TO 'guru_sia'@'localhost';

-- Update nilai tugas yang dikumpulkan siswa
GRANT SELECT, UPDATE ON sman_connect.detail_tugas TO 'guru_sia'@'localhost';

-- =====================================================
-- GRANTS UNTUK SISWA
-- =====================================================

-- Read-only untuk semua tabel
GRANT SELECT ON sman_connect.* TO 'siswa_sia'@'localhost';

-- Insert absensi (scan QR)
GRANT SELECT, INSERT ON sman_connect.detail_absensi TO 'siswa_sia'@'localhost';

-- Submit tugas dan update file
GRANT SELECT, INSERT, UPDATE ON sman_connect.detail_tugas TO 'siswa_sia'@'localhost';

-- Bayar SPP
GRANT SELECT, INSERT, UPDATE ON sman_connect.pembayaran_spp TO 'siswa_sia'@'localhost';

-- =====================================================
-- APPLY CHANGES
-- =====================================================

FLUSH PRIVILEGES;

-- =====================================================
-- VERIFIKASI
-- =====================================================

SELECT 
    '✅ MySQL Users Created Successfully!' as Status,
    COUNT(*) as TotalUsers
FROM mysql.user 
WHERE User IN ('admin_sia', 'guru_sia', 'siswa_sia');

SELECT 
    User, 
    Host,
    CASE 
        WHEN User = 'admin_sia' THEN 'Admin - Full Access'
        WHEN User = 'guru_sia' THEN 'Guru - Limited Access (Pembelajaran)'
        WHEN User = 'siswa_sia' THEN 'Siswa - Read-Only + Limited Write'
    END as Description
FROM mysql.user 
WHERE User IN ('admin_sia', 'guru_sia', 'siswa_sia')
ORDER BY User;

-- =====================================================
-- SELESAI
-- =====================================================

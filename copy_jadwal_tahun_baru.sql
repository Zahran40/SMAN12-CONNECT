-- Script untuk copy jadwal dari tahun ajaran lama ke tahun baru
-- INSTRUKSI PENGGUNAAN:
-- 1. Edit variabel @tahun_lama dan @tahun_baru sesuai kebutuhan
-- 2. Jalankan script ini di MySQL: mysql -u root sman_connect < copy_jadwal_tahun_baru.sql

-- Set tahun ajaran sumber (yang mau dicopy) dan tujuan (tahun baru)
SET @tahun_lama = 4;  -- 2025/2026 Ganjil
SET @tahun_baru = 6;  -- 2026/2027 Ganjil

-- Tampilkan info
SELECT CONCAT('Copy jadwal dari tahun ajaran ID=', @tahun_lama, ' ke ID=', @tahun_baru) as info;

-- Copy semua jadwal pelajaran
INSERT INTO jadwal_pelajaran (kelas_id, mapel_id, guru_id, hari, jam_mulai, jam_selesai, tahun_ajaran_id)
SELECT 
    kelas_id, 
    mapel_id, 
    guru_id, 
    hari, 
    jam_mulai, 
    jam_selesai, 
    @tahun_baru as tahun_ajaran_id
FROM jadwal_pelajaran
WHERE tahun_ajaran_id = @tahun_lama;

-- Tampilkan hasil
SELECT CONCAT('Berhasil copy ', ROW_COUNT(), ' jadwal') as hasil;

-- Verifikasi: Tampilkan jadwal yang baru dibuat
SELECT 
    jp.id_jadwal,
    k.nama_kelas,
    mp.nama_mapel,
    g.nama_lengkap as guru,
    jp.hari,
    CONCAT(jp.jam_mulai, ' - ', jp.jam_selesai) as jam,
    CONCAT(ta.tahun_mulai, '/', ta.tahun_selesai, ' ', ta.semester) as tahun_ajaran
FROM jadwal_pelajaran jp
JOIN kelas k ON jp.kelas_id = k.id_kelas
JOIN mata_pelajaran mp ON jp.mapel_id = mp.id_mapel
JOIN guru g ON jp.guru_id = g.id_guru
JOIN tahun_ajaran ta ON jp.tahun_ajaran_id = ta.id_tahun_ajaran
WHERE jp.tahun_ajaran_id = @tahun_baru
ORDER BY k.nama_kelas, jp.hari, jp.jam_mulai;

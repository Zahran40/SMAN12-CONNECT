-- Script untuk update jadwal ke tahun ajaran aktif
-- Dan update kelas_id ke kelas yang sesuai dengan tahun ajaran aktif

-- Cek tahun ajaran aktif
SELECT 'Tahun Ajaran Aktif:' as info;
SELECT id_tahun_ajaran, CONCAT(tahun_mulai, '/', tahun_selesai, ' ', semester) as tahun_ajaran, status 
FROM tahun_ajaran 
WHERE status = 'Aktif';

-- Cek jadwal guru Siti Nurhaliza sebelum update
SELECT 'Jadwal Guru Sebelum Update:' as info;
SELECT jp.id_jadwal, k.nama_kelas, mp.nama_mapel, jp.hari, jp.jam_mulai, 
       jp.tahun_ajaran_id, jp.kelas_id
FROM jadwal_pelajaran jp
JOIN kelas k ON jp.kelas_id = k.id_kelas
JOIN mata_pelajaran mp ON jp.mapel_id = mp.id_mapel
WHERE jp.guru_id = 1;

-- Update semua jadwal guru ke tahun ajaran aktif (id=6)
-- Juga update kelas_id ke kelas yang sesuai dengan tahun ajaran 2026/2027

-- X-MIPA-1 Senin Matematika: update ke kelas X-MIPA-1 tahun 2026/2027 (kelas_id=61)
UPDATE jadwal_pelajaran 
SET tahun_ajaran_id = 6, kelas_id = 61 
WHERE id_jadwal = 1 AND guru_id = 1;

-- X-MIPA-1 Selasa Fisika: update ke kelas X-MIPA-1 tahun 2026/2027 (kelas_id=61)  
UPDATE jadwal_pelajaran 
SET tahun_ajaran_id = 6, kelas_id = 61 
WHERE id_jadwal = 2 AND guru_id = 1;

-- XII-MIPA-5 Kamis Fisika: update ke kelas XII-MIPA-5 tahun 2026/2027 (kelas_id=85)
UPDATE jadwal_pelajaran 
SET tahun_ajaran_id = 6, kelas_id = 85 
WHERE id_jadwal = 3 AND guru_id = 1;

-- XI-MIPA-5 Kamis Fisika: update ke kelas XI-MIPA-5 tahun 2026/2027 (kelas_id=75)
UPDATE jadwal_pelajaran 
SET tahun_ajaran_id = 6, kelas_id = 75 
WHERE id_jadwal = 4 AND guru_id = 1;

-- X-IPS-2 Senin Fisika: update ke kelas X-IPS-2 tahun 2026/2027 (kelas_id=67)
UPDATE jadwal_pelajaran 
SET tahun_ajaran_id = 6, kelas_id = 67 
WHERE id_jadwal = 6 AND guru_id = 1;

-- Verifikasi hasil update
SELECT 'Jadwal Guru Setelah Update:' as info;
SELECT jp.id_jadwal, k.nama_kelas, mp.nama_mapel, jp.hari, jp.jam_mulai,
       CONCAT(ta.tahun_mulai, '/', ta.tahun_selesai, ' ', ta.semester) as tahun_ajaran,
       ta.status,
       (SELECT COUNT(*) FROM siswa_kelas sk 
        WHERE sk.kelas_id = jp.kelas_id 
        AND sk.tahun_ajaran_id = jp.tahun_ajaran_id 
        AND sk.status = 'Aktif') as jumlah_siswa
FROM jadwal_pelajaran jp
JOIN kelas k ON jp.kelas_id = k.id_kelas
JOIN mata_pelajaran mp ON jp.mapel_id = mp.id_mapel
JOIN tahun_ajaran ta ON jp.tahun_ajaran_id = ta.id_tahun_ajaran
WHERE jp.guru_id = 1
ORDER BY jp.id_jadwal;

SELECT 'Update selesai! Refresh halaman guru untuk melihat jadwal.' as hasil;

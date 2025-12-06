# Dokumentasi Database Views

> **Status**: 14 views aktif digunakan
> **Update Terakhir**: 6 Desember 2025

## 📊 Ringkasan

Database memiliki **14 views** yang dioptimalkan untuk meningkatkan performa query dan mengurangi kompleksitas kode.

---

## ✅ VIEWS YANG DIGUNAKAN (14 Total)

### 1. view_siswa_kelas
**Digunakan di**: Admin - Data Master (Tab Siswa)  
**Fungsi**: List siswa dengan info kelas dan tahun ajaran

### 2. view_jadwal_mengajar
**Digunakan di**: Guru - Beranda  
**Fungsi**: Jadwal mengajar guru per hari

### 3. view_jadwal_siswa
**Digunakan di**: Siswa - Beranda  
**Fungsi**: Jadwal pelajaran siswa per hari

### 4. view_presensi_aktif
**Digunakan di**: Siswa - Beranda  
**Fungsi**: Presensi yang sedang aktif hari ini

### 5. view_data_guru
**Digunakan di**: Admin - Data Master (Tab Guru)  
**Fungsi**: List guru dengan info user account

### 6. view_pembayaran_spp
**Digunakan di**: Admin - Manajemen Pembayaran  
**Fungsi**: List pembayaran SPP dengan filter

### 7. view_tugas_siswa
**Digunakan di**: Guru - Detail Tugas  
**Fungsi**: Status pengumpulan tugas per siswa

### 8. view_materi_guru
**Digunakan di**: Guru - Detail Materi (Tab "Semua Materi")  
**Fungsi**: List semua materi yang diupload  
**Catatan**: Menampilkan materi dari semua semester (tidak filter tahun ajaran aktif)

### 9. view_nilai_siswa
**Digunakan di**: Siswa - Raport  
**Fungsi**: Nilai dengan grade otomatis (A/B/C/D/E)

### 10. view_jadwal_guru
**Digunakan di**: Admin - Detail Kelas  
**Fungsi**: Jadwal pelajaran untuk manajemen kelas

### 11. view_dashboard_siswa
**Digunakan di**: Siswa - Beranda  
**Fungsi**: Statistik dashboard (total mapel, tagihan, rata-rata nilai)

### 12. view_kelas_detail
**Digunakan di**: Admin - Data Master (Tab Kelas)  
**Fungsi**: List kelas dengan statistik lengkap

### 13. view_mapel_diajarkan
**Digunakan di**: Admin - Data Master (Tab Mapel)  
**Fungsi**: Mata pelajaran dengan guru pengajar

### 14. view_guru_mengajar
**Digunakan di**: Admin - Detail Guru  
**Fungsi**: Profil guru dengan mata pelajaran yang diajar

---

## 📍 Panduan Akses Per Role

### 👨‍🎓 SISWA
- **Beranda** (`/siswa/beranda`): Jadwal, presensi aktif, statistik dashboard
- **Raport** (`/siswa/raport`): Nilai dengan grade otomatis

### 👨‍🏫 GURU
- **Beranda** (`/guru/beranda`): Jadwal mengajar
- **Detail Materi** (`/guru/materi/{jadwal_id}`): Tab "Semua Materi"
- **Detail Tugas** (`/guru/detail-tugas/{tugas_id}`): Status pengumpulan siswa

### 👔 ADMIN
- **Data Master - Siswa** (`/admin/data-master?tab=siswa`): List siswa
- **Data Master - Guru** (`/admin/data-master?tab=guru`): List guru
- **Data Master - Kelas** (`/admin/data-master?tab=kelas`): List kelas dengan statistik
- **Data Master - Mapel** (`/admin/data-master?tab=mapel`): Mata pelajaran
- **Detail Guru** (`/admin/data-master/guru/{id}`): Profil guru
- **Detail Kelas** (`/admin/kelas/{tahun_ajaran}/{kelas_id}`): Manajemen jadwal
- **Pembayaran SPP** (`/admin/pembayaran`): Tracking pembayaran

---

## 🔧 File Controller yang Dimodifikasi

1. `app/Http/Controllers/Admin/DataMasterController.php`
2. `app/Http/Controllers/Admin/PembayaranController.php`
3. `app/Http/Controllers/Admin/KelasController.php`
4. `app/Http/Controllers/Guru/MateriController.php`
5. `app/Http/Controllers/Siswa/RaportController.php`
6. `app/Http/Controllers/SiswaController.php`

---

## ⚡ Manfaat Penggunaan Views

✅ Mengurangi N+1 query problem  
✅ Query lebih cepat dengan pre-computed joins  
✅ Kode lebih bersih dan mudah maintenance  
✅ Auto-calculated fields (grade, status, statistik)  
✅ Konsisten dengan semester logic (Ganjil/Genap)

---

## ⚠️ Catatan Penting

### view_materi_guru (Update)
View ini **TIDAK** memfilter berdasarkan status tahun ajaran aktif. Artinya, materi dari semester Ganjil dan Genap akan ditampilkan semua. Ini diperlukan agar guru bisa melihat semua materi yang pernah diupload, tidak hanya dari semester yang sedang aktif.

**Query tanpa filter**:
```sql
SELECT * FROM view_materi_guru 
WHERE id_guru = ? AND id_jadwal = ? 
ORDER BY tgl_upload DESC
```

### Compatibility
- ✅ Aman untuk semester switching (Ganjil ↔ Genap)
- ✅ Kompatibel dengan tahun ajaran baru
- ✅ Tidak ada breaking changes pada existing code

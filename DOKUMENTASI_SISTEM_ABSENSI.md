# 🎓 Sistem Absensi SMAN12-CONNECT - DOKUMENTASI LENGKAP

## ✅ STATUS: 100% SELESAI

Sistem absensi berbasis waktu dengan fitur one-click attendance untuk siswa dan manajemen lengkap untuk guru telah selesai dibuat.

---

## 📋 FITUR UTAMA

### 1. **Siswa**
- ✅ **One-Click Attendance**: Klik tombol "Presensi" → otomatis status "Hadir"
- ✅ **Dashboard Real-time**: Presensi aktif hari ini muncul di beranda
- ✅ **Validasi Waktu**: Hanya bisa absen jika dalam window waktu yang ditentukan
- ✅ **Riwayat Lengkap**: Lihat semua riwayat kehadiran dengan status dan keterangan
- ✅ **Visual Feedback**: Alert success/error/info yang jelas

### 2. **Guru**
- ✅ **Kelola Presensi**: Lihat semua siswa dengan status kehadirannya
- ✅ **Update Status**: Ubah status siswa (Hadir/Sakit/Izin/Alfa) dengan keterangan
- ✅ **Submit & Lock**: Lock presensi setelah selesai (data terkunci)
- ✅ **Statistik Real-time**: Total hadir, sakit/izin, alfa ditampilkan
- ✅ **Modal Interaktif**: Update status dengan UI yang user-friendly

### 3. **Admin**
- ✅ **Unlock Presensi**: Bisa membuka kembali presensi yang sudah di-submit
- ✅ **Full Control**: Akses penuh untuk edit data yang terkunci

---

## 🗄️ DATABASE SCHEMA

### Tabel `pertemuan` (9 kolom baru):
```sql
tanggal_absen_dibuka  DATE        -- Tanggal absensi dibuka
tanggal_absen_ditutup DATE        -- Tanggal absensi ditutup
jam_absen_buka        TIME        -- Jam buka absensi
jam_absen_tutup       TIME        -- Jam tutup absensi
waktu_absen_dibuka    DATETIME    -- Gabungan tanggal + jam buka
waktu_absen_ditutup   DATETIME    -- Gabungan tanggal + jam tutup
is_submitted          BOOLEAN     -- Status sudah di-submit atau belum
submitted_at          DATETIME    -- Kapan di-submit
submitted_by          BIGINT      -- Siapa yang submit (FK ke users.id)
```

### Tabel `detail_absensi` (existing):
```sql
id_detail_absensi  BIGINT PRIMARY KEY
pertemuan_id       BIGINT (FK ke pertemuan)
siswa_id           BIGINT (FK ke siswa)
status_kehadiran   ENUM('Hadir','Izin','Sakit','Alfa')
keterangan         VARCHAR(500)
dicatat_pada       DATETIME
```

---

## 📁 FILE-FILE YANG DIBUAT/DIMODIFIKASI

### **Migrations**
- ✅ `2025_11_16_142723_add_absensi_fields_to_pertemuan_table.php`

### **Models**
- ✅ `app/Models/Pertemuan.php` - Tambah fillable, casts, helper methods
- ✅ `app/Models/DetailAbsensi.php` - Model baru dengan relasi dan scopes

### **Controllers**
- ✅ `app/Http/Controllers/SiswaController.php` - Update beranda()
- ✅ `app/Http/Controllers/Siswa/PresensiController.php` - NEW
  - `index()` - List riwayat presensi
  - `detail()` - Detail satu pertemuan
  - `absen()` - One-click attendance
  
- ✅ `app/Http/Controllers/Guru/PresensiController.php` - NEW
  - `index()` - List kelas hari ini
  - `detail()` - Manage attendance
  - `updateStatus()` - Update status siswa
  - `submit()` - Lock presensi
  - `unlock()` - Unlock (admin only)

### **Routes** (`routes/web.php`)
```php
// Siswa
GET  /siswa/presensi
GET  /siswa/presensi/{pertemuan_id}
POST /siswa/presensi/absen/{pertemuan_id}

// Guru
GET  /guru/presensi
GET  /guru/presensi/{pertemuan_id}
POST /guru/presensi/{pertemuan_id}/update
POST /guru/presensi/{pertemuan_id}/submit
POST /guru/presensi/{pertemuan_id}/unlock
```

### **Views**
- ✅ `resources/views/siswa/beranda.blade.php` - Presensi aktif real-time
- ✅ `resources/views/siswa/presensi.blade.php` - Riwayat kehadiran
- ✅ `resources/views/Guru/presensi.blade.php` - List kelas hari ini
- ✅ `resources/views/Guru/detailpresensi.blade.php` - Manage attendance dengan modal
- ✅ `resources/views/layouts/siswa/app.blade.php` - Alert messages
- ✅ `resources/views/layouts/guru/app.blade.php` - Alert messages

### **Helper Scripts**
- ✅ `set_waktu_absensi.php` - Script untuk testing (set waktu absensi)

---

## 🔧 HELPER METHODS DI MODEL

### `Pertemuan::isAbsensiOpen()`
Mengecek apakah waktu absensi sedang terbuka.
```php
$pertemuan->isAbsensiOpen(); // true/false
```

### `Pertemuan::canEditAbsensi($user)`
Mengecek apakah absensi bisa diedit.
```php
$pertemuan->canEditAbsensi(auth()->user()); // true/false
// true jika: belum submit ATAU user adalah admin
```

---

## 🎯 ALUR KERJA SISTEM

### **Alur Siswa:**
1. Siswa masuk ke dashboard → melihat "Presensi Berlangsung"
2. Jika ada presensi aktif (sekarang di antara waktu_absen_dibuka dan waktu_absen_ditutup)
3. Siswa klik tombol "Presensi"
4. Sistem otomatis create record dengan status "Hadir"
5. Tombol berubah jadi "✓ Sudah Absen"

### **Alur Guru:**
1. Guru masuk ke menu Presensi → pilih kelas hari ini
2. Guru melihat daftar semua siswa dengan status kehadiran
3. Guru bisa klik "Set Status" atau "Ubah" untuk update status siswa
4. Modal muncul dengan 4 pilihan: Hadir/Sakit/Izin/Alfa + keterangan opsional
5. Setelah semua selesai, guru klik "Submit & Lock Presensi"
6. Data terkunci (is_submitted = true)

### **Alur Admin:**
1. Admin bisa lihat semua presensi yang sudah di-submit
2. Jika ada kesalahan, admin bisa klik "🔓 Buka Kembali"
3. Data jadi bisa diedit lagi oleh guru

---

## 🧪 CARA TESTING

### 1. **Setup Data Pertemuan**
```bash
php set_waktu_absensi.php
```

Atau manual via MySQL:
```sql
-- Update pertemuan yang sudah ada
UPDATE pertemuan 
SET tanggal_absen_dibuka = '2025-11-16',
    tanggal_absen_ditutup = '2025-11-16',
    jam_absen_buka = '14:00:00',
    jam_absen_tutup = '16:00:00',
    waktu_absen_dibuka = '2025-11-16 14:00:00',
    waktu_absen_ditutup = '2025-11-16 16:00:00'
WHERE id_pertemuan = 33;
```

### 2. **Login Sebagai Siswa**
- Buka: `/siswa/beranda`
- Cek section "Presensi Berlangsung"
- Klik tombol "Presensi"
- Lihat perubahan status

### 3. **Login Sebagai Guru**
- Buka: `/guru/presensi`
- Pilih kelas/mata pelajaran
- Kelola status kehadiran siswa
- Submit presensi

### 4. **Login Sebagai Admin**
- Akses presensi yang sudah di-submit
- Test unlock functionality

---

## 🎨 UI/UX HIGHLIGHTS

### **Beranda Siswa**
- Card "Presensi Berlangsung" dengan data real-time
- Tombol biru "Presensi" yang jelas
- Badge hijau "✓ Sudah Absen" setelah presensi
- Empty state dengan icon jika tidak ada presensi aktif

### **Detail Presensi Guru**
- Tabel lengkap dengan 7 kolom (No, NIS, Nama, Status, Keterangan, Waktu, Aksi)
- Status card dengan 4 metrik (Total, Hadir, Sakit/Izin, Alfa)
- Badge status berwarna (hijau=Hadir, kuning=Sakit, biru=Izin, merah=Alfa)
- Modal update dengan radio buttons yang interactive
- Tombol "Submit & Lock" yang prominent

### **Alert Messages**
- Success: Hijau dengan icon checkmark
- Error: Merah dengan icon X
- Info: Biru dengan icon info
- Auto-dismissable dengan tombol close

---

## 📊 BUSINESS RULES

1. **Waktu Absensi**:
   - Siswa hanya bisa absen jika `now()` berada di antara `waktu_absen_dibuka` dan `waktu_absen_ditutup`
   - Jika di luar waktu, muncul error message

2. **One-Click Attendance**:
   - Siswa klik sekali → langsung status "Hadir"
   - Tidak ada form, tidak ada pilihan

3. **Guru Override**:
   - Guru bisa mengubah status siswa kapan saja (selama belum submit)
   - Guru bisa ubah dari "Hadir" ke "Sakit/Izin/Alfa" atau sebaliknya

4. **Submit & Lock**:
   - Setelah di-submit, guru tidak bisa edit lagi
   - Hanya admin yang bisa unlock

5. **Unique Constraint**:
   - Satu siswa hanya bisa punya 1 record absensi per pertemuan
   - Database constraint: `UNIQUE(pertemuan_id, siswa_id)`

---

## 🚀 DEPLOYMENT CHECKLIST

- [x] Migration berhasil
- [x] Models dibuat dan diupdate
- [x] Controllers lengkap
- [x] Routes terdaftar
- [x] Views responsive dan interactive
- [x] Alert messages berfungsi
- [x] Helper methods tested
- [x] Business logic validated
- [x] Database constraints aktif
- [x] Timezone configuration (Asia/Jakarta)

---

## 🔮 FUTURE ENHANCEMENTS (Optional)

1. **Notifikasi Real-time**:
   - Push notification saat presensi dibuka
   - Email reminder untuk yang belum absen

2. **QR Code Attendance**:
   - Guru generate QR code
   - Siswa scan untuk absen

3. **Geolocation**:
   - Validasi lokasi siswa saat absen
   - Pastikan berada di area sekolah

4. **Report & Analytics**:
   - Grafik kehadiran per siswa
   - Export to Excel/PDF
   - Statistik per kelas/mapel

5. **Auto-Alfa**:
   - Cron job untuk auto-set siswa yang belum absen jadi "Alfa"
   - Run setelah waktu_absen_ditutup

---

## 📝 CATATAN PENTING

1. **Timezone**: Sistem menggunakan `Asia/Jakarta` (WIB)
2. **AlpineJS**: Views menggunakan Alpine.js untuk interactivity (sudah include di layout)
3. **TailwindCSS**: Semua styling menggunakan Tailwind utility classes
4. **Carbon**: Semua datetime operations menggunakan Carbon library
5. **Eloquent Relationships**: Pertemuan → Jadwal → MataPelajaran, Guru, Kelas

---

## 👨‍💻 DEVELOPER NOTES

**Created by**: GitHub Copilot  
**Date**: November 16, 2025  
**Framework**: Laravel 11  
**Database**: MySQL 8.0  
**Frontend**: Blade + TailwindCSS + Alpine.js  

**Key Design Decisions**:
- Time-based window (sama seperti tugas)
- Separate date and time fields untuk flexibility
- Submit/lock mechanism untuk data integrity
- Admin override untuk exceptional cases
- One-click UX untuk siswa (reduce friction)

---

🎉 **SISTEM SIAP DIGUNAKAN!** 🎉

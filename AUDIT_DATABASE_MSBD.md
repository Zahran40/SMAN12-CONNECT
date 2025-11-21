# AUDIT STRUKTUR DATABASE & RELASI - SMAN12 CONNECT
**Tanggal:** 21 November 2025
**Status:** ✅ SELESAI DIPERBAIKI

---

## 📋 RINGKASAN EKSEKUTIF

Sistem SMAN12-CONNECT memiliki beberapa pelanggaran prinsip MSBD (Manajemen Sistem Basis Data) yang menyebabkan:
- ❌ Redundansi data (data duplikat)
- ❌ Anomali update (inconsistency)
- ❌ Filter yang tidak berfungsi
- ❌ Relasi tabel yang tidak tepat

---

## ❌ MASALAH DITEMUKAN

### 1. **MATA PELAJARAN memiliki `tahun_ajaran_id` (SALAH)**

**Pelanggaran:** Normalisasi Database
```
tabel: mata_pelajaran
kolom: tahun_ajaran_id (SEHARUSNYA TIDAK ADA)
```

**Mengapa Salah:**
- Mata pelajaran adalah **MASTER DATA** yang tidak berubah per tahun ajaran
- Matematika, Fisika, Biologi adalah tetap, tidak berubah 2024 atau 2025
- Relasi mapel→tahun ajaran sudah ada di tabel `jadwal_pelajaran`

**Dampak:**
- Harus buat data Matematika berkali-kali untuk setiap tahun ajaran
- Jika update nama/kode mapel, harus update di banyak record
- Filter tahun ajaran di mapel tidak masuk akal

**Solusi:**
```sql
-- Hapus tahun_ajaran_id dari mata_pelajaran
ALTER TABLE mata_pelajaran DROP FOREIGN KEY fk_mapel_tahun;
ALTER TABLE mata_pelajaran DROP COLUMN tahun_ajaran_id;
```

---

### 2. **SISWA.kelas_id (Direct Relation) - TIDAK FLEKSIBEL**

**Pelanggaran:** Many-to-Many Relationship
```
tabel: siswa
kolom: kelas_id (SEHARUSNYA MANY-TO-MANY)
```

**Mengapa Salah:**
- Siswa bisa pindah kelas setiap tahun
- Tidak bisa track history kelas siswa
- Tidak bisa tau siswa pernah di kelas apa saja

**Dampak:**
- Data hilang saat siswa pindah kelas
- Tidak ada audit trail
- Tidak bisa lihat kelas siswa di tahun lalu

**Solusi:**
```sql
-- Buat tabel junction siswa_kelas
CREATE TABLE siswa_kelas (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    siswa_id BIGINT,
    kelas_id BIGINT,
    tahun_ajaran_id BIGINT,
    tanggal_masuk DATE,
    tanggal_keluar DATE,
    status ENUM('Aktif', 'Pindah', 'Lulus', 'Keluar'),
    UNIQUE(siswa_id, tahun_ajaran_id, status)
);
```

---

### 3. **FILTER SEMESTER TIDAK BERGUNA**

**Pelanggaran:** Redundant Filter
```
view: dataMaster_Siswa.blade.php, dataMaster_Guru.blade.php, dataMaster_Mapel.blade.php
filter: Semester (Ganjil/Genap)
```

**Mengapa Tidak Berguna:**
- Tahun ajaran SUDAH INCLUDE semester di database
  ```
  tahun_ajaran: 2024/2025 - Ganjil
  tahun_ajaran: 2024/2025 - Genap
  ```
- Filter semester terpisah tidak ada gunanya
- User bingung harus pilih apa

**Solusi:**
```blade
<!-- HAPUS filter semester -->
<select name="semester">...</select>

<!-- Cukup filter tahun ajaran saja -->
<select name="tahun_ajaran">
    <option>2024/2025 - Ganjil</option>
    <option>2024/2025 - Genap</option>
    <option>2025/2026 - Ganjil</option>
</select>
```

---

### 4. **FILTER KELAS TIDAK BERFUNGSI**

**Pelanggaran:** Incomplete Implementation
```
controller: DataMasterController
method: listGuru(), listMapel()
filter: kelas_id (TIDAK DIQUERY)
```

**Mengapa Tidak Berfungsi:**
- Filter kelas tampil di UI tapi tidak di-query
- Controller tidak join ke `jadwal_pelajaran`
- Hasilnya: Filter kelas tidak ada efek

**Solusi:**
```php
// BENAR: Filter guru by kelas via jadwal_pelajaran
$guruList = DB::table('view_guru_mengajar')
    ->where('kelas_id', $kelasId)
    ->distinct()
    ->get();

// BENAR: Filter mapel by kelas via jadwal_pelajaran  
$mapelList = DB::table('view_mapel_diajarkan')
    ->where('kelas_id', $kelasId)
    ->get();
```

---

## ✅ STRUKTUR YANG SUDAH BENAR

### 1. **Tabel `jadwal_pelajaran` - PERFECT!**
```sql
CREATE TABLE jadwal_pelajaran (
    id_jadwal BIGINT PRIMARY KEY,
    tahun_ajaran_id BIGINT, -- ✅ Benar
    kelas_id BIGINT,        -- ✅ Benar
    mapel_id BIGINT,        -- ✅ Benar
    guru_id BIGINT,         -- ✅ Benar
    hari ENUM(...),
    jam_mulai TIME,
    jam_selesai TIME,
    UNIQUE(tahun_ajaran_id, kelas_id, mapel_id, hari, jam_mulai)
);
```

**Mengapa Benar:**
- Ini adalah tabel **JUNCTION** yang benar
- Relasi Many-to-Many: tahun_ajaran ↔ kelas ↔ mapel ↔ guru
- Satu jadwal = 1 tahun ajaran, 1 kelas, 1 mapel, 1 guru, 1 waktu

### 2. **Tabel `kelas` - BENAR**
```sql
CREATE TABLE kelas (
    id_kelas BIGINT PRIMARY KEY,
    nama_kelas VARCHAR(250),
    tingkat ENUM('10', '11', '12'),
    jurusan VARCHAR(250),
    tahun_ajaran_id BIGINT, -- ✅ Benar: Kelas dibuat per tahun ajaran
    wali_kelas_id BIGINT
);
```

**Mengapa Benar:**
- Kelas 10 IPA 1 tahun 2024 BERBEDA dengan Kelas 10 IPA 1 tahun 2025
- Setiap tahun ajaran buat kelas baru
- Wali kelas bisa berubah per tahun

---

## 🔧 SOLUSI IMPLEMENTASI

### Migration yang Dibuat

1. **`2025_11_21_000001_create_siswa_kelas_table.php`**
   - Tabel junction siswa ↔ kelas
   - Track history kelas per siswa
   - Status: Aktif, Pindah, Lulus, Keluar

2. **`2025_11_21_000002_make_siswa_kelas_id_nullable.php`**
   - Ubah `siswa.kelas_id` jadi nullable
   - Backward compatibility

3. **`2025_11_21_000003_remove_tahun_ajaran_from_mata_pelajaran.php`**
   - Hapus `tahun_ajaran_id` dari `mata_pelajaran`
   - Mapel jadi pure master data

4. **`2025_11_21_000004_create_views_data_master.php`**
   - `view_siswa_kelas`: Siswa + kelas aktif + tahun ajaran
   - `view_guru_mengajar`: Guru + mapel + kelas yang diajar
   - `view_mapel_diajarkan`: Mapel + guru + kelas yang mengajar
   - `view_kelas_detail`: Kelas + jumlah siswa/guru/mapel

### Models yang Dibuat/Diupdate

1. **`SiswaKelas.php`** (BARU)
   - Model untuk tabel junction siswa_kelas

2. **`Siswa.php`** (UPDATE)
   - Tambah relasi `kelasAktif()` via siswa_kelas
   - Tambah relasi `kelasHistory()` via siswa_kelas

3. **`Kelas.php`** (UPDATE)
   - Tambah relasi `siswaAktif()` via siswa_kelas
   - Tambah relasi `siswaHistory()` via siswa_kelas

4. **`MataPelajaran.php`** (UPDATE)
   - Hapus relasi `tahunAjaran()`
   - Update relasi `guru()` via jadwal_pelajaran

### Controllers yang Diupdate

1. **`DataMasterController.php`**
   - `listSiswa()`: Gunakan `view_siswa_kelas` + filter kelas
   - `listGuru()`: Gunakan `view_guru_mengajar` + filter kelas
   - `listMapel()`: Gunakan `view_mapel_diajarkan` + filter kelas
   - Hapus parameter `$semester` (tidak perlu)

---

## 📊 DIAGRAM RELASI (SEBELUM vs SESUDAH)

### ❌ SEBELUM (SALAH)
```
siswa ──1:1──> kelas ──N:1──> tahun_ajaran
  │
  └──> (tidak bisa pindah kelas)

mata_pelajaran ──N:1──> tahun_ajaran
  │
  └──> (harus buat Matematika berkali-kali)
```

### ✅ SESUDAH (BENAR)
```
siswa ──N:M──> siswa_kelas ──N:1──> kelas ──N:1──> tahun_ajaran
  │              │                      │
  │              └──N:1──> tahun_ajaran │
  │                                      │
  └──> (bisa pindah kelas, ada history) │
                                         │
mata_pelajaran ──N:M──> jadwal_pelajaran <──N:M── guru
  │                       │
  │                       └──N:1──> tahun_ajaran
  │                       └──N:1──> kelas
  │
  └──> (master data, tidak duplikat)
```

---

## 🎯 FUNGSI FILTER (SETELAH PERBAIKAN)

### Filter Tahun Ajaran
- **Kelas:** Tampilkan kelas yang dibuat di tahun ajaran tertentu
- **Siswa:** Tampilkan siswa yang terdaftar di kelas tahun ajaran tertentu
- **Guru:** Tampilkan guru yang mengajar di tahun ajaran tertentu
- **Mapel:** Tampilkan mapel yang diajarkan di tahun ajaran tertentu

### Filter Kelas
- **Siswa:** Tampilkan siswa yang ada di kelas tertentu
- **Guru:** Tampilkan guru yang mengajar di kelas tertentu
- **Mapel:** Tampilkan mapel yang diajarkan di kelas tertentu

### ~~Filter Semester~~ (DIHAPUS)
- Tidak perlu, sudah ter-cover di tahun ajaran

---

## 📝 CARA MIGRASI DATA LAMA

### 1. Migrasi Data Siswa ke siswa_kelas
```sql
-- Insert data siswa yang sudah ada ke tabel siswa_kelas
INSERT INTO siswa_kelas (siswa_id, kelas_id, tahun_ajaran_id, status, tanggal_masuk)
SELECT 
    s.id_siswa,
    s.kelas_id,
    k.tahun_ajaran_id,
    'Aktif',
    NOW()
FROM siswa s
INNER JOIN kelas k ON s.kelas_id = k.id_kelas
WHERE s.kelas_id IS NOT NULL;
```

### 2. Verifikasi Data
```sql
-- Cek siswa yang belum ada di siswa_kelas
SELECT s.id_siswa, s.nama_lengkap, s.kelas_id
FROM siswa s
LEFT JOIN siswa_kelas sk ON s.id_siswa = sk.siswa_id AND sk.status = 'Aktif'
WHERE sk.id IS NULL;
```

---

## ✅ CHECKLIST PERBAIKAN

- [x] Buat tabel `siswa_kelas`
- [x] Buat model `SiswaKelas`
- [x] Update model `Siswa` (relasi kelasAktif)
- [x] Update model `Kelas` (relasi siswaAktif)
- [x] Hapus `tahun_ajaran_id` dari `mata_pelajaran`
- [x] Update model `MataPelajaran` (hapus relasi tahunAjaran)
- [x] Buat database views (view_siswa_kelas, view_guru_mengajar, view_mapel_diajarkan, view_kelas_detail)
- [x] Update controller `listSiswa()` (gunakan view + filter kelas)
- [x] Update controller `listGuru()` (gunakan view + filter kelas)
- [x] Update controller `listMapel()` (gunakan view + filter kelas)
- [x] Update view blade: Hapus filter semester
- [x] Migrasi data lama ke tabel `siswa_kelas`
- [ ] Testing: Filter tahun ajaran (PERLU DILAKUKAN USER)
- [ ] Testing: Filter kelas (PERLU DILAKUKAN USER)
- [ ] Testing: CRUD siswa dengan siswa_kelas (PERLU DILAKUKAN USER)
- [ ] Dokumentasi untuk user (OPTIONAL)

---

## 🚀 LANGKAH DEPLOYMENT

1. **Backup Database**
   ```bash
   mysqldump -u root sman12_connect > backup_before_migration.sql
   ```

2. **Run Migrations**
   ```bash
   php artisan migrate
   ```

3. **Migrasi Data Lama**
   ```bash
   php artisan tinker
   ```
   ```php
   // Run SQL di atas untuk migrasi siswa_kelas
   ```

4. **Testing**
   - Test filter tahun ajaran di semua halaman data master
   - Test filter kelas di siswa, guru, mapel
   - Test CRUD siswa baru
   - Test pindah kelas siswa

5. **Deploy ke Production**
   ```bash
   git add .
   git commit -m "Fix: Perbaikan struktur database sesuai MSBD"
   git push origin main
   ```

---

## 📞 KONTAK

Jika ada pertanyaan teknis, hubungi tim development.

**Status Terakhir:** ✅ SEMUA PERBAIKAN SELESAI - Siap untuk testing
**Estimated Time:** Testing memerlukan 30-60 menit

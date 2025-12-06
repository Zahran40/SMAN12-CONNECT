
# 📚 Database Objects Documentation - SMAN 12 Connect

> **Last Updated**: 7 Desember 2025  
> **Total Objects**: 24 (6 Functions + 5 Stored Procedures + 13 Views)  
> **Status**: ✅ **100% Utilized** - Tidak ada unused objects

---

## 📋 Table of Contents
1. [Functions (6)](#functions)
2. [Stored Procedures (5)](#stored-procedures)
3. [Views (13)](#views)
4. [Recent Changes](#recent-changes)
5. [Semester Logic](#semester-logic)
6. [Migration Guide](#migration-guide)

---

## 🔧 FUNCTIONS

### 1. `fn_convert_grade_letter(nilai)`
| Property | Value |
|----------|-------|
| **Parameter** | `nilai DECIMAL(5,2)` |
| **Return** | `VARCHAR(1)` (A, B, C, D, E) |
| **Kegunaan** | Convert nilai angka → huruf |
| **Digunakan di** | `RaportController@detailAll` |

**Grade Scale**:
```
A: >= 90  |  B: >= 80  |  C: >= 70  |  D: >= 60  |  E: < 60
```

**Example**:
```sql
SELECT fn_convert_grade_letter(85.5);  -- Returns: 'B'
SELECT fn_convert_grade_letter(56.0);  -- Returns: 'E'
```

---

### 2. `fn_hadir_persen(siswa_id, pertemuan_id)`
| Property | Value |
|----------|-------|
| **Parameters** | `siswa_id BIGINT`, `pertemuan_id BIGINT` |
| **Return** | `DECIMAL(5,2)` (0.00 - 100.00) |
| **Kegunaan** | Hitung persentase kehadiran |
| **Digunakan di** | `AbsensiController`, Dashboard Siswa |

**Example**:
```sql
SELECT fn_hadir_persen(1, 10);  -- Returns: 75.50
```

---

### 3. `fn_rata_nilai(siswa_id, tahun_ajaran_id, semester)` ⭐ **UPDATED**
| Property | Value |
|----------|-------|
| **Parameters** | `siswa_id BIGINT`, `tahun_ajaran_id BIGINT`, `semester VARCHAR(10)` |
| **Return** | `DECIMAL(5,2)` |
| **Kegunaan** | Rata-rata nilai **per semester** |
| **Digunakan di** | `RaportController@detailAll` |

**📌 UPDATE LOG (7 Des 2025)**:
- ✅ **ADDED**: Parameter `semester` untuk filter per semester
- ❌ **BEFORE**: Menghitung rata-rata SEMUA semester (tidak akurat)
- ✅ **NOW**: Menghitung rata-rata PER semester (akurat)
- 🚫 **REMOVED FROM**: `SiswaController@beranda` (dihapus untuk performa)

**Example**:
```sql
-- Semester Ganjil
SELECT fn_rata_nilai(1, 2, 'Ganjil');  -- Returns: 78.50

-- Semester Genap
SELECT fn_rata_nilai(1, 2, 'Genap');   -- Returns: 82.30
```

**Migration File**: `2025_11_14_151408_create_fn_rata_nilai_func.php`

---

### 4. `fn_total_spp_siswa(siswa_id, tahun)`
| Property | Value |
|----------|-------|
| **Parameters** | `siswa_id BIGINT`, `tahun YEAR` |
| **Return** | `DECIMAL(15,2)` |
| **Kegunaan** | Total SPP terbayar per tahun |
| **Digunakan di** | `SiswaController@beranda` |

**Example**:
```sql
SELECT fn_total_spp_siswa(1, 2025);  -- Returns: 6000000.00
```

---

### 5. `fn_guru_can_access_jadwal(guru_id, jadwal_id)`
| Property | Value |
|----------|-------|
| **Parameters** | `guru_id BIGINT`, `jadwal_id BIGINT` |
| **Return** | `BOOLEAN` |
| **Kegunaan** | Validasi akses guru ke jadwal |
| **Digunakan di** | `CheckGuruJadwalAccess` middleware |

**Example**:
```sql
SELECT fn_guru_can_access_jadwal(5, 12);  -- Returns: 1 (true)
```

---

### 6. `fn_siswa_can_access_jadwal(siswa_id, jadwal_id)`
| Property | Value |
|----------|-------|
| **Parameters** | `siswa_id BIGINT`, `jadwal_id BIGINT` |
| **Return** | `BOOLEAN` |
| **Kegunaan** | Validasi akses siswa ke jadwal |
| **Digunakan di** | `CheckSiswaJadwalAccess` middleware |

**Example**:
```sql
SELECT fn_siswa_can_access_jadwal(10, 12);  -- Returns: 1 (true)
```

---

## 📦 STORED PROCEDURES

### 1. `sp_calculate_average_tugas`
| Property | Value |
|----------|-------|
| **Parameters** | `siswa_id`, `mapel_id`, `semester`, `OUT average` |
| **Return** | `DECIMAL(5,2)` via OUT parameter |
| **Kegunaan** | Rata-rata nilai tugas |
| **Digunakan di** | `RaportController` (Guru) |

**Example**:
```sql
CALL sp_calculate_average_tugas(1, 5, 'Ganjil', @avg);
SELECT @avg;  -- Returns: 85.50
```

---

### 2. `sp_rekap_absensi_kelas(jadwal_id)`
| Property | Value |
|----------|-------|
| **Parameters** | `jadwal_id BIGINT` |
| **Returns** | Recordset (siswa + persentase kehadiran) |
| **Kegunaan** | Rekap absensi per kelas |
| **Digunakan di** | `PresensiController` (Guru) |

**Example**:
```sql
CALL sp_rekap_absensi_kelas(10);
-- Returns: List siswa dengan persen hadir
```

---

### 3. `sp_get_pengumuman_aktif(target_role)`
| Property | Value |
|----------|-------|
| **Parameters** | `target_role VARCHAR(20)` |
| **Returns** | Recordset (pengumuman aktif) |
| **Kegunaan** | Ambil pengumuman aktif |
| **Digunakan di** | `SiswaController`, `GuruController` |

**Valid Roles**: `'siswa'`, `'guru'`, `'Semua'`

**Example**:
```sql
CALL sp_get_pengumuman_aktif('siswa');
-- Returns: Pengumuman untuk siswa
```

---

### 4. `sp_rekap_nilai_siswa(siswa_id, tahun_ajaran_id, semester)` ⭐ **UPDATED**
| Property | Value |
|----------|-------|
| **Parameters** | `siswa_id`, `tahun_ajaran_id`, `semester` |
| **Returns** | Recordset (detail nilai per mapel) |
| **Kegunaan** | Detail nilai mapel **per semester** |
| **Digunakan di** | `RaportController@detailAll` (Siswa) |

**📌 UPDATE LOG (7 Des 2025)**:
- ✅ **ADDED**: Parameter `semester` untuk filter per semester
- ✅ **ADDED**: Output kolom `semester`
- ❌ **BEFORE**: Return semua nilai tanpa filter semester
- ✅ **NOW**: Return hanya nilai semester yang dipilih

**Returns**:
```
nama_lengkap | nama_mapel | nilai_tugas | nilai_uts | nilai_uas | nilai_akhir | semester
-------------|------------|-------------|-----------|-----------|-------------|----------
John Doe     | Matematika | 80          | 75        | 85        | 80.00       | Ganjil
John Doe     | Geografi   | 60          | 55        | 53        | 56.00       | Ganjil
```

**Example**:
```sql
-- Semester Ganjil
CALL sp_rekap_nilai_siswa(1, 2, 'Ganjil');

-- Semester Genap
CALL sp_rekap_nilai_siswa(1, 2, 'Genap');
```

**Migration File**: `2025_11_14_151405_create_sp_rekap_nilai_siswa_proc.php`

---

### 5. `sp_rekap_spp_tahun(tahun_ajaran_id)`
| Property | Value |
|----------|-------|
| **Parameters** | `tahun_ajaran_id BIGINT` |
| **Returns** | Recordset (rekap SPP per siswa) |
| **Kegunaan** | Rekap pembayaran SPP |
| **Digunakan di** | `PembayaranController` (Admin) |

**Returns**:
```
id_siswa | nama_lengkap | bulan_lunas | bulan_belum_lunas | total_bayar
---------|--------------|-------------|-------------------|-------------
1        | John Doe     | 10          | 2                 | 5000000.00
2        | Jane Smith   | 12          | 0                 | 6000000.00
```

**Example**:
```sql
CALL sp_rekap_spp_tahun(2);
```

---

## 👁️ VIEWS (13 Total)

### Data Master Views
1. **`view_siswa_kelas`** - Join siswa + kelas + tahun ajaran
2. **`view_guru_mengajar`** - Join guru + mata pelajaran + jadwal
3. **`view_mapel_diajarkan`** - Mata pelajaran yang diajarkan
4. **`view_kelas_detail`** - Detail kelas dengan wali kelas

### Dashboard Views
5. **`view_dashboard_siswa`** - Statistik dashboard siswa
6. **`view_data_siswa`** - Data lengkap siswa
7. **`view_data_guru`** - Data lengkap guru

### Academic Views
8. **`view_nilai_siswa`** - Nilai siswa dengan grade
9. **`view_jadwal_guru`** - Jadwal mengajar guru
10. **`view_absensi_siswa`** - Rekap absensi siswa
11. **`view_materi_guru`** - Materi per guru
12. **`view_tugas_siswa`** - Tugas siswa dengan deadline

### Finance View
13. **`view_pembayaran_spp`** - Rekap pembayaran SPP

**Note**: Semua views masih aktif digunakan di berbagai controller dan blade templates.

---

## 🔄 RECENT CHANGES (7 Des 2025)

### 1. ✅ Function `fn_rata_nilai` - Added Semester Parameter
**Changed By**: Refactoring session  
**Migration**: `2025_11_14_151408_create_fn_rata_nilai_func.php`

**Before**:
```sql
SELECT fn_rata_nilai(siswa_id, tahun_ajaran_id)
```

**After**:
```sql
SELECT fn_rata_nilai(siswa_id, tahun_ajaran_id, semester)
```

**Impact**:
- ✅ Rata-rata nilai sekarang akurat per semester
- ✅ Semester Ganjil dan Genap menampilkan nilai berbeda
- 🚫 Removed dari `SiswaController@beranda` (tidak perlu tampil rata-rata nilai di beranda)

---

### 2. ✅ Stored Procedure `sp_rekap_nilai_siswa` - Added Semester Parameter
**Changed By**: Refactoring session  
**Migration**: `2025_11_14_151405_create_sp_rekap_nilai_siswa_proc.php`

**Before**:
```sql
CALL sp_rekap_nilai_siswa(siswa_id, tahun_ajaran_id)
```

**After**:
```sql
CALL sp_rekap_nilai_siswa(siswa_id, tahun_ajaran_id, semester)
```

**Impact**:
- ✅ Detail nilai raport sekarang filter per semester
- ✅ Output tambah kolom `semester`
- ✅ Konsisten dengan `fn_rata_nilai`

---

### 3. ✅ Semester Logic Implementation
**Applied To**: Kelas, Siswa, Pembayaran, Mata Pelajaran, Raport

**Logic**:
```php
// Saat semester Genap dipilih
if ($semester === 'Genap') {
    // Query menggunakan data semester Ganjil dengan tahun_mulai yang sama
    $tahunAjaranGanjil = TahunAjaran::where('tahun_mulai', $selectedTA->tahun_mulai)
        ->where('semester', 'Ganjil')
        ->first();
    
    $tahunAjaranIdForQuery = $tahunAjaranGanjil->id_tahun_ajaran;
}
```

**Reason**: Siswa didaftarkan ke kelas hanya di semester Ganjil. Data kelas semester Genap menggunakan referensi dari semester Ganjil.

**Affected Controllers**:
- `DataMasterController` - Kelas, Siswa, Pembayaran, Mata Pelajaran
- `RaportController` - `index()`, `detailAll()`
- `SiswaController` - `profil()`

---

## 🔁 SEMESTER LOGIC

### Konsep
Dalam sistem akademik, **siswa didaftarkan ke kelas hanya sekali per tahun ajaran (semester Ganjil)**. Semester Genap menggunakan data kelas yang sama dari semester Ganjil.

### Implementasi

```php
// Pattern yang digunakan di semua controller
$tahunAjaranIdForQuery = $selectedTahunAjaran->id_tahun_ajaran;

if ($selectedTahunAjaran->semester === 'Genap') {
    $tahunAjaranGanjil = TahunAjaran::where('tahun_mulai', $selectedTahunAjaran->tahun_mulai)
        ->where('semester', 'Ganjil')
        ->first();
    
    if ($tahunAjaranGanjil) {
        $tahunAjaranIdForQuery = $tahunAjaranGanjil->id_tahun_ajaran;
    }
}

// Query kelas menggunakan $tahunAjaranIdForQuery
$kelas = DB::table('siswa_kelas')
    ->where('tahun_ajaran_id', $tahunAjaranIdForQuery)
    ->get();
```

### Contoh
User memilih: **2024/2025 - Semester Genap** (id = 2)  
System queries: **2024/2025 - Semester Ganjil** (id = 1)

**Data yang menggunakan logic ini**:
- ✅ Kelas (siswa_kelas)
- ✅ Siswa (siswa_kelas)
- ✅ Pembayaran SPP (tagihan semester Genap pakai kelas Ganjil)
- ✅ Mata Pelajaran (mapel semester Genap pakai jadwal Ganjil)
- ✅ Raport (nama kelas di raport)

**Data yang TIDAK menggunakan logic** (langsung query sesuai semester):
- ❌ Nilai (nilai memang berbeda per semester)
- ❌ Absensi (absensi berbeda per semester)
- ❌ Tugas (tugas berbeda per semester)

---

## 📖 MIGRATION GUIDE

### Untuk Fresh Install
```bash
# Clone project
cd c:\laragon\www\SMAN12-CONNECT

# Install dependencies
composer install

# Setup .env
cp .env.example .env
php artisan key:generate

# Migrate + Seed
php artisan migrate:fresh --seed

# ✅ Semua Functions, SP, Views otomatis terbuat
```

### Untuk Update dari Versi Lama
```bash
# Pastikan branch terbaru
git pull origin abbil

# Run migration baru
php artisan migrate

# Migration yang akan jalan:
# - 2025_11_14_151405_create_sp_rekap_nilai_siswa_proc.php (updated)
# - 2025_11_14_151408_create_fn_rata_nilai_func.php (updated)
# - 2025_12_06_220840_drop_unused_functions_and_procedures.php
# - 2025_12_06_214252_drop_unused_views.php
# - 2025_12_07_001012_update_documentation_for_sp_and_function_changes.php (docs only)
```

### Rollback (jika diperlukan)
```bash
# Rollback 1 migration
php artisan migrate:rollback --step=1

# Rollback ke batch tertentu
php artisan migrate:rollback --batch=3

# Fresh install (HATI-HATI: hapus semua data)
php artisan migrate:fresh --seed
```

---

## 🧪 Testing Database Objects

### Test Functions
```sql
-- 1. Convert grade
SELECT fn_convert_grade_letter(85.5) as grade;  -- Expected: B

-- 2. Hadir persen (sesuaikan siswa_id dan pertemuan_id)
SELECT fn_hadir_persen(1, 10) as persen;  -- Expected: 0-100

-- 3. Rata-rata nilai Ganjil
SELECT fn_rata_nilai(1, 2, 'Ganjil') as rata;  -- Expected: decimal

-- 4. Rata-rata nilai Genap
SELECT fn_rata_nilai(1, 2, 'Genap') as rata;  -- Expected: decimal

-- 5. Total SPP tahun ini
SELECT fn_total_spp_siswa(1, 2025) as total;  -- Expected: decimal

-- 6. Cek akses guru
SELECT fn_guru_can_access_jadwal(5, 12) as can_access;  -- Expected: 0/1

-- 7. Cek akses siswa
SELECT fn_siswa_can_access_jadwal(10, 12) as can_access;  -- Expected: 0/1
```

### Test Stored Procedures
```sql
-- 1. Rata-rata tugas
CALL sp_calculate_average_tugas(1, 5, 'Ganjil', @avg);
SELECT @avg;

-- 2. Rekap absensi kelas
CALL sp_rekap_absensi_kelas(10);

-- 3. Pengumuman aktif
CALL sp_get_pengumuman_aktif('siswa');

-- 4. Rekap nilai siswa (Ganjil)
CALL sp_rekap_nilai_siswa(1, 2, 'Ganjil');

-- 5. Rekap nilai siswa (Genap)
CALL sp_rekap_nilai_siswa(1, 2, 'Genap');

-- 6. Rekap SPP tahun ajaran
CALL sp_rekap_spp_tahun(2);
```

### Test Views
```sql
-- 1. Dashboard siswa
SELECT * FROM view_dashboard_siswa WHERE id_siswa = 1;

-- 2. Nilai siswa dengan grade
SELECT * FROM view_nilai_siswa WHERE id_siswa = 1 AND semester = 'Ganjil';

-- 3. Jadwal guru
SELECT * FROM view_jadwal_guru WHERE id_guru = 5;

-- 4. Pembayaran SPP
SELECT * FROM view_pembayaran_spp WHERE id_siswa = 1;

-- 5. Siswa kelas
SELECT * FROM view_siswa_kelas WHERE id_siswa = 1;
```

---

## 📝 Notes

1. **Semua objects 100% terpakai** - Tidak ada unused functions/SP/views
2. **Semester filtering** sudah diterapkan di SP dan Function untuk akurasi data
3. **Semester logic** diterapkan konsisten di semua modul data master
4. **Migration files** tersimpan di `database/migrations/` dengan timestamp jelas
5. **Rollback aman** - Semua migration punya method `down()`

---

## 🤝 Contribution

Jika ada perubahan pada database objects:
1. Update migration file yang sesuai
2. Update dokumentasi ini
3. Test semua affected controllers
4. Commit dengan message jelas

---

**Maintained by**: SMAN 12 Connect Development Team  
**Contact**: [Your Contact Info]  
**Repository**: [GitHub Repo URL]

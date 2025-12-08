# ⚠️ BREAKING CHANGES - Pengumuman Module (8 Des 2025)

## 🔧 Perubahan yang Dilakukan

### 1. ✅ Drop Kolom `hari` dari Tabel Pengumuman

**Migration**: `2025_12_08_155803_drop_hari_column_from_pengumuman_table.php`

**Alasan**:
- ❌ **Kolom `hari` redundan** dengan `tgl_publikasi`
- ❌ **Inconsistent data**: User bisa input "Senin, 5 Desember 2025" padahal 5 Des adalah Jumat
- ❌ **Melanggar normalisasi**: Data `hari` bisa di-derive dari `tgl_publikasi`
- ❌ **Maintenance nightmare**: Harus sync 2 kolom untuk 1 informasi

**Solusi**:
- ✅ Drop kolom `hari` dari tabel
- ✅ Auto-generate hari dari `tgl_publikasi`
- ✅ Gunakan `DAYNAME(tgl_publikasi)` di SQL
- ✅ Atau `Carbon::parse($tgl)->translatedFormat('l')` di PHP

---

### 2. ✅ Update Stored Procedure `sp_get_pengumuman_aktif`

**Migration**: `2025_11_14_151405_create_sp_get_pengumuman_aktif_proc.php` (updated)

**Before**:
```sql
SELECT p.hari FROM pengumuman p ...
```

**After**:
```sql
SELECT DAYNAME(p.tgl_publikasi) AS hari FROM pengumuman p ...
```

**Output**: Hari auto-generate dari `tgl_publikasi`, always konsisten!

---

### 3. ✅ Pindah SP Call dari Beranda ke Halaman Pengumuman

**Affected Files**:
- `app/Http/Controllers/SiswaController.php` → **REMOVED** SP call
- `app/Http/Controllers/GuruController.php` → **REMOVED** SP call
- `app/Http/Controllers/Siswa/MateriController.php` → **ADDED** SP call
- `app/Http/Controllers/Guru/MateriController.php` → **ADDED** SP call

**Alasan**:
- ❌ Beranda tidak perlu tampilkan pengumuman (sudah ada di menu sidebar)
- ✅ Halaman pengumuman adalah tempat yang tepat
- ✅ Performa beranda lebih cepat (1 query less)
- ✅ Separation of concerns lebih baik

---

### 4. ✅ Update Controller Pengumuman

**File**: `app/Http/Controllers/Admin/PengumumanController.php`

**Changes**:
```php
// REMOVED
'hari' => 'nullable|string',  // Input hari manual
'hari' => $validated['hari'], // Save hari manual

// CHANGED
'tanggal' => 'required|date',  // Tanggal wajib (sebelumnya nullable)

// AUTO-GENERATED
// Hari tidak disimpan, auto dari tgl_publikasi di query
```

---

## 📊 Impact Summary

| Komponen | Before | After | Status |
|----------|--------|-------|--------|
| Tabel `pengumuman` | Kolom `hari` + `tgl_publikasi` | Hanya `tgl_publikasi` | ✅ Simplified |
| SP `sp_get_pengumuman_aktif` | Return kolom `hari` | Return `DAYNAME(tgl)` | ✅ Auto-generate |
| Beranda Siswa | Call SP pengumuman | Tidak call SP | ✅ Faster |
| Beranda Guru | Call SP pengumuman | Tidak call SP | ✅ Faster |
| Halaman Pengumuman | Query manual | Call SP | ✅ Optimized |
| Form Buat Pengumuman | Input hari + tanggal | Hanya tanggal | ✅ User-friendly |
| Data Consistency | Risk inconsistent | Always consistent | ✅ Safe |

---

## 🧪 Testing

### Test SP Update
```sql
-- Test SP setelah migration
CALL sp_get_pengumuman_aktif('siswa');

-- Output harus punya kolom 'hari' yang auto dari tgl_publikasi
-- Contoh: hari = 'Sunday' untuk tgl_publikasi = '2025-12-08'
```

### Test Form Pengumuman
1. Buat pengumuman baru di Admin
2. Cek: Form hanya punya input **Tanggal** (tidak ada input Hari)
3. Submit pengumuman
4. Cek database: Kolom `hari` tidak ada, hanya `tgl_publikasi`
5. Lihat pengumuman di halaman Siswa/Guru
6. Cek: Hari otomatis muncul sesuai tanggal

### Test Beranda
1. Buka beranda Siswa
2. Cek: Tidak ada pengumuman ditampilkan
3. Buka menu **Pengumuman**
4. Cek: Pengumuman muncul di halaman tersendiri

---

## 🔄 Migration Guide

### Fresh Install
```bash
php artisan migrate:fresh --seed
# ✅ Kolom hari otomatis tidak dibuat
# ✅ SP otomatis pakai DAYNAME()
```

### Update Existing Database
```bash
# 1. Backup database dulu!
mysqldump -u root sman12-connect > backup.sql

# 2. Run migration
php artisan migrate

# 3. Refresh SP
php artisan migrate:refresh --path=database/migrations/2025_11_14_151405_create_sp_get_pengumuman_aktif_proc.php

# 4. Clear cache
php artisan cache:clear
php artisan view:clear
```

---

## 📝 Developer Notes

1. **Kolom `hari` sudah di-drop** → Jangan reference lagi di code
2. **SP auto-generate hari** → Gunakan `DAYNAME()` atau `Carbon`
3. **Form hanya input tanggal** → User tidak bisa input hari salah
4. **Beranda tidak call SP** → Pengumuman hanya di halaman tersendiri
5. **Data always consistent** → Hari always sesuai tanggal

---

## ⚠️ Breaking Changes

### Code yang perlu diupdate:
```php
// ❌ DEPRECATED - Jangan pakai lagi
$pengumuman->hari  // Kolom tidak ada!

// ✅ USE THIS
Carbon::parse($pengumuman->tgl_publikasi)->translatedFormat('l')
// Or di query:
SELECT DAYNAME(tgl_publikasi) as hari FROM pengumuman
```

### View yang perlu diupdate:
```blade
{{-- ❌ DEPRECATED --}}
{{ $item->hari }}

{{-- ✅ USE THIS --}}
{{ \Carbon\Carbon::parse($item->tgl_publikasi)->translatedFormat('l') }}
{{-- Or jika dari SP --}}
{{ $item->hari }}  <!-- SP sudah return DAYNAME() -->
```

---

## ✅ Completed Checklist

- [x] Drop kolom `hari` dari tabel pengumuman
- [x] Update SP `sp_get_pengumuman_aktif` dengan `DAYNAME()`
- [x] Remove SP call dari SiswaController beranda
- [x] Remove SP call dari GuruController beranda
- [x] Add SP call ke MateriController (Siswa) pengumuman
- [x] Add SP call ke MateriController (Guru) pengumuman
- [x] Update PengumumanController remove validation `hari`
- [x] Update PengumumanController remove assign `hari`
- [x] Test migration
- [x] Update dokumentasi

---

**Maintained by**: SMAN 12 Connect Development Team  
**Date**: 8 Desember 2025  
**Branch**: abbil

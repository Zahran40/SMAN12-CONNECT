# 🚀 Quick Setup Guide - SMAN 12 Connect

## Fresh Install

```bash
# 1. Clone project
git clone <repo-url>
cd SMAN12-CONNECT

# 2. Install dependencies
composer install
npm install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Configure database di .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sman12-connect
DB_USERNAME=root
DB_PASSWORD=

# 5. Migrate + Seed (OTOMATIS buat semua Functions, SP, Views)
php artisan migrate:fresh --seed

# 6. Build assets
npm run build

# 7. Jalankan server
php artisan serve
```

Buka: `http://localhost:8000`

---

## Update dari Branch Lama

```bash
# 1. Pull perubahan terbaru
git pull origin abbil

# 2. Install dependencies baru (jika ada)
composer install

# 3. Run migration baru SAJA
php artisan migrate

# 4. Clear cache
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```

---

## ⚠️ PENTING - Perubahan Terbaru (7 Des 2025)

### 1. Function `fn_rata_nilai` - UPDATED ⭐
**SEBELUM**:
```sql
SELECT fn_rata_nilai(siswa_id, tahun_ajaran_id)
```

**SEKARANG**:
```sql
SELECT fn_rata_nilai(siswa_id, tahun_ajaran_id, semester)
```

✅ **Parameter baru**: `semester VARCHAR(10)` (`'Ganjil'` atau `'Genap'`)  
✅ **Alasan**: Rata-rata nilai sekarang per semester (lebih akurat)

---

### 2. Stored Procedure `sp_rekap_nilai_siswa` - UPDATED ⭐
**SEBELUM**:
```sql
CALL sp_rekap_nilai_siswa(siswa_id, tahun_ajaran_id)
```

**SEKARANG**:
```sql
CALL sp_rekap_nilai_siswa(siswa_id, tahun_ajaran_id, semester)
```

✅ **Parameter baru**: `semester VARCHAR(10)`  
✅ **Output baru**: Tambah kolom `semester`  
✅ **Alasan**: Filter nilai per semester

---

### 3. Semester Logic - APPLIED ⭐
Saat semester **Genap** dipilih → Query kelas dari semester **Ganjil** (tahun yang sama)

**Diterapkan di**:
- Data Master: Kelas, Siswa, Pembayaran, Mata Pelajaran
- Raport: List tahun ajaran, Detail raport
- Profil Siswa

**Alasan**: Siswa didaftarkan ke kelas hanya sekali di semester Ganjil

---

## 📚 Dokumentasi Lengkap

Baca file berikut untuk detail lengkap:
- **`DATABASE_OBJECTS_DOCUMENTATION.md`** - Dokumentasi Functions, SP, Views (LENGKAP)
- **`FUNCTIONS_AND_SP_IMPLEMENTATION.md`** - Dokumentasi lama (masih valid)
- **Migration**: `2025_12_07_001012_update_documentation_for_sp_and_function_changes.php`

---

## 🧪 Testing

### Test Function & SP Baru
```sql
-- Test fn_rata_nilai dengan semester
SELECT fn_rata_nilai(1, 2, 'Ganjil') as rata_ganjil;
SELECT fn_rata_nilai(1, 2, 'Genap') as rata_genap;

-- Test sp_rekap_nilai_siswa dengan semester
CALL sp_rekap_nilai_siswa(1, 2, 'Ganjil');
CALL sp_rekap_nilai_siswa(1, 2, 'Genap');
```

### Test di Browser
1. Login sebagai **Siswa**
2. Buka **Nilai Raport** → Pilih tahun ajaran
3. Cek: Nama kelas muncul (bukan "Kelas Tidak Tersedia")
4. Klik **Lihat** pada Semester Ganjil → Cek nilai
5. Klik **Lihat** pada Semester Genap → Cek nilai (harus berbeda dari Ganjil)
6. Cek card statistik: Kelas + Rata-rata Semester (2 card saja)

---

## ❓ Troubleshooting

### "Kelas Tidak Tersedia"
**Solusi**: Migration semester logic sudah diterapkan. Run:
```bash
php artisan migrate:refresh --path=database/migrations/2025_12_07_001012_update_documentation_for_sp_and_function_changes.php
```

### Error: "Incorrect number of arguments for FUNCTION fn_rata_nilai"
**Solusi**: Function sudah diupdate ke 3 parameter. Run:
```bash
php artisan migrate:refresh --path=database/migrations/2025_11_14_151408_create_fn_rata_nilai_func.php
```

### Nilai Semester 1 dan 2 sama
**Solusi**: SP sudah diupdate dengan semester filter. Run:
```bash
php artisan migrate:refresh --path=database/migrations/2025_11_14_151405_create_sp_rekap_nilai_siswa_proc.php
```

### Error Collation
**Solusi**: Clear cache:
```bash
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```

---

## 📊 Database Objects Summary

| Type | Total | Status |
|------|-------|--------|
| **Functions** | 6 | ✅ All Active |
| **Stored Procedures** | 5 | ✅ All Active |
| **Views** | 13+ | ✅ All Active |

**Total**: 24+ objects, **100% utilized**, no unused objects.

---

## 🔧 Development Tools

```bash
# Tinker (test function/SP)
php artisan tinker

# Di tinker:
DB::select('SELECT fn_rata_nilai(1, 2, "Ganjil")');

# Check migration status
php artisan migrate:status

# Rollback last migration
php artisan migrate:rollback --step=1

# Fresh install + seed
php artisan migrate:fresh --seed
```

---

**Happy Coding! 🎉**

# 🚀 SETUP UNTUK TIM - SMAN12 CONNECT

## Langkah Setup Setelah Pull dari GitHub

### 1️⃣ Pull Code dari GitHub

```bash
git pull origin andre
```

---

### 2️⃣ Install Dependencies

```bash
composer install
npm install
```

---

### 3️⃣ Update File .env

Tambahkan baris berikut ke file `.env` Anda:

```env
# MySQL User Credentials untuk Role-Based Access
DB_USER_ADMIN=admin_sia
DB_PASSWORD_ADMIN=admin123

DB_USER_GURU=guru_sia
DB_PASSWORD_GURU=guru123

DB_USER_SISWA=siswa_sia
DB_PASSWORD_SISWA=siswa123
```

**PENTING:** Pastikan `DB_DATABASE=sman_connect` sesuai dengan database Anda.

---

### 4️⃣ Buat 3 MySQL Users

**Opsi A: Via Script Otomatis (RECOMMENDED)**

Jalankan file SQL yang sudah disediakan:

```bash
mysql -u root -p < setup-mysql-users.sql
```

**Opsi B: Manual via MySQL Command**

```bash
mysql -u root -p
```

Kemudian jalankan query berikut:

```sql
-- Buat 3 MySQL Users
CREATE USER IF NOT EXISTS 'admin_sia'@'localhost' IDENTIFIED BY 'admin123';
CREATE USER IF NOT EXISTS 'guru_sia'@'localhost' IDENTIFIED BY 'guru123';
CREATE USER IF NOT EXISTS 'siswa_sia'@'localhost' IDENTIFIED BY 'siswa123';

-- Grant untuk Admin (Full Access)
GRANT ALL PRIVILEGES ON sman_connect.* TO 'admin_sia'@'localhost';

-- Grant untuk Guru
GRANT SELECT ON sman_connect.* TO 'guru_sia'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON sman_connect.detail_absensi TO 'guru_sia'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON sman_connect.nilai TO 'guru_sia'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON sman_connect.materi TO 'guru_sia'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON sman_connect.tugas TO 'guru_sia'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON sman_connect.pertemuan TO 'guru_sia'@'localhost';
GRANT SELECT, UPDATE ON sman_connect.detail_tugas TO 'guru_sia'@'localhost';

-- Grant untuk Siswa
GRANT SELECT ON sman_connect.* TO 'siswa_sia'@'localhost';
GRANT SELECT, INSERT ON sman_connect.detail_absensi TO 'siswa_sia'@'localhost';
GRANT SELECT, INSERT, UPDATE ON sman_connect.detail_tugas TO 'siswa_sia'@'localhost';
GRANT SELECT, INSERT, UPDATE ON sman_connect.pembayaran_spp TO 'siswa_sia'@'localhost';

-- Apply changes
FLUSH PRIVILEGES;

-- Verify
SELECT User, Host FROM mysql.user WHERE User IN ('admin_sia', 'guru_sia', 'siswa_sia');
```

---

### 5️⃣ Jalankan Migration (jika diperlukan)

Jika ada migration baru:

```bash
php artisan migrate
```

**CATATAN:** Migration untuk tabel sudah jalan di database lama. Hanya perlu jika ada migration baru.

---

### 6️⃣ Clear Cache Laravel

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

Atau gunakan satu command:

```bash
php artisan optimize:clear
```

---

### 7️⃣ Test Koneksi Database

Jalankan script test yang sudah disediakan:

```bash
php test-role-access.php
```

**Expected Output:**
```
✅ MySQL Users: 3 static users (admin_sia, guru_sia, siswa_sia)
✅ Database Connections: All connections working
✅ Security: Role-based access properly enforced
✅ Middleware: Connection switching logic implemented
✅ Grants: Proper privileges assigned per role

🎉 SISTEM MANAJEMEN USER BERHASIL DIIMPLEMENTASI!
```

---

### 8️⃣ Jalankan Development Server

```bash
php artisan serve
```

Atau jika menggunakan Laragon, cukup start Apache & MySQL.

---

## ⚠️ TROUBLESHOOTING

### Error: "Access denied for user 'admin_sia'@'localhost'"

**Solusi:**
1. Pastikan MySQL users sudah dibuat (langkah 4)
2. Cek credentials di `.env` sudah benar
3. Jalankan `FLUSH PRIVILEGES;` di MySQL

---

### Error: "SQLSTATE[HY000] [1045] Access denied"

**Solusi:**
1. Verifikasi password di `.env` match dengan MySQL user
2. Test koneksi manual:
   ```bash
   mysql -u admin_sia -padmin123 sman_connect
   ```

---

### Error: "Table 'sman_connect.users' doesn't exist"

**Solusi:**
Database belum ada atau belum migrate. Jalankan:
```bash
php artisan migrate --seed
```

---

### Middleware tidak switch connection

**Solusi:**
1. Clear cache: `php artisan optimize:clear`
2. Pastikan middleware terdaftar di `bootstrap/app.php`
3. Restart development server

---

## 📋 CHECKLIST SETUP

- [ ] Git pull dari branch `andre`
- [ ] Composer install
- [ ] Update `.env` dengan credentials MySQL users
- [ ] Buat 3 MySQL users (admin_sia, guru_sia, siswa_sia)
- [ ] Apply grants untuk setiap user
- [ ] Run migration (jika ada yang baru)
- [ ] Clear Laravel cache
- [ ] Test koneksi dengan `php test-role-access.php`
- [ ] Jalankan development server
- [ ] Login dan test role-based access

---

## 📞 BANTUAN

Jika ada masalah saat setup, hubungi:
- **Developer Lead:** [Nama Anda]
- **Repository:** https://github.com/Zahran40/SMAN12-CONNECT
- **Branch:** andre

Baca juga: `DOKUMENTASI_FITUR_DATABASE.md` untuk detail lengkap fitur database.

---

**Setup Version:** 1.0  
**Last Updated:** 24 November 2025

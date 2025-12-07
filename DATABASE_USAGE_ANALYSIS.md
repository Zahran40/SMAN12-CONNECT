# Analisis Penggunaan Database Objects di Project SMAN12-CONNECT

**Tanggal Analisis:** 7 Desember 2025

---

## 📊 RINGKASAN DATABASE OBJECTS

### Total Objects:
- **VIEWS:** 18 views
- **FUNCTIONS:** 6 functions  
- **STORED PROCEDURES:** 9 procedures
- **TABLES:** 27 tables (termasuk jobs, cache, sessions, dll)

---

## 🔍 VIEWS (18 Total)

### ✅ VIEWS YANG DIGUNAKAN (6)

#### 1. `view_jadwal_siswa`
- **Digunakan di:** `SiswaController.php` (beranda)
- **Tujuan:** Menampilkan jadwal pelajaran siswa per hari
- **Query:**
  ```php
  DB::table('view_jadwal_siswa')
      ->where('kelas_id', $siswaKelas->kelas_id)
      ->where('hari', $hari)
      ->get();
  ```

#### 2. `view_presensi_aktif`
- **Digunakan di:** `SiswaController.php` (beranda)
- **Tujuan:** Menampilkan presensi yang sedang berlangsung hari ini
- **Query:**
  ```php
  DB::table('view_presensi_aktif')
      ->where('kelas_id', $siswaKelas->kelas_id)
      ->where('is_open', 1)
      ->get();
  ```

#### 3. `view_dashboard_siswa`
- **Digunakan di:** `SiswaController.php` (beranda)
- **Tujuan:** Statistik dashboard siswa
- **Query:**
  ```php
  DB::table('view_dashboard_siswa')
      ->where('id_siswa', $siswa->id_siswa)
      ->first();
  ```

#### 4. `view_pembayaran_spp`
- **Digunakan di:** `Admin/pembayaran.blade.php`
- **Tujuan:** Menampilkan data pembayaran SPP dengan detail
- **Lokasi:** Blade template admin

#### 5. `view_tunggakan_siswa`
- **Kemungkinan digunakan di:** Controllers untuk cek tunggakan
- **Tujuan:** Menampilkan tunggakan SPP siswa
- **Status:** Perlu dicek lebih lanjut

#### 6. `view_pengumuman_dashboard`
- **Kemungkinan digunakan di:** Dashboard siswa/guru
- **Tujuan:** Menampilkan pengumuman untuk dashboard
- **Status:** Perlu dicek lebih lanjut

---

### ⚠️ VIEWS YANG BELUM TERPAKAI (12)

1. **view_data_guru** - Data lengkap guru
2. **view_guru_mengajar** - Jadwal mengajar guru
3. **view_jadwal_guru** - Jadwal guru (mungkin duplikat)
4. **view_jadwal_mengajar** - Jadwal mengajar (mungkin duplikat)
5. **view_kelas_detail** - Detail kelas
6. **view_mapel_diajarkan** - Mata pelajaran yang diajarkan
7. **view_materi_guru** - Materi yang dibuat guru
8. **view_nilai_siswa** - Nilai siswa
9. **view_pengumuman_data** - Data pengumuman
10. **view_siswa_kelas** - Relasi siswa-kelas
11. **view_status_absensi_siswa** - Status absensi siswa
12. **view_tugas_siswa** - Tugas siswa

**Rekomendasi:** 
- Pertimbangkan untuk menggunakan atau menghapus views yang tidak terpakai
- Beberapa view duplikat (contoh: view_jadwal_guru vs view_guru_mengajar)

---

## 🧮 FUNCTIONS (6 Total)

### ✅ FUNCTIONS YANG DIGUNAKAN (3)

#### 1. `fn_total_spp_siswa(siswa_id, tahun)`
- **Digunakan di:** 
  - `SiswaController.php` - beranda
  - `Admin/pembayaran.blade.php`
- **Tujuan:** Menghitung total SPP yang sudah dibayar siswa
- **Return:** DECIMAL total pembayaran
- **Query:**
  ```php
  DB::select('SELECT fn_total_spp_siswa(?, ?) as total', [
      $siswa->id_siswa,
      date('Y')
  ]);
  ```

#### 2. `fn_hadir_persen(siswa_id, jadwal_id)`
- **Digunakan di:** `Guru/PresensiController.php`
- **Tujuan:** Menghitung persentase kehadiran siswa
- **Return:** DECIMAL persentase
- **Query:**
  ```php
  DB::select('SELECT fn_hadir_persen(?, ?) as persen', [
      $siswa->id_siswa,
      $jadwal->id_jadwal
  ]);
  ```

#### 3. `fn_rata_nilai(siswa_id, tahun_ajaran_id, [semester])`
- **Digunakan di:** Dokumentasi (belum terpakai di code)
- **Tujuan:** Menghitung rata-rata nilai siswa
- **Return:** DECIMAL rata-rata nilai
- **Status:** Dibuat tapi belum digunakan

---

### ⚠️ FUNCTIONS YANG BELUM TERPAKAI (3)

1. **fn_convert_grade_letter(nilai)** 
   - Mengkonversi nilai angka ke huruf (A, B, C, D, E)
   - Return: VARCHAR(2)

2. **fn_guru_can_access_jadwal(guru_id, jadwal_id)**
   - Cek apakah guru bisa akses jadwal tertentu
   - Return: BOOLEAN

3. **fn_siswa_can_access_jadwal(siswa_id, jadwal_id)**
   - Cek apakah siswa bisa akses jadwal tertentu
   - Return: BOOLEAN

**Rekomendasi:** 
- `fn_convert_grade_letter` bisa digunakan untuk raport
- `fn_guru_can_access_jadwal` & `fn_siswa_can_access_jadwal` bisa digunakan untuk authorization

---

## 📋 STORED PROCEDURES (9 Total)

### ✅ PROCEDURES YANG DIGUNAKAN (3)

#### 1. `sp_rekap_absensi_kelas(jadwal_id)`
- **Digunakan di:** `Guru/PresensiController.php`
- **Tujuan:** Rekap absensi seluruh siswa di kelas untuk jadwal tertentu
- **Return:** ResultSet (id_siswa, nama_siswa, total_hadir, total_sakit, total_izin, total_alfa, total_pertemuan)
- **Query:**
  ```php
  DB::select('CALL sp_rekap_absensi_kelas(?)', [$jadwal->id_jadwal]);
  ```
- **Digunakan untuk:** Rekap Absensi & Export Excel

#### 2. `sp_get_pengumuman_aktif(role)`
- **Digunakan di:** `SiswaController.php`, `GuruController.php`
- **Tujuan:** Mengambil pengumuman aktif sesuai role
- **Return:** ResultSet pengumuman
- **Query:**
  ```php
  DB::select('CALL sp_get_pengumuman_aktif(?)', ['siswa']);
  ```

#### 3. `sp_rekap_spp_tahun(tahun_ajaran_id)`
- **Digunakan di:** Dokumentasi (mungkin untuk admin)
- **Tujuan:** Rekap SPP per tahun ajaran
- **Return:** ResultSet (id_siswa, nisn, nama_siswa, nama_kelas, total_bayar, bulan_lunas, bulan_belum_lunas, total_tagihan)
- **Status:** Dibuat dan diperbaiki (7 Des 2025)

---

### ⚠️ PROCEDURES YANG BELUM TERPAKAI (6)

1. **sp_rekap_nilai_siswa(siswa_id, tahun_ajaran_id, [semester])**
   - Rekap nilai siswa per semester
   - Return: ResultSet nilai

2. **sp_calculate_average_tugas(siswa_id, mapel_id, tahun_ajaran_id, OUT avg)**
   - Menghitung rata-rata nilai tugas
   - Return: OUT parameter

3. **sp_check_login_attempts(user_id)**
   - Cek percobaan login user
   - Security feature

4. **sp_check_user_permission(user_id, permission)**
   - Cek permission user
   - Security feature

5. **sp_log_login_attempt(user_id, status, ip_address)**
   - Log percobaan login
   - Security feature

6. **sp_tambah_pengumuman(...)**
   - Menambah pengumuman baru
   - Alternative: Bisa pakai Eloquent

**Rekomendasi:**
- SP untuk security (login attempts, permission) bisa digunakan untuk meningkatkan keamanan
- sp_rekap_nilai_siswa bisa digunakan untuk raport

---

## 📁 TABLES (27 Total)

### Tables Utama Yang Digunakan:

1. **users** - Akun pengguna
2. **siswa** - Data siswa
3. **guru** - Data guru
4. **kelas** - Data kelas
5. **mata_pelajaran** - Mata pelajaran
6. **tahun_ajaran** - Tahun ajaran
7. **jadwal_pelajaran** - Jadwal mengajar
8. **siswa_kelas** - Relasi siswa-kelas
9. **pertemuan** - Pertemuan kelas (16 slot)
10. **detail_absensi** - Absensi siswa
11. **materi** - Materi pelajaran
12. **tugas** - Tugas
13. **detail_tugas** - Pengumpulan tugas siswa
14. **raport** - Nilai raport (Pengetahuan, Keterampilan, Sikap)
15. **pembayaran_spp** - Pembayaran SPP
16. **tagihan_batch** - Batch tagihan SPP
17. **pengumuman** - Pengumuman sekolah
18. **log_aktivitas** - Log aktivitas user

### Tables Laravel Default:
- **cache**, **cache_locks** - Laravel cache
- **jobs**, **job_batches**, **failed_jobs** - Laravel queue
- **sessions** - Session management
- **user_sessions** - Custom session tracking

---

## 🎯 REKOMENDASI PENGGUNAAN

### 1. Views Yang Perlu Digunakan:
```php
// Untuk halaman guru
view_guru_mengajar    // List jadwal mengajar guru
view_materi_guru      // Materi yang dibuat guru
view_nilai_siswa      // Input nilai raport

// Untuk halaman admin
view_siswa_kelas      // Management siswa per kelas
view_kelas_detail     // Detail kelas dengan wali kelas
```

### 2. Functions Yang Perlu Digunakan:
```php
// Untuk raport siswa
fn_convert_grade_letter(85.5)  // Convert ke huruf: B

// Untuk authorization (middleware)
fn_guru_can_access_jadwal($guru_id, $jadwal_id)
fn_siswa_can_access_jadwal($siswa_id, $jadwal_id)
```

### 3. Stored Procedures Yang Perlu Digunakan:
```php
// Untuk raport
CALL sp_rekap_nilai_siswa($siswa_id, $tahun_ajaran_id, 'Ganjil')

// Untuk security (middleware auth)
CALL sp_check_login_attempts($user_id)
CALL sp_log_login_attempt($user_id, 'success', $ip)
```

---

## 📊 STATISTIK PENGGUNAAN

### Current Usage:
- **Views:** 6/18 (33%) ✅
- **Functions:** 3/6 (50%) ⚠️
- **Stored Procedures:** 3/9 (33%) ⚠️

### Efisiensi Database:
- 🟢 **Bagus:** Stored procedures untuk rekap data kompleks
- 🟡 **Perlu Ditingkatkan:** Banyak views yang belum terpakai
- 🟡 **Perlu Ditingkatkan:** Functions bisa lebih banyak digunakan

---

## 🔧 ACTION ITEMS

### High Priority:
1. ✅ Gunakan `view_tunggakan_siswa` untuk alert SPP
2. ✅ Implementasi `fn_convert_grade_letter` di raport
3. ✅ Gunakan `sp_rekap_nilai_siswa` untuk print raport

### Medium Priority:
4. ⚠️ Review & cleanup views yang duplikat
5. ⚠️ Implementasi security procedures (login attempts)
6. ⚠️ Gunakan views untuk optimize query yang kompleks

### Low Priority:
7. 📝 Drop views yang tidak terpakai setelah review
8. 📝 Dokumentasi lengkap setiap database object
9. 📝 Buat unit test untuk stored procedures

---

## 📝 CATATAN PENTING

### Query Optimization:
- Views sudah dioptimasi dengan index yang tepat
- Stored procedures lebih cepat untuk query kompleks
- Functions cocok untuk kalkulasi yang sering dipakai

### Maintenance:
- Views perlu di-refresh jika struktur tabel berubah
- Stored procedures perlu di-update jika business logic berubah
- Selalu backup sebelum modify database objects

### Security:
- Semua database objects menggunakan `DEFINER = root@localhost`
- Pastikan user aplikasi hanya punya EXECUTE permission
- Log semua perubahan data penting

---

**Generated by:** Database Analysis Tool
**Last Updated:** 7 Desember 2025, 21:30 WIB

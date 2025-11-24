# 📚 DOKUMENTASI FITUR DATABASE - SMAN12 CONNECT

> **Sistem Informasi Akademik SMA Negeri 12**  
> Database: MySQL/MariaDB  
> Framework: Laravel 12.x  
> Last Updated: 24 November 2025

---

## 📑 DAFTAR ISI

1. [Relasi Database](#1-relasi-database)
2. [Stored Procedures](#2-stored-procedures)
3. [Functions](#3-functions)
4. [Triggers](#4-triggers)
5. [Views](#5-views)
6. [Manajemen User & Security](#6-manajemen-user--security)

---

## 1. RELASI DATABASE

### 1.1 Relasi Utama (Core Relationships)

#### **users → guru/siswa** (One-to-One)
```
users.id ─────► guru.user_id
         └────► siswa.user_id
```
- **Tujuan**: Setiap akun user memiliki profil guru atau siswa
- **Foreign Key**: `fk_guru_user`, `fk_siswa_user`
- **On Delete**: SET NULL (user bisa exist tanpa profil)
- **On Update**: NO ACTION

#### **tahun_ajaran → kelas** (One-to-Many)
```
tahun_ajaran.id_tahun_ajaran ─────► kelas.tahun_ajaran_id
```
- **Tujuan**: Setiap kelas terdaftar di tahun ajaran tertentu
- **Foreign Key**: `fk_kelas_tahun`
- **On Delete**: SET NULL
- **On Update**: CASCADE

#### **guru → kelas** (One-to-Many - Wali Kelas)
```
guru.id_guru ─────► kelas.wali_kelas_id
```
- **Tujuan**: Satu guru menjadi wali kelas
- **Foreign Key**: `fk_kelas_wali`
- **On Delete**: SET NULL
- **On Update**: NO ACTION

#### **kelas → siswa** (One-to-Many)
```
kelas.id_kelas ─────► siswa.kelas_id
```
- **Tujuan**: Siswa tergabung dalam satu kelas
- **Foreign Key**: `fk_siswa_kelas`
- **On Delete**: SET NULL
- **On Update**: CASCADE

#### **mata_pelajaran → guru** (One-to-Many)
```
mata_pelajaran.id_mapel ─────► guru.mapel_id
```
- **Tujuan**: Guru mengajar mata pelajaran tertentu
- **Foreign Key**: `fk_guru_mapel`
- **On Delete**: SET NULL
- **On Update**: NO ACTION

### 1.2 Relasi Jadwal & Pembelajaran

#### **jadwal_pelajaran** (Many-to-Many Hub)
```
tahun_ajaran.id_tahun_ajaran ─────┐
kelas.id_kelas ──────────────────┤
mata_pelajaran.id_mapel ─────────┼───► jadwal_pelajaran
guru.id_guru ────────────────────┘
```
- **Tujuan**: Jadwal mengaitkan tahun ajaran, kelas, mapel, dan guru
- **Foreign Keys**: 
  - `fk_jadwal_tahun` (CASCADE/CASCADE)
  - `fk_jadwal_kelas` (CASCADE/CASCADE)
  - `fk_jadwal_mapel` (CASCADE/CASCADE)
  - `fk_jadwal_guru` (CASCADE/CASCADE)

#### **pertemuan** (Sesi Pembelajaran)
```
jadwal_pelajaran.id_jadwal ─────► pertemuan.jadwal_id
users.id ───────────────────────► pertemuan.submitted_by
```
- **Tujuan**: Merekam setiap sesi tatap muka
- **Foreign Keys**:
  - `fk_pertemuan_jadwal` (CASCADE/CASCADE)
  - `fk_pertemuan_submitted_by` (CASCADE/SET NULL)
- **Atribut**: tanggal_pertemuan, topik_bahasan, status_sesi, qr_token

### 1.3 Relasi Absensi

#### **detail_absensi**
```
pertemuan.id_pertemuan ─────► detail_absensi.pertemuan_id
siswa.id_siswa ─────────────► detail_absensi.siswa_id
```
- **Tujuan**: Mencatat kehadiran siswa per pertemuan
- **Foreign Keys**:
  - `fk_absensi_pertemuan` (CASCADE/CASCADE)
  - `fk_absensi_siswa` (CASCADE/CASCADE)
- **Status**: Hadir, Izin, Sakit, Alfa

### 1.4 Relasi Tugas

#### **tugas**
```
jadwal_pelajaran.id_jadwal ─────► tugas.jadwal_id
pertemuan.id_pertemuan ─────────► tugas.pertemuan_id (optional)
```
- **Foreign Keys**:
  - `fk_tugas_jadwal` (CASCADE/CASCADE)
  - `fk_tugas_pertemuan` (CASCADE/SET NULL)

#### **detail_tugas** (Pengumpulan)
```
tugas.id_tugas ─────► detail_tugas.tugas_id
siswa.id_siswa ─────► detail_tugas.siswa_id
```
- **Foreign Keys**:
  - `fk_detail_tugas` (CASCADE/CASCADE)
  - `fk_detail_siswa` (CASCADE/CASCADE)
- **Atribut**: file_path, nilai_tugas, status_pengumpulan

### 1.5 Relasi Materi

#### **materi**
```
jadwal_pelajaran.id_jadwal ─────► materi.jadwal_id
pertemuan.id_pertemuan ─────────► materi.pertemuan_id (optional)
```
- **Foreign Keys**:
  - `fk_materi_jadwal` (CASCADE/CASCADE)
  - `fk_materi_pertemuan` (CASCADE/SET NULL)
- **Atribut**: judul_materi, deskripsi, file_path

### 1.6 Relasi Nilai (Raport)

#### **nilai**
```
tahun_ajaran.id_tahun_ajaran ─────► nilai.tahun_ajaran_id
siswa.id_siswa ───────────────────► nilai.siswa_id
mata_pelajaran.id_mapel ──────────► nilai.mapel_id
```
- **Foreign Keys**:
  - `fk_nilai_tahun` (CASCADE/CASCADE)
  - `fk_nilai_siswa` (CASCADE/CASCADE)
  - `fk_nilai_mapel` (CASCADE/CASCADE)
- **Komponen Nilai**: nilai_tugas, nilai_uts, nilai_uas, nilai_akhir
- **Semester**: Ganjil/Genap

### 1.7 Relasi Pembayaran SPP

#### **pembayaran_spp**
```
siswa.id_siswa ───────────────────► pembayaran_spp.siswa_id
tahun_ajaran.id_tahun_ajaran ─────► pembayaran_spp.tahun_ajaran_id
tagihan_batch.id_batch ───────────► pembayaran_spp.batch_id (optional)
```
- **Foreign Keys**:
  - `fk_pembayaran_siswa` (CASCADE/CASCADE)
  - `fk_pembayaran_tahun` (CASCADE/RESTRICT)
  - `fk_pembayaran_batch` (CASCADE/SET NULL)
- **Status**: Lunas, Belum Lunas
- **Atribut**: bulan, tahun, jumlah_bayar, metode_pembayaran, bukti_pembayaran

#### **tagihan_batch** (Generate Tagihan Massal)
```
users.id ────────────────────────► tagihan_batch.admin_id
tahun_ajaran.id_tahun_ajaran ─────► tagihan_batch.tahun_ajaran_id
```
- **Foreign Keys**:
  - `fk_batch_admin` (CASCADE/RESTRICT)
  - `fk_batch_tahun` (CASCADE/RESTRICT)
- **Tujuan**: Tracking generate tagihan massal per tahun ajaran

### 1.8 Relasi Siswa-Kelas (History)

#### **siswa_kelas** (Many-to-Many)
```
siswa.id_siswa ───────────────────► siswa_kelas.siswa_id
kelas.id_kelas ───────────────────► siswa_kelas.kelas_id
tahun_ajaran.id_tahun_ajaran ─────► siswa_kelas.tahun_ajaran_id
```
- **Tujuan**: Tracking history kelas siswa per tahun ajaran
- **Foreign Keys**:
  - `fk_sk_siswa` (CASCADE/CASCADE)
  - `fk_sk_kelas` (CASCADE/CASCADE)
  - `fk_sk_tahun` (CASCADE/CASCADE)

### 1.9 Relasi Lainnya

#### **pengumuman**
```
users.id ─────► pengumuman.author_id
```
- **Foreign Key**: `fk_pengumuman_author` (CASCADE/CASCADE)
- **Atribut**: judul, konten, kategori, tanggal_mulai, tanggal_selesai

#### **user_sessions**
```
users.id ─────► user_sessions.user_id
```
- **Foreign Key**: `fk_session_user` (CASCADE/CASCADE)
- **Tujuan**: Tracking sesi login user

---

## 2. STORED PROCEDURES

### 2.1 `sp_rekap_absensi_kelas`
**Rekap absensi siswa dalam satu kelas**

```sql
CALL sp_rekap_absensi_kelas(id_kelas, tanggal_awal, tanggal_akhir);
```

**Parameter:**
- `id_kelas_param` (BIGINT): ID kelas yang akan direkap
- `tanggal_awal` (DATE): Tanggal mulai periode
- `tanggal_akhir` (DATE): Tanggal akhir periode

**Output:**
| Column | Type | Deskripsi |
|--------|------|-----------|
| nama_lengkap | VARCHAR | Nama siswa |
| nis | VARCHAR | NIS siswa |
| total_pertemuan | INT | Total pertemuan yang ada |
| hadir | INT | Total kehadiran |
| izin | INT | Total izin |
| sakit | INT | Total sakit |
| alfa | INT | Total alfa |

**Contoh Penggunaan:**
```php
$results = DB::select('CALL sp_rekap_absensi_kelas(?, ?, ?)', [
    $kelasId, 
    '2025-01-01', 
    '2025-06-30'
]);
```

---

### 2.2 `sp_rekap_nilai_siswa`
**Rekap nilai siswa per tahun ajaran**

```sql
CALL sp_rekap_nilai_siswa(siswa_id, tahun_ajaran_id);
```

**Parameter:**
- `siswa_id_param` (BIGINT): ID siswa
- `tahun_ajaran_id_param` (BIGINT): ID tahun ajaran

**Output:**
| Column | Type | Deskripsi |
|--------|------|-----------|
| nama_lengkap | VARCHAR | Nama siswa |
| nama_mapel | VARCHAR | Nama mata pelajaran |
| nilai_tugas | DECIMAL | Nilai tugas rata-rata |
| nilai_uts | DECIMAL | Nilai UTS |
| nilai_uas | DECIMAL | Nilai UAS |
| nilai_akhir | DECIMAL | Nilai akhir (raport) |

**Contoh Penggunaan:**
```php
$raport = DB::select('CALL sp_rekap_nilai_siswa(?, ?)', [
    $siswaId, 
    $tahunAjaranId
]);
```

---

### 2.3 `sp_rekap_spp_tahun`
**Rekap pembayaran SPP per tahun ajaran**

```sql
CALL sp_rekap_spp_tahun(tahun_ajaran_id);
```

**Parameter:**
- `id_tahun_ajaran_param` (BIGINT): ID tahun ajaran

**Output:**
| Column | Type | Deskripsi |
|--------|------|-----------|
| nama_lengkap | VARCHAR | Nama siswa |
| total_bayar | DECIMAL | Total yang sudah dibayar |
| bulan_belum_lunas | INT | Jumlah bulan yang belum lunas |

**Contoh Penggunaan:**
```php
$rekapSPP = DB::select('CALL sp_rekap_spp_tahun(?)', [$tahunAjaranId]);
```

---

### 2.4 `sp_get_jadwal_siswa`
**Ambil jadwal pelajaran siswa**

```sql
CALL sp_get_jadwal_siswa(siswa_id);
```

**Digunakan untuk menampilkan jadwal mingguan siswa.**

---

### 2.5 `sp_get_jadwal_guru`
**Ambil jadwal mengajar guru**

```sql
CALL sp_get_jadwal_guru(guru_id);
```

**Digunakan untuk menampilkan jadwal mengajar guru.**

---

### 2.6 `sp_get_pembayaran_siswa`
**Ambil history pembayaran SPP siswa**

```sql
CALL sp_get_pembayaran_siswa(siswa_id, tahun_ajaran_id);
```

---

### 2.7 `sp_get_materi_by_pertemuan`
**Ambil materi berdasarkan pertemuan**

```sql
CALL sp_get_materi_by_pertemuan(pertemuan_id);
```

---

### 2.8 `sp_get_pengumuman_aktif`
**Ambil pengumuman yang masih aktif**

```sql
CALL sp_get_pengumuman_aktif();
```

**Menampilkan pengumuman yang tanggal sekarang berada di antara tanggal_mulai dan tanggal_selesai.**

---

### 2.9 `sp_get_presensi_pertemuan`
**Ambil data presensi per pertemuan**

```sql
CALL sp_get_presensi_pertemuan(pertemuan_id);
```

---

### 2.10 `sp_calculate_average_tugas`
**Hitung rata-rata nilai tugas per semester**

```sql
CALL sp_calculate_average_tugas(siswa_id, mapel_id, semester);
```

**Digunakan untuk menghitung rata-rata nilai tugas yang akan dimasukkan ke field `nilai_tugas` di tabel `nilai`.**

---

## 3. FUNCTIONS

### 3.1 `fn_hadir_persen`
**Menghitung persentase kehadiran siswa**

```sql
SELECT fn_hadir_persen(siswa_id, tahun_ajaran_id);
```

**Parameter:**
- `siswa_id_param` (BIGINT): ID siswa
- `tahun_ajaran_id_param` (BIGINT): ID tahun ajaran

**Return:** DECIMAL(5,2) - Persentase kehadiran (0-100)

**Logic:**
1. Hitung total pertemuan yang diikuti siswa
2. Hitung total pertemuan dengan status 'Hadir'
3. Persentase = (total_hadir / total_pertemuan) × 100

**Contoh Penggunaan:**
```php
$persentase = DB::selectOne('SELECT fn_hadir_persen(?, ?) as persen', [
    $siswaId, 
    $tahunAjaranId
])->persen;
```

---

### 3.2 `fn_rata_nilai`
**Menghitung rata-rata nilai akhir siswa**

```sql
SELECT fn_rata_nilai(siswa_id, tahun_ajaran_id);
```

**Parameter:**
- `siswa_id_param` (BIGINT): ID siswa
- `tahun_ajaran_id_param` (BIGINT): ID tahun ajaran

**Return:** DECIMAL(5,2) - Rata-rata nilai (0-100)

**Logic:**
1. Ambil semua nilai_akhir dari tabel `nilai`
2. Hitung AVG(nilai_akhir)
3. Return 0 jika tidak ada nilai

**Contoh Penggunaan:**
```php
$rataRata = DB::selectOne('SELECT fn_rata_nilai(?, ?) as rata', [
    $siswaId, 
    $tahunAjaranId
])->rata;
```

---

### 3.3 `fn_total_spp_siswa`
**Menghitung total pembayaran SPP yang sudah lunas**

```sql
SELECT fn_total_spp_siswa(siswa_id, tahun);
```

**Parameter:**
- `siswa_id_param` (BIGINT): ID siswa
- `tahun_param` (YEAR): Tahun pembayaran (misal: 2025)

**Return:** DECIMAL(15,2) - Total pembayaran yang sudah lunas

**Logic:**
1. SUM(jumlah_bayar) dari tabel `pembayaran_spp`
2. WHERE status = 'Lunas' dan tahun = tahun_param
3. Return 0 jika belum ada pembayaran

**Contoh Penggunaan:**
```php
$totalBayar = DB::selectOne('SELECT fn_total_spp_siswa(?, ?) as total', [
    $siswaId, 
    2025
])->total;
```

---

### 3.4 `fn_convert_grade_letter`
**Konversi nilai angka ke huruf (A, B, C, D, E)**

```sql
SELECT fn_convert_grade_letter(nilai);
```

**Parameter:**
- `p_nilai` (DECIMAL(5,2)): Nilai angka (0-100)

**Return:** CHAR(1) - Nilai huruf

**Konversi:**
- A: nilai ≥ 90
- B: nilai ≥ 80
- C: nilai ≥ 70
- D: nilai ≥ 60
- E: nilai < 60

**Contoh Penggunaan:**
```php
$grade = DB::selectOne('SELECT fn_convert_grade_letter(?) as grade', [85])->grade;
// Output: "B"
```

---

### 3.5 `fn_guru_can_access_jadwal`
**Validasi apakah guru bisa mengakses jadwal tertentu**

```sql
SELECT fn_guru_can_access_jadwal(guru_id, jadwal_id);
```

**Return:** BOOLEAN (1/0)

**Digunakan untuk authorization di controller.**

---

### 3.6 `fn_siswa_can_access_jadwal`
**Validasi apakah siswa bisa mengakses jadwal tertentu**

```sql
SELECT fn_siswa_can_access_jadwal(siswa_id, jadwal_id);
```

**Return:** BOOLEAN (1/0)

**Digunakan untuk authorization di controller.**

---

## 4. TRIGGERS

### 4.1 `log_insert_nilai`
**Trigger setelah INSERT ke tabel `nilai`**

**Aksi:** AFTER INSERT  
**Tabel:** nilai

**Fungsi:**
- Mencatat log aktivitas saat guru input nilai siswa
- Log disimpan ke tabel `log_aktivitas`

**Data yang dicatat:**
- jenis_aktivitas: "raport"
- deskripsi: "Input nilai [Mapel] untuk siswa [Nama] - Nilai Akhir: [nilai]"
- user_id: dari session variable `@current_user_id`
- role: dari session variable `@current_user_role`
- nama_tabel: "nilai"
- id_referensi: id_nilai yang baru dibuat
- aksi: "insert"
- ip_address: dari `@current_ip_address`
- user_agent: dari `@current_user_agent`

**Cara Set Session Variable (di Controller):**
```php
DB::statement('SET @current_user_id = ?', [Auth::id()]);
DB::statement('SET @current_user_role = ?', [Auth::user()->role]);
DB::statement('SET @current_ip_address = ?', [request()->ip()]);
DB::statement('SET @current_user_agent = ?', [request()->userAgent()]);
```

---

### 4.2 `log_update_nilai`
**Trigger setelah UPDATE ke tabel `nilai`**

**Aksi:** AFTER UPDATE  
**Tabel:** nilai

**Fungsi:**
- Mencatat log aktivitas saat guru update nilai siswa
- Deskripsi mencakup perbandingan OLD vs NEW nilai

---

### 4.3 `log_insert_pembayaran_spp`
**Trigger setelah INSERT ke tabel `pembayaran_spp`**

**Aksi:** AFTER INSERT  
**Tabel:** pembayaran_spp

**Fungsi:**
- Mencatat log aktivitas pembayaran SPP
- jenis_aktivitas: "pembayaran"
- deskripsi: "Pembayaran SPP [Nama Siswa] - Bulan [bulan] Tahun [tahun] - Rp [jumlah]"

---

### 4.4 `log_update_pembayaran_spp`
**Trigger setelah UPDATE ke tabel `pembayaran_spp`**

**Aksi:** AFTER UPDATE  
**Tabel:** pembayaran_spp

**Fungsi:**
- Mencatat log perubahan status pembayaran
- Tracking perubahan dari "Belum Lunas" ke "Lunas"

---

### 4.5 `log_insert_absensi`
**Trigger setelah INSERT ke tabel `detail_absensi`**

**Aksi:** AFTER INSERT  
**Tabel:** detail_absensi

**Fungsi:**
- Mencatat log aktivitas input absensi
- jenis_aktivitas: "absensi"
- deskripsi: "Input absensi [Nama Siswa] - Status: [status_kehadiran]"

---

### 4.6 `log_insert_detail_tugas`
**Trigger setelah INSERT ke tabel `detail_tugas`**

**Aksi:** AFTER INSERT  
**Tabel:** detail_tugas

**Fungsi:**
- Mencatat log aktivitas pengumpulan tugas
- jenis_aktivitas: "tugas"
- deskripsi: "Pengumpulan tugas [Judul Tugas] oleh [Nama Siswa]"

---

## 5. VIEWS

### 5.1 `view_dashboard_siswa`
**Dashboard overview untuk siswa**

**Kolom:**
- id_siswa, nis, nisn, nama_lengkap
- nama_kelas, tingkat
- tahun_mulai, tahun_selesai, semester
- total_mapel: Jumlah mata pelajaran
- tagihan_belum_lunas: Jumlah tagihan belum dibayar
- tagihan_lunas: Jumlah tagihan sudah dibayar
- rata_rata_nilai: Rata-rata nilai akhir

**Filter:** Hanya tahun ajaran dengan status 'Aktif'

**Contoh Query:**
```php
$dashboard = DB::table('view_dashboard_siswa')
    ->where('id_siswa', $siswaId)
    ->first();
```

---

### 5.2 `view_absensi_siswa`
**Detail absensi siswa lengkap**

**Kolom:**
- id_detail_absensi, status_kehadiran, keterangan
- id_siswa, nis, nama_siswa
- nama_kelas
- tanggal_pertemuan, topik_bahasan, status_sesi
- nama_mapel, nama_guru
- hari, jam_mulai, jam_selesai
- tahun_mulai, tahun_selesai, semester

**Contoh Query:**
```php
$absensi = DB::table('view_absensi_siswa')
    ->where('id_siswa', $siswaId)
    ->whereBetween('tanggal_pertemuan', ['2025-01-01', '2025-06-30'])
    ->get();
```

---

### 5.3 `view_nilai_siswa`
**Data nilai siswa per mata pelajaran**

**Kolom:**
- id_siswa, nama_lengkap, nis
- nama_mapel
- nilai_tugas, nilai_uts, nilai_uas, nilai_akhir
- semester
- tahun_ajaran

**Contoh Query:**
```php
$nilai = DB::table('view_nilai_siswa')
    ->where('id_siswa', $siswaId)
    ->where('semester', 'Ganjil')
    ->get();
```

---

### 5.4 `view_pembayaran_spp`
**History pembayaran SPP siswa**

**Kolom:**
- id_pembayaran, siswa_id, nama_lengkap, nis
- bulan, tahun, jumlah_bayar
- tanggal_bayar, metode_pembayaran, status
- bukti_pembayaran
- tahun_ajaran

**Contoh Query:**
```php
$pembayaran = DB::table('view_pembayaran_spp')
    ->where('siswa_id', $siswaId)
    ->where('tahun', 2025)
    ->orderBy('bulan')
    ->get();
```

---

### 5.5 `view_tunggakan_siswa`
**Rekap tunggakan SPP siswa**

**Kolom:**
- id_siswa, nisn, nama
- id_tahun_ajaran, tahun_mulai, tahun_selesai, semester
- jumlah_tunggakan: Jumlah bulan yang belum dibayar
- total_tunggakan: Total nominal tunggakan
- bulan_tertunggak_pertama: Format YYYY-MM
- bulan_tertunggak_terakhir: Format YYYY-MM

**Filter:** Hanya tahun ajaran dengan status 'Aktif'

**Contoh Query:**
```php
$tunggakan = DB::table('view_tunggakan_siswa')
    ->where('jumlah_tunggakan', '>', 0)
    ->orderBy('total_tunggakan', 'desc')
    ->get();
```

---

### 5.6 `view_jadwal_guru`
**Jadwal mengajar guru**

**Kolom:**
- id_jadwal
- nama_guru, nip
- nama_mapel
- nama_kelas, tingkat
- hari, jam_mulai, jam_selesai
- tahun_ajaran, semester

---

### 5.7 `view_data_guru`
**Data lengkap guru dengan user info**

**Kolom:**
- id_guru, nip, nama_lengkap
- email, no_telepon, alamat
- nama_mapel (mata pelajaran yang diampu)
- foto_profil
- status_akun (dari tabel users)

---

### 5.8 `view_data_siswa`
**Data lengkap siswa dengan kelas**

**Kolom:**
- id_siswa, nisn, nis, nama_lengkap
- jenis_kelamin, tempat_lahir, tgl_lahir
- nama_kelas, tingkat
- nama_wali_kelas
- email, no_telepon
- foto_profil
- tahun_ajaran, semester

---

### 5.9 `view_tugas_siswa`
**Daftar tugas dan status pengumpulan**

**Kolom:**
- id_tugas, judul_tugas, deskripsi, deadline
- nama_mapel, nama_guru
- status_pengumpulan, nilai_tugas
- file_path (file yang dikumpulkan siswa)
- waktu_pengumpulan

---

### 5.10 `view_materi_guru`
**Materi pembelajaran yang diunggah guru**

**Kolom:**
- id_materi, judul_materi, deskripsi
- file_path
- nama_mapel, nama_kelas
- tanggal_pertemuan, topik_bahasan
- nama_guru

---

### 5.11 `view_user_permissions`
**Mapping user dengan role dan permissions**

**Kolom:**
- user_id, name, email, role
- id_guru (jika role = guru)
- id_siswa (jika role = siswa)
- status_akun

**Digunakan untuk middleware dan authorization.**

---

### 5.12 `view_pengumuman_dashboard`
**Pengumuman yang ditampilkan di dashboard**

**Kolom:**
- id_pengumuman, judul, konten, kategori
- author_name (nama pembuat pengumuman)
- tanggal_mulai, tanggal_selesai
- target_role (admin/guru/siswa/semua)

**Filter:** Hanya pengumuman yang masih aktif (NOW() BETWEEN tanggal_mulai AND tanggal_selesai)

---

## 6. MANAJEMEN USER & SECURITY

### 6.1 Konsep Role-Based Database Access

**Sistem ini menggunakan 3 MySQL user static yang di-share berdasarkan role:**

```
┌─────────────────────────────────────────────────────┐
│         APPLICATION USERS (Tabel: users)            │
│  ┌─────────┬─────────┬─────────┬─────────┐          │
│  │ Admin 1 │ Admin 2 │ Admin 3 │   ...   │          │
│  └────┬────┴────┬────┴────┬────┴─────────┘          │
│       │         │         │                          │
│       └─────────┴─────────┘                          │
│              ↓                                        │
│     MySQL User: admin_sia@localhost                  │
│     Grants: ALL PRIVILEGES                           │
└─────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────┐
│         APPLICATION USERS (Tabel: users)            │
│  ┌─────────┬─────────┬─────────┬─────────┐          │
│  │ Guru 1  │ Guru 2  │ Guru 3  │   ...   │          │
│  └────┬────┴────┬────┴────┬────┴─────────┘          │
│       │         │         │                          │
│       └─────────┴─────────┘                          │
│              ↓                                        │
│     MySQL User: guru_sia@localhost                   │
│     Grants: SELECT/INSERT/UPDATE/DELETE              │
│            (absensi, nilai, materi, tugas)           │
└─────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────┐
│         APPLICATION USERS (Tabel: users)            │
│  ┌──────────┬──────────┬──────────┬─────────┐       │
│  │ Siswa 1  │ Siswa 2  │ Siswa 3  │   ...   │       │
│  └────┬─────┴────┬─────┴────┬─────┴─────────┘       │
│       │          │          │                        │
│       └──────────┴──────────┘                        │
│              ↓                                        │
│     MySQL User: siswa_sia@localhost                  │
│     Grants: SELECT (semua tabel)                     │
│            INSERT (detail_absensi, detail_tugas)     │
│            INSERT/UPDATE (pembayaran_spp)            │
└─────────────────────────────────────────────────────┘
```

---

### 6.2 MySQL Users & Credentials

**File:** `.env`

```env
DB_USER_ADMIN=admin_sia
DB_PASSWORD_ADMIN=admin123

DB_USER_GURU=guru_sia
DB_PASSWORD_GURU=guru123

DB_USER_SISWA=siswa_sia
DB_PASSWORD_SISWA=siswa123
```

---

### 6.3 Database Connections

**File:** `config/database.php`

```php
'connections' => [
    'mysql' => [
        'driver' => 'mysql',
        'host' => env('DB_HOST', '127.0.0.1'),
        'database' => env('DB_DATABASE', 'sman_connect'),
        'username' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD', ''),
        // ... default connection untuk migration/seeder
    ],
    
    'mysql_admin' => [
        'driver' => 'mysql',
        'host' => env('DB_HOST', '127.0.0.1'),
        'database' => env('DB_DATABASE', 'sman_connect'),
        'username' => env('DB_USER_ADMIN', 'admin_sia'),
        'password' => env('DB_PASSWORD_ADMIN', ''),
    ],
    
    'mysql_guru' => [
        'driver' => 'mysql',
        'host' => env('DB_HOST', '127.0.0.1'),
        'database' => env('DB_DATABASE', 'sman_connect'),
        'username' => env('DB_USER_GURU', 'guru_sia'),
        'password' => env('DB_PASSWORD_GURU', ''),
    ],
    
    'mysql_siswa' => [
        'driver' => 'mysql',
        'host' => env('DB_HOST', '127.0.0.1'),
        'database' => env('DB_DATABASE', 'sman_connect'),
        'username' => env('DB_USER_SISWA', 'siswa_sia'),
        'password' => env('DB_PASSWORD_SISWA', ''),
    ],
],
```

---

### 6.4 Middleware: SetDatabaseConnection

**File:** `app/Http/Middleware/SetDatabaseConnection.php`

```php
public function handle(Request $request, Closure $next)
{
    if (Auth::check()) {
        $role = Auth::user()->role;
        
        $connection = match($role) {
            'admin' => 'mysql_admin',
            'guru' => 'mysql_guru',
            'siswa' => 'mysql_siswa',
            default => 'mysql',
        };
        
        Config::set('database.default', $connection);
        DB::reconnect();
    }
    
    return $next($request);
}
```

**Registered di:** `bootstrap/app.php`

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->append(SetDatabaseConnection::class);
})
```

---

### 6.5 MySQL Grants Detail

#### **Admin (admin_sia@localhost)**
```sql
GRANT ALL PRIVILEGES ON sman_connect.* TO 'admin_sia'@'localhost';
```

**Akses:**
- ✅ Full access ke semua tabel
- ✅ CREATE, DROP, ALTER table
- ✅ GRANT privileges ke user lain

---

#### **Guru (guru_sia@localhost)**
```sql
-- Read-only untuk semua tabel
GRANT SELECT ON sman_connect.* TO 'guru_sia'@'localhost';

-- Full access untuk tabel pembelajaran
GRANT SELECT, INSERT, UPDATE, DELETE ON sman_connect.detail_absensi TO 'guru_sia'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON sman_connect.nilai TO 'guru_sia'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON sman_connect.materi TO 'guru_sia'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON sman_connect.tugas TO 'guru_sia'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON sman_connect.pertemuan TO 'guru_sia'@'localhost';

-- Update nilai tugas yang dikumpulkan siswa
GRANT SELECT, UPDATE ON sman_connect.detail_tugas TO 'guru_sia'@'localhost';
```

**Akses:**
- ✅ Baca semua data (SELECT)
- ✅ Input/edit absensi siswa
- ✅ Input/edit nilai raport
- ✅ Upload/edit materi pembelajaran
- ✅ Buat/edit tugas
- ✅ Input pertemuan (topik bahasan)
- ✅ Beri nilai tugas siswa
- ❌ TIDAK BISA insert/update/delete siswa, guru, kelas, jadwal

---

#### **Siswa (siswa_sia@localhost)**
```sql
-- Read-only untuk semua tabel
GRANT SELECT ON sman_connect.* TO 'siswa_sia'@'localhost';

-- Insert absensi (scan QR)
GRANT SELECT, INSERT ON sman_connect.detail_absensi TO 'siswa_sia'@'localhost';

-- Submit tugas dan update file
GRANT SELECT, INSERT, UPDATE ON sman_connect.detail_tugas TO 'siswa_sia'@'localhost';

-- Bayar SPP
GRANT SELECT, INSERT, UPDATE ON sman_connect.pembayaran_spp TO 'siswa_sia'@'localhost';
```

**Akses:**
- ✅ Baca semua data (lihat nilai, jadwal, materi, dll)
- ✅ Input absensi via QR Code
- ✅ Submit tugas
- ✅ Bayar SPP (insert pembayaran)
- ❌ TIDAK BISA edit nilai
- ❌ TIDAK BISA edit data guru/siswa lain
- ❌ TIDAK BISA hapus data apapun

---

### 6.6 Testing Connections

**File:** `test-role-access.php`

```bash
php test-role-access.php
```

**Output:**
```
✅ MySQL Users: 3 static users (admin_sia, guru_sia, siswa_sia)
✅ Database Connections: All connections working
✅ Security: Role-based access properly enforced
✅ Middleware: Connection switching logic implemented
✅ Grants: Proper privileges assigned per role
```

---

### 6.7 Cara Kerja Flow Authentication

```
1. User login (email + password)
   ↓
2. Auth::attempt() → Session created
   ↓
3. Middleware SetDatabaseConnection detect role
   ↓
4. Switch DB connection berdasarkan role:
   - Admin → mysql_admin (admin_sia)
   - Guru → mysql_guru (guru_sia)
   - Siswa → mysql_siswa (siswa_sia)
   ↓
5. Query menggunakan connection role-specific
   ↓
6. MySQL enforce grants:
   - Guru TIDAK BISA insert ke tabel guru
   - Siswa TIDAK BISA update nilai
   - Admin bisa semua
```

---

## 📝 CATATAN PENTING

### Best Practices

1. **Selalu gunakan Stored Procedures** untuk query kompleks yang sering dipakai
2. **Gunakan Functions** untuk kalkulasi yang reusable (persentase, rata-rata, dll)
3. **Triggers digunakan untuk audit log** - jangan untuk business logic kompleks
4. **Views untuk simplify query** - terutama yang melibatkan banyak JOIN
5. **Set session variables** sebelum INSERT/UPDATE untuk tracking di trigger:
   ```php
   DB::statement('SET @current_user_id = ?', [Auth::id()]);
   DB::statement('SET @current_user_role = ?', [Auth::user()->role]);
   ```

### Security Notes

1. **NEVER expose root credentials** - hanya untuk migration/seeder
2. **Gunakan prepared statements** untuk semua user input
3. **Validate user_id** di controller sebelum query
4. **Authorization checks** menggunakan fn_guru_can_access_jadwal / fn_siswa_can_access_jadwal
5. **Log semua aktivitas** menggunakan trigger log_aktivitas

---

## 📞 SUPPORT

**Developer:** Tim SMAN12-CONNECT  
**Database:** MySQL/MariaDB 10.x  
**Framework:** Laravel 12.x  
**Repository:** https://github.com/Zahran40/SMAN12-CONNECT

---

**Last Updated:** 24 November 2025  
**Version:** 1.0.0

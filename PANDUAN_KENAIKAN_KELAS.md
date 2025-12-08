# 🎓 PANDUAN KENAIKAN KELAS OTOMATIS

## 📋 Konsep Sistem Kenaikan Kelas

### Masalah yang Diselesaikan
Sebelumnya, siswa yang sama bisa tercatat di kelas yang sama untuk **tahun ajaran yang berbeda** (misal: siswa X-E1 di tahun 2025/2026 tetap di X-E1 di tahun 2027/2028). Ini **SALAH dan REDUNDAN**.

### Solusi
Sistem kenaikan kelas otomatis yang akan:
1. **Naikan tingkat** siswa sesuai logika:
   - Kelas **X** (10) → **XI** (11)
   - Kelas **XI** (11) → **XII** (12)
   - Kelas **XII** (12) → **Lulus** (status tidak aktif)

2. **Pindahkan siswa** ke tahun ajaran baru dengan kelas yang sesuai

3. **Nonaktifkan** siswa di tahun ajaran lama (histori tetap tersimpan)

---

## 🚀 Cara Menggunakan

### Via Admin Panel (UI)

#### 1. Persiapan
Pastikan sudah ada:
- ✅ Tahun ajaran **lama** (contoh: 2025/2026) dengan siswa aktif
- ✅ Tahun ajaran **baru** (contoh: 2026/2027) dengan **semester Ganjil** aktif
- ✅ Kelas sudah dibuat di tahun ajaran baru (gunakan "Generate 30 Kelas" atau buat manual)

#### 2. Langkah-langkah

1. **Login sebagai Admin**
2. **Buka Menu**: Manajemen Tahun Ajaran
3. **Klik Tombol**: "🔼 Naikkan Kelas" (di pojok kanan atas, antara tombol Arsip dan Tambah Tahun Ajaran)
   - Tombol ini hanya muncul jika:
     - Ada tahun ajaran dengan semester Ganjil yang **aktif**
     - Ada tahun ajaran sebelumnya dengan semester yang **tidak aktif**

4. **Konfirmasi di Modal**:
   - Akan muncul modal yang menampilkan:
     - Tahun ajaran lama (sumber)
     - Tahun ajaran baru (tujuan)
     - Penjelasan proses (X→XI, XI→XII, XII→Lulus)
   
5. **Klik "Proses"** untuk melanjutkan atau **"Batal"** untuk membatalkan

6. **Tunggu Proses Selesai**
   - Sistem akan menampilkan pesan sukses dengan statistik:
     - Berapa siswa naik dari X ke XI
     - Berapa siswa naik dari XI ke XII
     - Berapa siswa lulus (dari XII)
     - Berapa siswa yang tidak bisa dinaikkan (jika ada)

#### Contoh Output Sukses:
```
✅ Kenaikan kelas berhasil! 
   X→XI: 120 siswa, XI→XII: 115 siswa, XII→Lulus: 110 siswa
```

---

### Via Command Line (Artisan)

Jika ingin melakukan kenaikan kelas via terminal (untuk automasi atau cron job):

#### Sintaks:
```bash
php artisan siswa:naik-kelas {tahun_ajaran_lama_id} {tahun_ajaran_baru_id} [--force]
```

#### Parameter:
- `tahun_ajaran_lama_id` - ID tahun ajaran sumber (yang akan dinaikkan)
- `tahun_ajaran_baru_id` - ID tahun ajaran tujuan (semester Ganjil)
- `--force` - (Optional) Skip konfirmasi manual

#### Contoh:
```bash
# Cek ID tahun ajaran terlebih dahulu
php artisan tinker
>>> App\Models\TahunAjaran::select('id_tahun_ajaran','tahun_mulai','tahun_selesai','semester','status')->get();

# Misal: ID Ganjil 2025/2026 = 1, ID Ganjil 2026/2027 = 3
php artisan siswa:naik-kelas 1 3

# Atau dengan --force untuk skip konfirmasi
php artisan siswa:naik-kelas 1 3 --force
```

#### Output Console:
```
===========================================
PROSES KENAIKAN KELAS OTOMATIS
===========================================
Dari: 2025/2026 (Ganjil)
Ke  : 2026/2027 (Ganjil)

Total siswa aktif di tahun ajaran lama: 345

[Progress Bar] 345/345 ████████████████████ 100%

===========================================
HASIL KENAIKAN KELAS
===========================================
✅ X → XI       : 120 siswa
✅ XI → XII     : 115 siswa
🎓 XII → Lulus  : 110 siswa

Proses kenaikan kelas selesai!
```

---

## 📊 Logika Kenaikan Kelas

### Mapping Tingkat Kelas

| Kelas Lama | Kelas Baru | Status |
|------------|------------|--------|
| X-MIPA 1   | XI-MIPA 1  | Naik   |
| X-IPS 2    | XI-IPS 2   | Naik   |
| XI-MIPA 3  | XII-MIPA 3 | Naik   |
| XI-IPS 4   | XII-IPS 4  | Naik   |
| XII-MIPA 5 | -          | Lulus  |
| XII-IPS 1  | -          | Lulus  |

### Detail Proses per Siswa

1. **Identifikasi Tingkat Lama**
   - Baca tingkat dari kelas siswa di tahun ajaran lama (X, XI, atau XII)

2. **Tentukan Tingkat Baru**
   - X → XI
   - XI → XII
   - XII → Status "Lulus" (tidak perlu kelas baru)

3. **Cari Kelas Baru**
   - Di tahun ajaran baru (semester Ganjil)
   - Dengan tingkat baru
   - Dengan **jurusan yang sama** (MIPA tetap MIPA, IPS tetap IPS)

4. **Eksekusi Kenaikan**
   - Nonaktifkan siswa di tabel `siswa_kelas` tahun lama:
     ```sql
     status = 'Tidak Aktif'
     tanggal_keluar = sekarang
     ```
   - Tambahkan siswa ke tabel `siswa_kelas` tahun baru:
     ```sql
     siswa_id = sama
     kelas_id = kelas baru
     tahun_ajaran_id = tahun baru
     status = 'Aktif'
     tanggal_masuk = sekarang
     ```
   - Update kolom `kelas_id` di tabel `siswa` (untuk kompatibilitas)

---

## ⚠️ Kondisi Khusus

### Siswa Tidak Bisa Dinaikkan

**Penyebab 1: Kelas Baru Tidak Ditemukan**
- **Gejala**: Kelas dengan tingkat dan jurusan yang sesuai tidak ada di tahun ajaran baru
- **Solusi**: 
  1. Buka menu "Kelola Kelas" untuk tahun ajaran baru
  2. Buat kelas yang diperlukan (misal: XI-MIPA 6 jika ada siswa X-MIPA 6)
  3. Jalankan ulang proses kenaikan kelas

**Penyebab 2: Siswa Sudah Ada di Tahun Ajaran Baru**
- **Gejala**: Siswa sudah terdaftar di tahun ajaran baru (mungkin ditambahkan manual)
- **Solusi**: Siswa akan dilewati (skip), tidak terjadi error

### Siswa Kelas XII (Lulus)

- Status di tabel `siswa_kelas` akan diubah menjadi **"Tidak Aktif"**
- `tanggal_keluar` diisi dengan tanggal proses kenaikan kelas
- **Data tidak dihapus**, tetap tersimpan untuk histori dan arsip
- Siswa tidak akan muncul di tahun ajaran baru

---

## 🔒 Validasi dan Keamanan

### Validasi yang Dilakukan Sistem:

1. ✅ **Tahun ajaran lama** harus ada di database
2. ✅ **Tahun ajaran baru** harus ada di database
3. ✅ **Tahun ajaran baru** harus **semester Ganjil** (karena kelas dibuat di Ganjil)
4. ✅ **Tahun baru** harus **lebih tinggi** dari tahun lama (misal: 2026 > 2025)
5. ✅ Hanya siswa dengan status **"Aktif"** yang akan dinaikkan
6. ✅ **Tidak ada duplikasi** - siswa yang sudah ada di tahun baru akan dilewati

### Rollback (Jika Terjadi Error)

- Sistem menggunakan **Database Transaction**
- Jika terjadi error di tengah proses, **SEMUA perubahan akan di-rollback** (tidak ada data yang terubah)
- Anda dapat menjalankan ulang proses kenaikan kelas dengan aman

---

## 📝 Checklist Kenaikan Kelas

Gunakan checklist ini sebelum menjalankan kenaikan kelas:

### Sebelum Proses:
- [ ] Tahun ajaran baru sudah dibuat (2026/2027)
- [ ] Semester Ganjil tahun baru sudah diaktifkan
- [ ] 30 kelas standar sudah di-generate atau dibuat manual
- [ ] Jadwal pelajaran sudah dicopy/dibuat untuk tahun baru
- [ ] Semester tahun lama sudah dinonaktifkan (Ganjil & Genap)
- [ ] Backup database (untuk berjaga-jaga)

### Setelah Proses:
- [ ] Cek statistik kenaikan kelas (X→XI, XI→XII, XII→Lulus)
- [ ] Verifikasi beberapa siswa sudah masuk ke kelas baru
- [ ] Cek siswa kelas XII sudah berstatus "Tidak Aktif"
- [ ] Test login siswa - pastikan mereka melihat kelas baru
- [ ] Test login guru - pastikan jadwal dan siswa terlihat
- [ ] Arsipkan tahun ajaran lama (opsional)

---

## 🐛 Troubleshooting

### Error: "Tahun ajaran baru harus semester Ganjil"

**Penyebab**: Anda memilih tahun ajaran dengan semester Genap sebagai tujuan

**Solusi**: 
- Kenaikan kelas hanya bisa dilakukan ke **semester Ganjil** tahun ajaran baru
- Pilih tahun ajaran Ganjil yang benar
- Contoh: Dari 2025/2026 Ganjil → Harus ke 2026/2027 **Ganjil** (bukan Genap)

### Warning: "X siswa tidak dinaikkan (kelas baru tidak ditemukan)"

**Penyebab**: Ada kelas di tahun lama yang tidak ada pasangannya di tahun baru

**Contoh**: 
- Tahun lama ada X-MIPA 6
- Tahun baru tidak ada XI-MIPA 6

**Solusi**:
1. Catat kelas mana yang hilang (lihat log)
2. Buka menu "Kelola Kelas" untuk tahun baru
3. Buat kelas yang diperlukan secara manual
4. Jalankan ulang proses kenaikan kelas (siswa yang sudah dinaikkan akan di-skip)

### Siswa Tidak Muncul di Kelas Baru

**Penyebab 1**: Proses kenaikan kelas belum dijalankan
- **Solusi**: Jalankan proses kenaikan kelas via UI atau command

**Penyebab 2**: Siswa di-skip karena sudah ada di tahun baru
- **Solusi**: Cek di Data Master > Siswa, pastikan siswa benar-benar sudah pindah

**Penyebab 3**: Kelas baru tidak ada
- **Solusi**: Buat kelas yang diperlukan, lalu jalankan ulang

---

## 🔄 Workflow Lengkap: Pergantian Tahun Ajaran

### Timeline Ideal:

#### **Juni (Akhir Tahun Ajaran 2025/2026)**
1. Input semua nilai raport semester Genap
2. Nonaktifkan semester Genap 2025/2026
3. Arsipkan tahun ajaran 2025/2026 (opsional)

#### **Juli (Awal Tahun Ajaran 2026/2027)**
1. **Buat tahun ajaran baru** 2026/2027
   - Sistem otomatis membuat Ganjil dan Genap
   - Sistem otomatis generate 30 kelas untuk Ganjil

2. **Aktifkan semester Ganjil** 2026/2027

3. **Copy jadwal pelajaran** dari tahun lama atau buat baru

4. **Jalankan kenaikan kelas otomatis** 🎯
   - Via UI: Klik tombol "Naikkan Kelas"
   - Atau via command: `php artisan siswa:naik-kelas 1 3`

5. **Verifikasi hasil kenaikan**
   - Cek statistik kenaikan
   - Test login beberapa siswa
   - Test login beberapa guru

6. **Tambahkan siswa baru** (untuk kelas X)
   - Via menu Data Master > Siswa
   - Assign ke kelas X tahun ajaran 2026/2027

7. **Mulai semester baru** 🎉

---

## 📚 File-file Terkait

### Backend (Laravel)
- **Command**: `app/Console/Commands/NaikkanKelasCommand.php`
  - Artisan command untuk kenaikan kelas via CLI
  - Validasi, logika bisnis, progress bar
  
- **Controller**: `app/Http/Controllers/Admin/TahunAjaranController.php`
  - Method `naikkanKelas()` untuk handle request dari UI
  - Validasi dan eksekusi kenaikan kelas

- **Models**:
  - `app/Models/SiswaKelas.php` - Relasi siswa-kelas per tahun ajaran
  - `app/Models/Kelas.php` - Master kelas
  - `app/Models/TahunAjaran.php` - Master tahun ajaran
  - `app/Models/Siswa.php` - Master siswa

### Frontend (Blade)
- **View**: `resources/views/Admin/tahunAjaran.blade.php`
  - Tombol "Naikkan Kelas"
  - Modal konfirmasi kenaikan kelas
  - JavaScript untuk open/close modal

### Route
- **Route**: `routes/web.php`
  ```php
  Route::post('/tahun-ajaran/naik-kelas', [TahunAjaranController::class, 'naikkanKelas'])
      ->name('tahun-ajaran.naik-kelas');
  ```

---

## 💡 Best Practices

### DO ✅
- ✅ Selalu backup database sebelum kenaikan kelas massal
- ✅ Verifikasi kelas sudah dibuat di tahun baru sebelum proses
- ✅ Jalankan di waktu low-traffic (misal: dini hari atau weekend)
- ✅ Cek log dan statistik setelah proses selesai
- ✅ Test dengan beberapa siswa terlebih dahulu jika memungkinkan

### DON'T ❌
- ❌ Jangan jalankan kenaikan kelas saat semester masih aktif
- ❌ Jangan hapus tahun ajaran lama sebelum verifikasi kenaikan berhasil
- ❌ Jangan lupa aktifkan semester Ganjil tahun baru
- ❌ Jangan jalankan berkali-kali (siswa akan di-skip tapi tidak efisien)

---

## 📞 Kontak

Jika menemukan bug atau butuh bantuan:
1. Cek dokumentasi ini terlebih dahulu
2. Cek file `PANDUAN_TAHUN_AJARAN.md` untuk context tambahan
3. Hubungi developer/administrator sistem

---

**Terakhir diupdate**: 8 Desember 2024

**Versi Sistem**: v2.0 (dengan fitur kenaikan kelas otomatis)

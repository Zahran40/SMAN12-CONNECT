# PANDUAN SISTEM TAHUN AJARAN

## Konsep Dasar

### Struktur Tahun Ajaran
- Setiap tahun ajaran terdiri dari **2 semester**: Ganjil dan Genap
- **Semester Ganjil**: Juli - Desember
- **Semester Genap**: Januari - Juni

### Aturan Data
1. **KELAS** dibuat HANYA untuk semester **Ganjil**
2. **JADWAL PELAJARAN** dibuat HANYA untuk semester **Ganjil**
3. **SISWA_KELAS** dan **NILAI** bisa berbeda per semester
4. Semester **Genap** menggunakan kelas dan jadwal yang sama dengan semester **Ganjil**

---

## Cara Membuat Tahun Ajaran Baru

### Via Admin Panel

1. **Login sebagai Admin**
2. **Buka Menu**: Manajemen Tahun Ajaran
3. **Klik**: Tambah Tahun Ajaran
4. **Isi Form**:
   - Tahun Mulai: 2025
   - Tahun Selesai: 2026
   - Sistem akan otomatis membuat 2 semester (Ganjil & Genap)
5. **Klik Simpan**

### Yang Terjadi Otomatis

**Observer TahunAjaranObserver akan:**
- Membuat 30 kelas standar untuk semester **Ganjil**:
  - X-MIPA 1-5, X-IPS 1-5
  - XI-MIPA 1-5, XI-IPS 1-5
  - XII-MIPA 1-5, XII-IPS 1-5

### Yang Harus Dilakukan Manual

1. **Copy Jadwal Pelajaran**
   - Dari tahun ajaran lama ke baru
   - Atau buat jadwal baru via menu Akademik

2. **Pindahkan Siswa**
   - Via menu Data Master > Siswa
   - Pilih kelas baru untuk setiap siswa
   - Status: Aktif

3. **Aktifkan Semester**
   - Aktifkan semester Ganjil untuk mulai tahun ajaran baru
   - Nanti aktifkan semester Genap di tengah tahun

---

## Proses Perpindahan Semester

### Dari Ganjil ke Genap (Pertengahan Tahun)

1. **Nonaktifkan** semester Ganjil
2. **Aktifkan** semester Genap
3. **Data yang tetap sama**:
   - Kelas (masih menggunakan yang dibuat di Ganjil)
   - Jadwal (masih menggunakan yang dibuat di Ganjil)
   - Guru pengampu
4. **Data yang bisa berbeda**:
   - Siswa bisa pindah kelas
   - Nilai raport per semester

---

## Arsip Tahun Ajaran

### Kapan Mengarsipkan?
- Setelah tahun ajaran selesai
- Kedua semester sudah tidak aktif
- Semua nilai sudah diinput dan rapor sudah dibagikan

### Cara Mengarsipkan:
1. **Pastikan** kedua semester (Ganjil & Genap) **Tidak Aktif**
2. **Klik** tombol "Arsipkan" di halaman Tahun Ajaran
3. Data **tidak akan dihapus**, hanya **disembunyikan** dari dropdown
4. **Bisa dipulihkan** kapan saja via menu "Tahun Ajaran Arsip"

### Yang Terjadi Setelah Diarsipkan:
- ✅ Data tetap tersimpan di database
- ✅ Bisa dilihat di menu "Tahun Ajaran Arsip"
- ❌ Tidak muncul di dropdown filter
- ❌ Tidak bisa dipilih untuk input data baru

---

## Troubleshooting

### Data Tidak Muncul di Halaman Guru

**Gejala**: Guru tidak melihat mata pelajaran/kelas di menu Materi/Raport/Presensi

**Penyebab**: 
- Semester aktif adalah Genap, tapi jadwal ada di Ganjil
- Sistem otomatis mencari dari Ganjil

**Solusi**: 
- Pastikan semester Ganjil tahun yang sama tidak di-archive
- Refresh halaman

### Siswa Tidak Muncul di Input Nilai

**Gejala**: Halaman input nilai kosong, tidak ada siswa

**Penyebab**:
- Siswa belum di-assign ke kelas di tahun ajaran aktif
- Status siswa "Tidak Aktif"

**Solusi**:
1. Cek di menu Data Master > Siswa
2. Pastikan siswa sudah masuk ke kelas
3. Pastikan status "Aktif"
4. Pastikan tahun_ajaran_id sesuai

### Kelas Tidak Muncul di Pendataan Kelas

**Gejala**: Halaman pendataan kelas menampilkan 0 kelas

**Penyebab**:
- Kelas belum dibuat untuk semester Ganjil
- Semester Ganjil di-archive

**Solusi**:
1. Buka halaman Tahun Ajaran
2. Klik "Generate 30 Kelas Otomatis" untuk semester Ganjil
3. Atau buat manual via "Kelola Kelas"

---

## Checklist Tahun Ajaran Baru

- [ ] Buat tahun ajaran baru (2025/2026)
- [ ] Cek 30 kelas sudah dibuat otomatis
- [ ] Copy jadwal pelajaran dari tahun lama
- [ ] Pindahkan siswa ke kelas baru
- [ ] Aktifkan semester Ganjil
- [ ] Nonaktifkan tahun ajaran lama
- [ ] Test login guru: cek Materi, Raport, Presensi
- [ ] Test login siswa: cek kelas dan jadwal
- [ ] Arsipkan tahun ajaran lama (opsional)

---

## File-file Penting yang Sudah Diperbaiki

### Controllers
- `app/Http/Controllers/Admin/TahunAjaranController.php` - Hitung kelas dari Ganjil
- `app/Http/Controllers/Admin/KelasController.php` - Share kelas Ganjil ke Genap
- `app/Http/Controllers/Guru/RaportController.php` - Query jadwal dan siswa dari Ganjil
- `app/Http/Controllers/Guru/MateriController.php` - Query jadwal dari Ganjil
- `app/Http/Controllers/Guru/PresensiController.php` - Query jadwal dari Ganjil

### Models
- `app/Models/TahunAjaran.php` - Scope active(), getActive()
- `app/Observers/TahunAjaranObserver.php` - Auto-create 30 kelas

### Views
- `resources/views/Admin/tahunAjaran.blade.php` - Statistik dan generate kelas
- `resources/views/Admin/pendataanKelas.blade.php` - Daftar kelas dengan sharing
- `resources/views/Guru/raport.blade.php` - Hitung siswa dari jadwal

### Database
- Migration: `2025_11_25_002256_add_is_archived_to_tahun_ajaran_table.php`
- Kolom: `is_archived` (boolean, default false)

---

## Catatan Penting

⚠️ **JANGAN** hapus semester Ganjil jika masih digunakan semester Genap di tahun yang sama
⚠️ **SELALU** buat kelas dan jadwal di semester Ganjil
⚠️ **PASTIKAN** siswa sudah dipindahkan ke tahun ajaran baru sebelum mengarsipkan yang lama

✅ Sistem sudah dioptimalkan untuk handle Ganjil/Genap sharing
✅ Semua halaman guru sudah otomatis query dari semester yang benar
✅ Archive system sudah terintegrasi di semua controller

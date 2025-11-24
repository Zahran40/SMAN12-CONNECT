# APLIKASI SISTEM INFORMASI AKADEMIK SMA NEGERI 12 MEDAN

## Kelompok 3 MSBD KOM C '24
- Abbil Rizki Abdillah - 241402033 (UI/UX, Backend)
- Daniele Christian Hasiholland Siahaan - 241402060 (Backend,QA Testing)	
- Reagan Brian Siahaan -241402099 (Frontend)
- Andre Al Farizi Sebayang - 241402105 (Project Manager, Frontend, Backend)	
- Yeremia Nicolas Purba - 241402140 (Frontend)

## Deskripsi Aplikasi
Aplikasi ini adalah Sistem Informasi Akademik (SIAKAD) berbasis web yang dirancang untuk mengotomatisasi dan mengintegrasikan seluruh proses pengelolaan data akademik dan administrasi di SMA Negeri 12 Medan. SMA Negeri 12 Medan adalah salah satu Sekolah Menengah Atas Negeri yang berlokasi di Kota Medan, Provinsi Sumatera Utara, Indonesia tepatnya di Jalan Cempaka No. 75, Kelurahan Helvetia Tengah, Kecamatan Medan Helvetia.

## Fitur-Fitur Sistem Berdasarkan Peran Pengguna
### 1. Admin / operator tata usaha
- Fitur-fitur Admin difokuskan pada pengelolaan menyeluruh (CRUD: Create, Read, Update, Delete) data sistem meliputi :
- Melihat seluruh data dalam database (guru, siswa, kelas, jadwal, nilai, absensi, dan pembayaran SPP).
- Mengelola (menambah, mengubah, menghapus) akun guru dan siswa.
- Mengelola data kelas (nama kelas, tingkat/tingkatan, jurusan, dan wali kelas).
- Mengelola data mata pelajaran ($menambah$, $mengubah$, dan $menghapus$).
- Mengatur dan memperbarui tahun ajaran yang sedang aktif.
- Membuat dan mengatur jadwal pelajaran (berdasarkan guru, kelas, dan mata pelajaran).
- Melihat dan mencetak laporan nilai, absensi, dan pembayaran SPP seluruh siswa.Membuat pengumuman yang dapat dilihat oleh seluruh pengguna sistem.
- Memantau status pembayaran SPP siswa dan memperbarui status pembayaran.

### 2. Guru
Fitur-fitur Guru difokuskan pada kegiatan belajar mengajar dan administrasi kelas meliputi :
- Dapat login ke sistem dan melihat tampilan dashboard guru.
- Melihat jadwal mengajar berdasarkan tahun ajaran dan kelas yang diampu.
- Menginput dan mengeditnilai siswa (tugas, UTS, UAS, dan nilai akhir).
- Mencatat absensi siswa pada kelas dan mata pelajaran yang diajarnya.
- Melihat laporan nilai, absensi, dan daftar siswa pada kelas yang diajar.
- Melihat pengumuman akademik yang disampaikan melalui sistem.

### 3. Siswa
Fitur-fitur Siswa difokuskan pada akses transparan terhadap informasi akademik dan administrasi pribadi meliputi :
- Dapat login ke sistem dan melihat tampilan dashboard siswa.
- Melihat jadwal pelajaran berdasarkan kelas dan tahun ajaran aktif.
- Melihat nilai (tugas, UTS, UAS, dan nilai akhir) untuk setiap mata pelajaran.
- Melihat rekap absensi pribadi berdasarkan tanggal dan Mata Pelajaran.
- Melihat status pembayaran SPP (lunas/belum lunas).
Melihat pengumuman terbaru yang dibuat oleh admin atau guru.
Mengubah data profil pribadi seperti alamat dan tanggal lahir.

## Tech Stack
- Laravel v12.14.1
dipakai untuk framework utama aplikasi
- Composer v2.4.1 
untuk package laravel
- PHP v8.3.22 
sebagai bahasa pemrograman framework laravel khususnay backend
- MySQL v10.11 
sebagai software manajemen sistem database
- Tailwind CSS (versi belum fix)
dipakai untuk framework CSS

## Instalasi & Menjalankan (Laragon + Tailwind/Vite)

Langkah singkat untuk menjalankan proyek ini di Windows menggunakan Laragon dan Tailwind CSS (Vite):

### 1) Clone repository ke folder Laragon
```powershell
cd C:\laragon\www
git clone https://github.com/Zahran40/SMAN12-CONNECT.git
cd SMAN12-CONNECT
```
Setelah di-clone, proyek berada di `C:\laragon\www\SMAN12-CONNECT` sehingga Laragon dapat membuat virtual host otomatis (`.test`).

### 2) Siapkan aplikasi Laravel
```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
```

### 3) Atur database di `.env`
Contoh pengaturan default Laragon:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=Nama_Database
DB_USERNAME=root
DB_PASSWORD=
```

Buat database `Nama_Database` (via HeidiSQL/PhpMyAdmin), lalu jalankan migrasi:

```powershell
php artisan migrate
```

### 4) Jalankan Tailwind (Vite) dan akses lewat Laragon
```powershell
npm install
npm run dev
```

Di Laragon, klik Start All dan aktifkan Auto virtual hosts. Akses aplikasi melalui:

- http://SMAN12-CONNECT.test

Biarkan `npm run dev` tetap berjalan selama pengembangan untuk kompilasi Tailwind CSS dan auto-refresh.

---

## Konfigurasi Tambahan

### 🔑 Setup Email (Forgot Password)

Sistem lupa password menggunakan Gmail SMTP. Ikuti langkah berikut:

#### 1. Login ke Akun Google
Buka [myaccount.google.com](https://myaccount.google.com) dengan email yang akan digunakan.

#### 2. Aktifkan Verifikasi 2 Langkah (WAJIB!)
1. Klik menu **Keamanan** (Security)
2. Cari **Verifikasi 2 Langkah** (2-Step Verification)
3. Aktifkan jika belum aktif
4. Tautkan nomor HP Anda

#### 3. Buat App Password
1. Masih di menu **Keamanan**, klik **Verifikasi 2 Langkah**
2. Scroll ke **paling bawah** halaman
3. Klik **"Sandi Aplikasi"** (App passwords)
4. Pilih aplikasi: **Email** (Mail)
5. Pilih perangkat: **Lainnya** (Other), ketik: `SMAN12-CONNECT`
6. Klik **Buat** (Generate)
7. **Copy kode 16 karakter** (tanpa spasi)

#### 4. Update File .env
```env
MAIL_MAILER=smtp
MAIL_SCHEME=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_USERNAME="emailanda@gmail.com"
MAIL_PASSWORD="abcdefghijklmnop"  # 16 digit App Password
MAIL_FROM_ADDRESS="emailanda@gmail.com"
MAIL_FROM_NAME="SMAN 12 Connect"
```

#### 5. Clear Cache Laravel
```powershell
php artisan config:clear
php artisan cache:clear
```

#### 6. Test Email
1. Akses halaman **Forgot Password** di aplikasi
2. Masukkan email yang terdaftar
3. Cek inbox email (atau folder Spam)

---

### 💳 Setup Midtrans Payment Gateway

Konfigurasi Midtrans untuk pembayaran SPP sudah tersedia dalam mode **Sandbox/Testing**.

#### Update File .env
```env
MIDTRANS_MERCHANT_ID=G699511196
MIDTRANS_CLIENT_KEY=SB-Mid-client-JV1hxBKvK54RC4PV
MIDTRANS_SERVER_KEY=SB-Mid-server-vtes-EfWHAlMBGLjEXF06HtG
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_SANITIZED=true
MIDTRANS_3DS=true
```

#### Cara Testing
1. Akses fitur pembayaran SPP
2. Gunakan test card dari [Midtrans Sandbox](https://docs.midtrans.com/docs/testing-payment-on-sandbox)
3. Contoh test card: `4811 1111 1111 1114` (Visa, sukses)

**Catatan:**
- Mode: **Sandbox** (IS_PRODUCTION=false)
- 3D Secure: Aktif untuk keamanan
- Untuk production, ganti credentials di [Midtrans Dashboard](https://dashboard.midtrans.com/)

---

### 🗺️ Setup Google Maps API (Untuk Absensi GPS)

Sistem absensi menggunakan GPS tracking. Ada 2 pilihan:

#### Pilihan 1: Nominatim OSM (GRATIS, Sudah Aktif) ✅ **RECOMMENDED**
Sistem sudah menggunakan Nominatim OpenStreetMap secara default.

**Keuntungan:**
- ✅ 100% Gratis, tidak perlu API key
- ✅ Tidak perlu billing account
- ✅ Akurasi bagus untuk Indonesia
- ✅ Sudah langsung bisa dipakai

**Kekurangan:**
- Rate limit: 1 request/second
- Tidak seprestisius Google Maps

#### Pilihan 2: Google Maps API (Premium, Opsional)

Jika ingin menggunakan Google Maps (lebih akurat):

**1. Buka Google Cloud Console**
- https://console.cloud.google.com/
- Login dengan akun Google Anda

**2. Pilih/Buat Project**
- Klik dropdown project di bagian atas
- Buat project baru: `SMAN12-CONNECT`

**3. Enable Geocoding API**
1. Menu ☰ → **APIs & Services** → **Enabled APIs & services**
2. Klik **+ ENABLE APIS AND SERVICES**
3. Cari: **Geocoding API**
4. Klik dan **ENABLE**

**4. Setup Billing Account (WAJIB!)**
1. Menu ☰ → **Billing**
2. Klik **LINK A BILLING ACCOUNT**
3. Isi informasi billing (kartu kredit untuk verifikasi)
4. **PENTING:** Google tidak akan charge otomatis
5. Free tier: $200 credit/bulan atau 28,500 requests

**5. Buat API Key**
1. **APIs & Services** → **Credentials**
2. Klik **+ CREATE CREDENTIALS** → **API key**
3. Copy API key yang dihasilkan

**6. Restrict API Key (Keamanan)**
1. Klik API key yang baru dibuat
2. **Application restrictions**: Pilih **HTTP referrers**
   ```
   http://localhost/*
   http://SMAN12-CONNECT.test/*
   ```
3. **API restrictions**: Restrict key, centang **Geocoding API**
4. Klik **SAVE**

**7. Update File .env**
```env
GOOGLE_MAPS_API_KEY=your_api_key_here
```

**8. Test API Key**
Buka URL ini di browser:
```
https://maps.googleapis.com/maps/api/geocode/json?latlng=3.5952,-98.6722&key=YOUR_API_KEY
```

**Biaya:**
- Free tier: $200/bulan (cukup untuk ~28,500 requests)
- Untuk 1000 siswa absen/bulan = ~$5 (masih dalam free tier)

---

## ⚠️ Troubleshooting

### Email tidak terkirim
**Solusi:**
1. Pastikan App Password di-copy tanpa spasi
2. Pastikan Verifikasi 2 Langkah sudah aktif
3. Cek log: `storage/logs/laravel.log`
4. Test dengan command:
   ```powershell
   php artisan tinker
   Mail::raw('Test', function($msg) { $msg->to('test@example.com')->subject('Test'); });
   ```

### Midtrans Error "Unauthorized"
**Solusi:**
1. Cek ulang credentials di [Midtrans Dashboard](https://dashboard.midtrans.com/)
2. Jalankan: `php artisan config:clear`
3. Restart server

### GPS Tracking tidak akurat
**Solusi:**
1. Pastikan browser mengizinkan akses lokasi
2. Gunakan HTTPS (atau localhost) untuk geolocation API
3. Jika menggunakan Google Maps, pastikan API key sudah aktif
4. Default Nominatim OSM sudah cukup akurat untuk Indonesia

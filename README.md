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

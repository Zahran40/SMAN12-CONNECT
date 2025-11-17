# Setup HTTPS untuk Laragon - SMAN12 CONNECT

## ⚠️ PENTING: GPS Memerlukan HTTPS
Browser modern (Chrome, Firefox, Safari) **hanya mengizinkan akses GPS/Lokasi pada koneksi HTTPS**.
Tanpa HTTPS, fitur presensi dengan GPS tidak akan berfungsi.

---

## 🔧 Langkah-langkah Setup HTTPS di Laragon

### **Metode 1: Auto SSL (Paling Mudah - RECOMMENDED)**

1. **Buka Laragon**
   - Klik kanan icon Laragon di system tray

2. **Enable SSL Auto untuk Virtual Host**
   - Klik: **Menu** → **SSL** → **Auto**
   - Tunggu beberapa detik hingga proses selesai

3. **Restart Laragon**
   - Klik kanan icon Laragon → **Stop All**
   - Klik kanan icon Laragon → **Start All**

4. **Akses Website dengan HTTPS**
   ```
   https://sman12-connect.test
   ```
   atau
   ```
   https://localhost/SMAN12-CONNECT/public
   ```

5. **Bypass SSL Warning (Jika Muncul)**
   - Klik **Advanced** atau **Lanjutkan**
   - Klik **Proceed to sman12-connect.test (unsafe)**
   - Certificate self-signed dari Laragon aman untuk development local

---

### **Metode 2: Manual SSL Certificate**

Jika metode auto tidak bekerja:

1. **Buka Menu Laragon**
   - Klik kanan icon Laragon → **Menu** → **Apache** → **SSL** → **Certificate & Key Generator**

2. **Generate Certificate**
   - Hostname: `sman12-connect.test`
   - Klik **Generate**

3. **Install Certificate di Windows**
   - Buka file `C:\laragon\etc\ssl\laragon.crt`
   - Double click → **Install Certificate**
   - Pilih **Local Machine**
   - Pilih **Place all certificates in the following store**
   - Browse → **Trusted Root Certification Authorities**
   - Klik **OK** → **Finish**

4. **Restart Browser & Laragon**
   - Tutup semua browser
   - Restart Laragon
   - Buka browser baru dan akses `https://sman12-connect.test`

---

### **Metode 3: Setup Virtual Host Custom**

1. **Buat Virtual Host**
   - Klik kanan icon Laragon → **Apache** → **sites-enabled**
   - Klik **auto.sman12-connect.test.conf** (buka dengan notepad)

2. **Edit Konfigurasi** (Tambahkan SSL):
   ```apache
   <VirtualHost *:443>
       ServerName sman12-connect.test
       ServerAlias *.sman12-connect.test
       DocumentRoot "C:/laragon/www/SMAN12-CONNECT/public"
       
       SSLEngine on
       SSLCertificateFile "C:/laragon/etc/ssl/laragon.crt"
       SSLCertificateKeyFile "C:/laragon/etc/ssl/laragon.key"
       
       <Directory "C:/laragon/www/SMAN12-CONNECT/public">
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```

3. **Restart Apache**
   - Klik kanan Laragon → **Apache** → **Restart**

---

## 📱 Konfigurasi Laravel untuk HTTPS

### **1. Update .env File**

Buka file `.env` dan pastikan:

```env
APP_URL=https://sman12-connect.test

# Atau jika menggunakan localhost:
APP_URL=https://localhost/SMAN12-CONNECT/public

# Force HTTPS (tambahkan baris ini)
FORCE_HTTPS=true
```

### **2. Update AppServiceProvider.php**

File sudah otomatis dibuat di `app/Providers/AppServiceProvider.php`:

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Force HTTPS in production or when FORCE_HTTPS is true
        if ($this->app->environment('production') || env('FORCE_HTTPS', false)) {
            URL::forceScheme('https');
        }
    }
}
```

### **3. Clear Cache Laravel**

Jalankan di terminal:
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

---

## 🧪 Testing GPS di HTTPS

1. **Akses Halaman Presensi Siswa**
   ```
   https://sman12-connect.test/siswa/nilai
   ```

2. **Klik Semester → Detail Pertemuan**

3. **Browser akan meminta izin lokasi:**
   - Klik **Allow** / **Izinkan**
   - GPS akan mulai mendeteksi lokasi

4. **Verifikasi:**
   - Lokasi harus terdeteksi otomatis
   - Alamat muncul dari Google Maps API
   - Tombol "Absen Sekarang" aktif

---

## 🌐 Testing di Mobile (HP)

### **Opsi 1: Ngrok (Recommended untuk testing cepat)**

1. **Download Ngrok**
   - https://ngrok.com/download

2. **Install & Jalankan**
   ```bash
   ngrok http 80
   ```

3. **Copy HTTPS URL**
   ```
   Contoh: https://abc123.ngrok.io
   ```

4. **Akses dari HP**
   - Buka browser di HP
   - Masuk ke URL ngrok
   - GPS akan langsung bisa diakses (karena HTTPS)

### **Opsi 2: Same Network (WiFi)**

1. **Cek IP Laptop**
   ```bash
   ipconfig
   ```
   Contoh: `192.168.1.100`

2. **Setup Virtual Host di Laragon**
   - Hostname: `192.168.1.100`
   - Enable SSL untuk IP ini

3. **Akses dari HP**
   ```
   https://192.168.1.100/SMAN12-CONNECT/public
   ```

---

## ❌ Troubleshooting

### **Problem: "Your connection is not private" / ERR_CERT_AUTHORITY_INVALID**

**Solusi:**
- Klik **Advanced** → **Proceed anyway**
- Atau install certificate Laragon ke Trusted Root (lihat Metode 2)

### **Problem: GPS tidak terdeteksi**

**Checklist:**
1. ✅ Apakah menggunakan HTTPS? (bukan HTTP)
2. ✅ Apakah browser meminta izin lokasi?
3. ✅ Apakah GPS/Lokasi sudah ON di device?
4. ✅ Apakah ada error di Console browser? (F12)

### **Problem: "Geolocation permission denied"**

**Solusi:**
1. Buka **Settings** browser
2. Cari **Site Settings** → **Location**
3. Hapus block untuk domain Anda
4. Refresh halaman dan klik **Allow**

### **Problem: Google Maps API tidak jalan**

**Checklist:**
1. API Key valid: `AIzaSyBy-ugy58EBTMwG2TqtBVlPhR8oF3LeMhA`
2. API sudah enable: Geocoding API & Maps JavaScript API
3. Billing sudah aktif di Google Cloud Console

---

## 📊 Status Implementasi

✅ Database: Kolom `latitude`, `longitude`, `alamat_lengkap` sudah ditambahkan
✅ Model: DetailAbsensi sudah include GPS fields
✅ Controller Siswa: Menyimpan data GPS saat absen
✅ Controller Guru: Menampilkan lokasi siswa
✅ View Siswa: Auto-detect GPS & reverse geocoding
✅ View Guru: Tampilkan lokasi + link Google Maps
✅ Google Maps API: Terintegrasi

---

## 🚀 Production Deployment

Untuk server production (hosting):

1. **Pastikan SSL Certificate Valid**
   - Gunakan Let's Encrypt (gratis)
   - Atau beli SSL certificate

2. **Update .env Production**
   ```env
   APP_ENV=production
   APP_URL=https://yourdomain.com
   FORCE_HTTPS=true
   ```

3. **Redirect HTTP ke HTTPS**
   Tambahkan di `.htaccess`:
   ```apache
   RewriteEngine On
   RewriteCond %{HTTPS} off
   RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
   ```

---

## 📞 Support

Jika ada masalah saat setup:
1. Cek log Apache: `C:\laragon\bin\apache\logs\error.log`
2. Cek log Laravel: `storage/logs/laravel.log`
3. Buka Console browser (F12) untuk error JavaScript

---

**Dibuat pada:** 17 November 2025
**Versi Laravel:** 12.35.1
**Versi PHP:** 8.2+

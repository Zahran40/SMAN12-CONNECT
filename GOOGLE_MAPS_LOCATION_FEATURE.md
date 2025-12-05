# Fitur Google Maps - Lokasi Absensi Siswa

## 📍 Overview
Fitur ini memungkinkan sistem untuk melacak lokasi siswa saat melakukan absensi menggunakan Google Maps API dan HTML5 Geolocation.

## 🔑 Konfigurasi

### 1. Google Maps API Key
API Key sudah dikonfigurasi di:
- **File**: `.env`
- **Key**: `GOOGLE_MAPS_API_KEY=AIzaSyBy-ugy58EBTMwG2TqtBVlPhR8oF3LeMhA`

### 2. Service Configuration
- **File**: `config/services.php`
```php
'google_maps' => [
    'api_key' => env('GOOGLE_MAPS_API_KEY'),
],
```

## 📊 Database Schema

### Tabel: `detail_absensi`
Kolom yang menyimpan lokasi:
- `latitude` (decimal) - Koordinat latitude
- `longitude` (decimal) - Koordinat longitude  
- `alamat_lengkap` (text) - Alamat lengkap hasil reverse geocoding

## 🎯 Fitur untuk Siswa

### Halaman: `resources/views/siswa/beranda.blade.php`

**Cara Kerja:**
1. Siswa klik tombol "Presensi Sekarang"
2. Modal muncul dengan peta Google Maps
3. Browser otomatis meminta izin akses lokasi
4. Setelah izin diberikan:
   - Peta menampilkan posisi siswa saat ini
   - Marker ditempatkan di lokasi siswa
   - Sistem melakukan reverse geocoding untuk mendapat alamat
   - Koordinat dan alamat ditampilkan
5. Siswa klik "Konfirmasi Presensi"
6. Data disimpan: `latitude`, `longitude`, `alamat_lengkap`

**Teknologi:**
- **Google Maps JavaScript API** - Menampilkan peta
- **HTML5 Geolocation API** - Mendapatkan lokasi perangkat
- **Google Geocoding API** - Konversi koordinat ke alamat

**Error Handling:**
- Jika browser tidak support geolocation: Tampil pesan error
- Jika user menolak izin lokasi: Tampil instruksi
- Jika timeout: Tampil pesan timeout
- Jika geocoding gagal: Tetap simpan koordinat

## 👨‍🏫 Fitur untuk Guru

### Halaman: `resources/views/Guru/detailpresensi.blade.php`

**Fitur Tampilan:**

1. **Tabel Detail Absensi**
   - Kolom "Lokasi" menampilkan:
     - Alamat lengkap
     - Koordinat (latitude, longitude)
     - Link "Lihat Maps" → Membuka Google Maps di tab baru

2. **Peta Lokasi Kehadiran** (Baru!)
   - Hanya muncul jika ada siswa yang sudah absen dengan lokasi
   - Menampilkan semua lokasi siswa dalam 1 peta
   - **Marker berwarna berdasarkan status:**
     - 🟢 Hijau - Hadir
     - 🟡 Kuning - Sakit
     - 🔵 Biru - Izin
     - 🔴 Merah - Alfa
   - Klik marker → Tampil info window:
     - Nama siswa
     - NIS
     - Status kehadiran (dengan badge warna)
     - Waktu absensi
     - Alamat

**Auto-zoom:**
- Peta otomatis menyesuaikan zoom agar semua marker terlihat
- Jika hanya 1 siswa: Zoom level 15
- Jika banyak siswa: Zoom menyesuaikan bounds

## 🔒 Security & Privacy

**Validasi Controller:**
```php
$request->validate([
    'latitude' => 'required|numeric',
    'longitude' => 'required|numeric',
    'alamat_lengkap' => 'nullable|string',
]);
```

**Privacy:**
- Lokasi hanya diambil saat siswa klik tombol presensi
- Browser meminta izin eksplisit dari user
- Data lokasi hanya disimpan saat absensi berhasil
- Guru hanya bisa lihat lokasi siswa di kelasnya sendiri

## 📱 Responsive Design

- Modal absensi responsive (max-w-lg)
- Peta menyesuaikan ukuran layar
- Mobile-friendly dengan touch support

## 🧪 Testing

**Test Scenario:**
1. ✅ Siswa berhasil absen dengan lokasi
2. ✅ Browser tidak support geolocation
3. ✅ User menolak izin lokasi
4. ✅ Timeout saat mendapat lokasi
5. ✅ Geocoding gagal (tetap simpan koordinat)
6. ✅ Guru melihat peta dengan banyak siswa
7. ✅ Link Google Maps eksternal berfungsi

## 🔧 Maintenance

**Jika API Key bermasalah:**
1. Check `.env` → GOOGLE_MAPS_API_KEY
2. Pastikan API aktif di Google Cloud Console
3. Enable APIs:
   - Maps JavaScript API
   - Geocoding API

**Jika peta tidak muncul:**
1. Check console browser untuk error API key
2. Pastikan `@stack('scripts')` ada di layout
3. Check network tab untuk request API

## 📝 Files Modified

1. `.env` - API key configuration
2. `config/services.php` - Service config
3. `resources/views/siswa/beranda.blade.php` - Student interface
4. `resources/views/Guru/detailpresensi.blade.php` - Teacher interface
5. `resources/views/layouts/siswa/app.blade.php` - Added @stack('styles') & @stack('scripts')
6. `resources/views/layouts/guru/app.blade.php` - Added @stack('styles')
7. `app/Http/Controllers/Siswa/PresensiController.php` - Already has validation

## 🎨 UI/UX Features

**Siswa:**
- Loading spinner saat deteksi lokasi
- Success message hijau saat lokasi terdeteksi
- Error message merah jika gagal
- Peta interaktif dengan marker
- Info lokasi dengan alamat readable
- Tombol disabled sampai lokasi terdeteksi

**Guru:**
- Card count: Total siswa dengan lokasi
- Peta full width dengan rounded corners
- Marker dengan custom color per status
- Info window styled dengan Tailwind
- Auto-fit bounds untuk semua marker
- Link eksternal ke Google Maps untuk detail

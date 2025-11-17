# 🚀 QUICK SETUP HTTPS - Laragon

## ⚡ Cara Tercepat (1 Menit)

### **Langkah 1: Enable SSL di Laragon**
1. Buka **Laragon**
2. Klik kanan icon Laragon di taskbar
3. Pilih: **Menu** → **SSL** → **Auto**
4. Tunggu 5-10 detik (akan muncul notifikasi selesai)

### **Langkah 2: Restart Laragon**
1. Klik kanan icon Laragon → **Stop All**
2. Tunggu sampai semua service stop
3. Klik kanan icon Laragon → **Start All**

### **Langkah 3: Update Laravel .env**
1. Buka file `.env` di folder project
2. Ubah baris `APP_URL`:
   ```env
   APP_URL=https://sman12-connect.test
   ```
3. Tambahkan baris baru:
   ```env
   FORCE_HTTPS=true
   ```
4. Save file

### **Langkah 4: Clear Cache Laravel**
Buka PowerShell/CMD di folder project:
```bash
php artisan config:clear
php artisan cache:clear
```

### **Langkah 5: Akses Website**
Buka browser dan akses:
```
https://sman12-connect.test
```

Jika muncul warning SSL:
- Klik **Advanced**
- Klik **Proceed to sman12-connect.test (unsafe)**

---

## ✅ Testing GPS

1. Login sebagai **Siswa**
2. Klik menu **Presensi Kehadiran**
3. Pilih mata pelajaran
4. Klik detail pertemuan
5. Browser akan minta izin lokasi → Klik **Allow**
6. Tunggu lokasi terdeteksi
7. Klik **Absen Sekarang**

---

## 🔍 Verifikasi Guru

1. Login sebagai **Guru**
2. Buka menu **Presensi**
3. Lihat detail presensi
4. Kolom **Lokasi** akan menampilkan:
   - Alamat lengkap siswa saat absen
   - Koordinat GPS (latitude, longitude)
   - Link ke Google Maps

---

## ❓ Troubleshooting

### GPS tidak terdeteksi?
✅ Pastikan menggunakan **HTTPS** (bukan HTTP)
✅ Pastikan GPS/Location service ON di device
✅ Refresh halaman dan izinkan lokasi lagi

### Certificate Error?
✅ Normal untuk development local
✅ Klik "Proceed anyway" untuk melanjutkan
✅ Atau install certificate (lihat SETUP_HTTPS_LARAGON.md)

---

## 📱 Testing di HP

Gunakan **ngrok** untuk akses dari HP:

1. Download ngrok: https://ngrok.com/download
2. Extract dan jalankan:
   ```bash
   ngrok http 443
   ```
3. Copy URL HTTPS yang muncul (contoh: `https://abc123.ngrok-free.app`)
4. Akses dari HP dengan URL tersebut

---

**File lengkap:** Lihat `SETUP_HTTPS_LARAGON.md` untuk detail lebih lengkap

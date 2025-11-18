# 🗺️ CARA AKTIVASI GOOGLE MAPS API

API Key Anda: `AIzaSyBy-ugy58EBTMwG2TqtBVlPhR8oF3LeMhA`

---

## 📋 Langkah-langkah Aktivasi

### **1. Buka Google Cloud Console**
https://console.cloud.google.com/

### **2. Login dengan Akun Google**
- Login dengan akun yang membuat API key tersebut

### **3. Pilih/Buat Project**
- Klik dropdown project di bagian atas
- Pilih project yang sesuai dengan API key Anda
- Atau buat project baru jika belum ada

### **4. Enable Geocoding API**

#### A. Dari Menu API & Services
1. Klik menu ☰ (hamburger) di kiri atas
2. Pilih **APIs & Services** → **Enabled APIs & services**
3. Klik tombol **+ ENABLE APIS AND SERVICES**
4. Cari: **Geocoding API**
5. Klik **Geocoding API**
6. Klik tombol **ENABLE**
7. Tunggu beberapa detik

#### B. Enable JavaScript API (Opsional)
1. Ulangi langkah di atas
2. Cari: **Maps JavaScript API**
3. Klik dan **ENABLE**

### **5. Setup Billing Account** (WAJIB!)

Google Maps API memerlukan billing account meskipun ada free tier:

1. Klik menu ☰ → **Billing**
2. Klik **LINK A BILLING ACCOUNT**
3. Jika belum punya:
   - Klik **CREATE BILLING ACCOUNT**
   - Isi informasi:
     - Country: Indonesia
     - Nama
     - Alamat
     - Kartu kredit/debit (untuk verifikasi)
   - **PENTING**: Google tidak akan charge otomatis
   - Free tier: $200 credit/bulan atau 28,500 requests

4. Link billing account ke project Anda

### **6. Restrict API Key (Keamanan)**

1. Kembali ke **APIs & Services** → **Credentials**
2. Klik API key Anda
3. Di bagian **Application restrictions**:
   - Pilih **HTTP referrers (websites)**
   - Tambahkan:
     ```
     https://sman12-connect.test/*
     http://localhost/*
     https://your-production-domain.com/*
     ```

4. Di bagian **API restrictions**:
   - Pilih **Restrict key**
   - Centang:
     - ✅ Geocoding API
     - ✅ Maps JavaScript API (jika diaktifkan)

5. Klik **SAVE**

### **7. Test API Key**

Buka browser dan test URL ini:
```
https://maps.googleapis.com/maps/api/geocode/json?latlng=3.5952,-98.6722&key=AIzaSyBy-ugy58EBTMwG2TqtBVlPhR8oF3LeMhA
```

Jika berhasil, akan muncul response JSON dengan alamat.

---

## 💰 Biaya & Free Tier

### **Free Tier (Setiap Bulan):**
- ✅ $200 credit gratis
- ✅ Geocoding: 28,500 requests GRATIS
- ✅ Maps JavaScript API: Unlimited map loads

### **Setelah Free Tier:**
- Geocoding: $5 per 1000 requests
- Total 1000 siswa absen/bulan = ~$5 (masih dalam free tier)

### **Tips Hemat:**
1. Cache hasil geocoding di database ✅ (sudah diimplementasikan)
2. Gunakan Nominatim OSM untuk development ✅ (sudah diimplementasikan)
3. Set quota limit di console

---

## 🔧 Alternatif: Gunakan Nominatim (FREE)

Jika tidak ingin setup billing, gunakan **Nominatim OpenStreetMap** (sudah diimplementasikan):

**Keuntungan:**
- ✅ 100% Gratis
- ✅ Tidak perlu API key
- ✅ Tidak perlu billing
- ✅ Akurasi bagus untuk Indonesia
- ✅ Unlimited usage (fair use policy)

**Kekurangan:**
- ⚠️ Rate limit: 1 request/second
- ⚠️ Tidak seprestisius Google

**Kode sudah diupdate menggunakan Nominatim**, jadi GPS tracking sudah bisa jalan tanpa Google Maps API!

---

## 📊 Monitoring Usage

### **Cek Quota Usage:**
1. Buka Google Cloud Console
2. Menu ☰ → **APIs & Services** → **Dashboard**
3. Klik **Geocoding API**
4. Lihat tab **Metrics**
5. Monitor requests per hari/bulan

### **Set Quota Limit (Opsional):**
1. Buka **APIs & Services** → **Enabled APIs**
2. Klik **Geocoding API**
3. Tab **Quotas**
4. Klik **EDIT QUOTAS**
5. Set limit harian (contoh: 1000 requests/day)

---

## ❌ Troubleshooting

### **Error: "This API project is not authorized"**
✅ Enable Geocoding API di console
✅ Tunggu 1-2 menit propagasi

### **Error: "REQUEST_DENIED"**
✅ Check billing account sudah di-link
✅ Check API restrictions di credentials

### **Error: "OVER_QUERY_LIMIT"**
✅ Sudah melebihi quota
✅ Check billing atau tunggu reset bulan depan

### **Kartu Kredit di-charge?**
❌ Tidak, Google hanya verifikasi
✅ Bisa set alert jika mendekati limit
✅ Free tier $200/bulan cukup untuk ribuan requests

---

## 🎯 Rekomendasi

### **Untuk Development/Testing:**
✅ Gunakan **Nominatim OSM** (sudah diimplementasikan)
✅ GRATIS, tidak perlu setup apapun

### **Untuk Production:**
✅ Setup Google Maps API dengan billing
✅ Lebih akurat dan prestisi
✅ Support lebih baik
✅ Rate limit lebih tinggi

---

## 📝 Summary

**Pilihan 1: Google Maps (Premium)**
1. Enable Geocoding API
2. Setup Billing Account
3. Restrict API key
4. Update code (uncomment Google Maps)

**Pilihan 2: Nominatim OSM (FREE) ← RECOMMENDED**
- Sudah diimplementasikan
- Tidak perlu setup apapun
- Langsung bisa dipakai
- Cukup untuk kebutuhan sekolah

---

**File ini dibuat:** 17 November 2025
**Current Status:** Menggunakan Nominatim OSM (GRATIS, sudah jalan)

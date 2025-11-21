# 📋 Setup Konfigurasi SMAN12-CONNECT

## ✅ Status Konfigurasi

### 1. Midtrans Payment Gateway
Konfigurasi Midtrans sudah ditambahkan ke file `.env`:

```env
MIDTRANS_MERCHANT_ID=G699511196
MIDTRANS_CLIENT_KEY=SB-Mid-client-JV1hxBKvK54RC4PV
MIDTRANS_SERVER_KEY=SB-Mid-server-vtes-EfWHAlMBGLjEXF06HtG
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_SANITIZED=true
MIDTRANS_3DS=true
```

**Keterangan:**
- ✅ Mode: **Sandbox/Testing** (IS_PRODUCTION=false)
- ✅ 3D Secure: Aktif (untuk keamanan transaksi)
- ✅ Sanitized: Aktif (untuk membersihkan input)

**Cara Testing:**
1. Akses fitur pembayaran SPP
2. Gunakan test card number dari [Midtrans Sandbox](https://docs.midtrans.com/docs/testing-payment-on-sandbox)
3. Contoh test card: `4811 1111 1111 1114` (Visa, sukses)

---

### 2. Email Configuration (Forgot Password)

Konfigurasi email sudah diupdate di file `.env`:

```env
MAIL_MAILER=smtp
MAIL_SCHEME=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_USERNAME="isi_email_kamu@gmail.com"
MAIL_PASSWORD="isi_app_password_16_digit_disini"
MAIL_FROM_ADDRESS="isi_email_kamu@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"
```

## 🔧 Cara Setup Email Gmail

### Langkah 1: Login ke Akun Google
1. Buka [myaccount.google.com](https://myaccount.google.com)
2. Login dengan email yang akan digunakan untuk mengirim email forgot password

### Langkah 2: Aktifkan Verifikasi 2 Langkah (WAJIB!)
1. Klik menu **Keamanan** (Security) di sebelah kiri
2. Cari bagian **"Cara Anda login ke Google"** (How you sign in to Google)
3. Cari **Verifikasi 2 Langkah** (2-Step Verification)
4. Jika statusnya OFF/Tidak aktif, klik untuk mengaktifkan
5. Ikuti petunjuk untuk menautkan nomor HP Anda
6. ⚠️ **PENTING:** Menu App Password tidak akan muncul jika ini belum aktif!

### Langkah 3: Buat App Password
1. Masih di menu **Keamanan**, klik **Verifikasi 2 Langkah**
2. Scroll/gulir ke **paling bawah** halaman
3. Cari dan klik **"Sandi Aplikasi"** (App passwords)
   - *Alternatif:* Ketik "App passwords" di kolom pencarian di bagian atas

### Langkah 4: Generate Password Baru
1. Di **"Pilih aplikasi"** (Select app): Pilih **Email** (Mail)
2. Di **"Pilih perangkat"** (Select device): Pilih **Lainnya** (Other)
3. Ketik nama project, contoh: `SMAN12-CONNECT` atau `Laravel-Project`
4. Klik tombol **Buat** (Generate)

### Langkah 5: Copy Password
1. Google akan menampilkan kode **16 karakter** dalam kotak kuning
   - Contoh format: `abcd efgh ijkl mnop`
2. **Copy kode tersebut** (tanpa spasi)

### Langkah 6: Update File .env
1. Buka file `.env` di project Laravel Anda
2. Cari baris berikut:
   ```env
   MAIL_USERNAME="isi_email_kamu@gmail.com"
   MAIL_PASSWORD="isi_app_password_16_digit_disini"
   MAIL_FROM_ADDRESS="isi_email_kamu@gmail.com"
   ```
3. Ganti dengan data Anda:
   ```env
   MAIL_USERNAME="emailanda@gmail.com"
   MAIL_PASSWORD="abcdefghijklmnop"  # 16 digit tanpa spasi
   MAIL_FROM_ADDRESS="emailanda@gmail.com"
   ```

### Langkah 7: Clear Cache Laravel
Setelah update `.env`, jalankan command berikut:
```bash
php artisan config:clear
php artisan cache:clear
```

### Langkah 8: Test Email
1. Akses halaman **Forgot Password** di aplikasi
2. Masukkan email yang terdaftar
3. Cek inbox email untuk menerima link reset password
4. ⚠️ Jika tidak masuk inbox, cek folder **Spam/Junk**

---

## 📝 Contoh Konfigurasi Lengkap

Berikut contoh `.env` yang sudah terisi lengkap (ganti dengan data Anda):

```env
# Email Configuration
MAIL_MAILER=smtp
MAIL_SCHEME=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_USERNAME="admin@sman12.sch.id"
MAIL_PASSWORD="abcdefghijklmnop"
MAIL_FROM_ADDRESS="admin@sman12.sch.id"
MAIL_FROM_NAME="SMAN 12 Connect"

# Midtrans Payment Gateway
MIDTRANS_MERCHANT_ID=G699511196
MIDTRANS_CLIENT_KEY=SB-Mid-client-JV1hxBKvK54RC4PV
MIDTRANS_SERVER_KEY=SB-Mid-server-vtes-EfWHAlMBGLjEXF06HtG
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_SANITIZED=true
MIDTRANS_3DS=true
```

---

## ⚠️ Troubleshooting

### Email tidak terkirim
**Penyebab:**
- App Password salah
- Verifikasi 2 Langkah belum aktif
- Email belum diizinkan Less Secure Apps (sudah tidak diperlukan jika pakai App Password)

**Solusi:**
1. Generate ulang App Password
2. Pastikan copy password tanpa spasi
3. Cek log Laravel: `storage/logs/laravel.log`
4. Test dengan command:
   ```bash
   php artisan tinker
   Mail::raw('Test email', function($msg) {
       $msg->to('test@example.com')->subject('Test');
   });
   ```

### Midtrans Error "Unauthorized"
**Penyebab:**
- Server Key atau Client Key salah
- Belum clear cache config

**Solusi:**
1. Cek ulang credentials di Midtrans Dashboard
2. Jalankan: `php artisan config:clear`
3. Restart server: `php artisan serve`

---

## 🎯 Checklist Setup

- [x] Midtrans credentials ditambahkan ke `.env`
- [ ] Email Gmail sudah dipilih
- [ ] Verifikasi 2 Langkah sudah aktif
- [ ] App Password sudah di-generate
- [ ] App Password sudah dimasukkan ke `.env`
- [ ] Config cache sudah di-clear
- [ ] Test kirim email forgot password berhasil
- [ ] Test pembayaran Midtrans sandbox berhasil

---

## 📞 Kontak Support

**Midtrans:**
- Dashboard: https://dashboard.midtrans.com/
- Docs: https://docs.midtrans.com/
- Support: support@midtrans.com

**Gmail App Password:**
- Help: https://support.google.com/accounts/answer/185833

---

**Dibuat:** 21 November 2025
**Last Update:** 21 November 2025

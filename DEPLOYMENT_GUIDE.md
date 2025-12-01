# 🚀 Panduan Deployment SMAN12-CONNECT ke Hostinger

## 📋 Checklist Persiapan

### 1. Persiapan Lokal (Laragon)

- [x] ✅ Project sudah berjalan normal di Laragon
- [ ] ⚠️ Backup database terlebih dahulu
- [ ] ⚠️ Test semua fitur (login admin, guru, siswa)
- [ ] ⚠️ Update .env.production dengan data hosting

---

## 🔧 Langkah 1: Persiapan Database

### Export Database dari Laragon

```bash
# Buka terminal di folder project
cd C:\laragon\www\SMAN12-CONNECT

# Export database (via phpMyAdmin atau command)
# Atau gunakan Laragon > MySQL > phpMyAdmin > Export
```

**Via Command Line:**
```bash
C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysqldump -u root sman_connect > database_backup.sql
```

### Import ke Hostinger

1. Login ke **Hostinger Control Panel**
2. Buka **Databases** > **phpMyAdmin**
3. Buat database baru (misal: `u123456789_sman_connect`)
4. Import file `database_backup.sql`
5. Catat kredensial database:
   - DB_HOST (biasanya: `localhost`)
   - DB_DATABASE (nama database)
   - DB_USERNAME (username database)
   - DB_PASSWORD (password database)

---

## 📦 Langkah 2: Upload File ke Hostinger

### File yang HARUS di-upload:

```
✅ app/
✅ bootstrap/
✅ config/
✅ database/
✅ public/
✅ resources/
✅ routes/
✅ storage/
✅ .htaccess
✅ artisan
✅ composer.json
✅ composer.lock
✅ package.json
✅ package-lock.json
✅ vite.config.js
✅ tailwind.config.js
✅ postcss.config.js
```

### File yang TIDAK perlu di-upload:

```
❌ node_modules/        (terlalu besar, akan di-install di server)
❌ vendor/              (akan di-install via composer)
❌ .env                 (buat manual di server)
❌ .git/                (optional, tapi sebaiknya tidak)
❌ storage/logs/*       (log lokal tidak perlu)
❌ *.md files           (optional)
❌ tests/               (optional untuk production)
```

### Cara Upload:

**Opsi 1: Via File Manager Hostinger**
1. Login ke Hostinger
2. Buka **File Manager**
3. Upload file ZIP project
4. Extract di `public_html`

**Opsi 2: Via FTP (Recommended)**
1. Install FileZilla
2. Connect dengan kredensial FTP dari Hostinger
3. Upload semua file ke `public_html`

---

## ⚙️ Langkah 3: Konfigurasi di Server Hostinger

### 3.1 Buat file .env di server

1. Buka **File Manager** di Hostinger
2. Copy isi file `.env.production` 
3. Buat file baru `.env` di root project
4. Paste dan edit sesuai data Hostinger

**Penting yang harus diganti:**

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://namadomain-anda.com

DB_HOST=localhost
DB_DATABASE=u123456789_sman_connect
DB_USERNAME=u123456789_admin
DB_PASSWORD=password_database_anda

MAIL_USERNAME=email-sekolah@sman12.sch.id
MAIL_PASSWORD="app_password_16_digit"

MIDTRANS_MERCHANT_ID=production_merchant_id
MIDTRANS_CLIENT_KEY=production_client_key
MIDTRANS_SERVER_KEY=production_server_key
MIDTRANS_IS_PRODUCTION=true
```

### 3.2 Install Dependencies via SSH/Terminal

**Login SSH ke Hostinger:**

```bash
ssh u123456789@yourdomain.com
cd public_html
```

**Install Composer Dependencies:**

```bash
composer install --optimize-autoloader --no-dev
```

**Install NPM Dependencies & Build:**

```bash
npm install
npm run build
```

**Laravel Setup Commands:**

```bash
# Generate application key (jika belum ada di .env)
php artisan key:generate

# Run migrations
php artisan migrate --force

# Create storage link
php artisan storage:link

# Clear & cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

---

## 🔒 Langkah 4: Setup Security & Permissions

### 4.1 Set Permissions

```bash
chmod -R 755 public_html
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chown -R username:username *
```

### 4.2 Update .htaccess di public/

File sudah OK, tapi tambahkan security headers:

```apache
# Security Headers
<IfModule mod_headers.c>
    Header set X-Frame-Options "SAMEORIGIN"
    Header set X-Content-Type-Options "nosniff"
    Header set X-XSS-Protection "1; mode=block"
    Header set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>
```

### 4.3 Setup SSL Certificate

1. Di Hostinger Panel > **SSL**
2. Pilih **Let's Encrypt SSL** (Gratis)
3. Aktifkan untuk domain Anda
4. Force HTTPS

---

## 🗄️ Langkah 5: Setup MySQL Users (Role-Based Access)

**Jalankan di phpMyAdmin Hostinger:**

```sql
-- Buat users untuk role-based access
CREATE USER 'u123456789_admin_sia'@'localhost' IDENTIFIED BY 'PASSWORD_AMAN_ADMIN';
CREATE USER 'u123456789_guru_sia'@'localhost' IDENTIFIED BY 'PASSWORD_AMAN_GURU';
CREATE USER 'u123456789_siswa_sia'@'localhost' IDENTIFIED BY 'PASSWORD_AMAN_SISWA';

-- Grant privileges sesuai file database/sql/grants.sql
-- (Sesuaikan nama database dan user dengan prefix Hostinger)
```

Atau jalankan file `database/sql/grants.sql` yang sudah disesuaikan.

---

## 🌐 Langkah 6: Konfigurasi Domain & DNS

### Set Document Root ke /public

Di Hostinger:
1. **Advanced** > **Domain**
2. Pilih domain Anda
3. Set **Document Root** ke: `public_html/public`

Atau buat `.htaccess` di root:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

---

## 📱 Langkah 7: Setup Midtrans (Payment Gateway)

### Upgrade ke Production

1. Login ke **Midtrans Dashboard**
2. Switch dari **Sandbox** ke **Production**
3. Dapatkan Production Keys:
   - Merchant ID
   - Client Key
   - Server Key
4. Update di `.env` production

**Konfigurasi Notification URL:**
- Payment Notification URL: `https://yourdomain.com/api/midtrans/notification`
- Finish Redirect URL: `https://yourdomain.com/siswa/tagihan`
- Error Redirect URL: `https://yourdomain.com/siswa/tagihan`

---

## ✅ Langkah 8: Testing Production

### Test Checklist:

```
[ ] Website bisa diakses (https://yourdomain.com)
[ ] Login Admin berhasil
[ ] Login Guru berhasil
[ ] Login Siswa berhasil
[ ] Upload file/materi berhasil
[ ] Payment Midtrans berhasil
[ ] Email notifikasi terkirim
[ ] Database connection OK
[ ] Semua halaman load normal
[ ] Mobile responsive
```

### Test via Browser:

1. Buka `https://yourdomain.com`
2. Test login dengan setiap role
3. Test fitur upload
4. Test payment
5. Check console browser untuk error

---

## 🔍 Troubleshooting

### Error 500 - Internal Server Error

```bash
# Check logs
tail -f storage/logs/laravel.log

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Permission Denied Error

```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Database Connection Error

- Cek credentials di `.env`
- Pastikan DB_HOST benar (biasanya `localhost`)
- Test connection via phpMyAdmin

### CSS/JS Not Loading

```bash
# Re-build assets
npm run build

# Clear cache
php artisan cache:clear
```

### Midtrans Callback Error

- Pastikan Notification URL sudah diset di dashboard Midtrans
- Check firewall/security di Hostinger
- Test dengan Postman

---

## 📊 Monitoring & Maintenance

### Daily Tasks:

```bash
# Backup database (setup cron job)
0 2 * * * /usr/bin/mysqldump -u user -p database > /backup/db_$(date +\%Y\%m\%d).sql

# Clear old logs (jika ada)
php artisan log:clear
```

### Weekly Tasks:

- Monitor disk space
- Check error logs
- Backup database manual
- Update dependencies (jika ada security patch)

---

## 🎯 Post-Deployment

### Update .env.example

Update file `.env.example` untuk dokumentasi:

```env
APP_NAME=SMAN12-CONNECT
APP_ENV=production
APP_DEBUG=false
...
```

### Create Admin Account

```bash
php artisan tinker

# Create admin
$user = new App\Models\User();
$user->name = 'Administrator';
$user->email = 'admin@sman12.sch.id';
$user->password = bcrypt('admin123');
$user->role = 'admin';
$user->save();
```

---

## 📞 Support

Jika ada masalah:

1. **Check logs**: `storage/logs/laravel.log`
2. **Hostinger Support**: Live chat 24/7
3. **Laravel Docs**: https://laravel.com/docs

---

## 🎉 Selamat!

Website SMAN12-CONNECT sudah live di production! 🚀

**Important URLs:**
- Website: https://yourdomain.com
- Admin Panel: https://yourdomain.com/login
- Midtrans Dashboard: https://dashboard.midtrans.com

---

**Last Updated:** December 2025
**PHP Version Required:** 8.2+
**Laravel Version:** 12.0

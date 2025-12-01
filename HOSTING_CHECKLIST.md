# ✅ Checklist Deployment ke Hostinger

## 📋 Sebelum Upload

- [ ] **Backup Database**
  - [ ] Export database dari Laragon/phpMyAdmin
  - [ ] Simpan file SQL di folder `backups/`
  - [ ] Test restore database di lokal

- [ ] **Test Lokal Lengkap**
  - [ ] Login Admin berfungsi
  - [ ] Login Guru berfungsi  
  - [ ] Login Siswa berfungsi
  - [ ] Upload materi/tugas berfungsi
  - [ ] Payment Midtrans berfungsi (sandbox)
  - [ ] Email notifikasi terkirim

- [ ] **Persiapkan Kredensial**
  - [ ] Email production (Gmail dengan App Password)
  - [ ] Midtrans Production Keys
  - [ ] Google Maps API Key (jika ada quota)
  - [ ] Domain & SSL ready

---

## 🌐 Setup Hostinger

- [ ] **Buat Database di Hostinger**
  - [ ] Login Hostinger Control Panel
  - [ ] Buat MySQL Database baru
  - [ ] Catat: DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD
  - [ ] Import database SQL dari backup

- [ ] **Buat MySQL Users untuk RBAC**
  - [ ] User Admin: `u123_admin_sia`
  - [ ] User Guru: `u123_guru_sia`
  - [ ] User Siswa: `u123_siswa_sia`
  - [ ] Set password yang kuat untuk setiap user
  - [ ] Jalankan GRANT privileges sesuai `database/sql/grants.sql`

- [ ] **Setup FTP/File Manager**
  - [ ] Dapatkan FTP credentials
  - [ ] Test koneksi FTP

---

## 📤 Upload Files

- [ ] **Upload via FTP/File Manager**
  - [ ] Upload semua folder kecuali: `node_modules`, `vendor`, `.git`
  - [ ] Upload ke folder `public_html`
  - [ ] Pastikan struktur folder benar

- [ ] **Buat File .env di Server**
  - [ ] Copy dari `.env.production`
  - [ ] Update semua credentials:
    - [ ] APP_URL sesuai domain
    - [ ] DB_* sesuai database Hostinger
    - [ ] MAIL_* sesuai email production
    - [ ] MIDTRANS_* sesuai production keys
  - [ ] Set APP_DEBUG=false
  - [ ] Set APP_ENV=production

---

## 🔧 Instalasi di Server

- [ ] **SSH ke Server Hostinger**
  ```bash
  ssh u123456789@yourdomain.com
  cd public_html
  ```

- [ ] **Install Dependencies**
  ```bash
  composer install --optimize-autoloader --no-dev
  npm install
  npm run build
  ```

- [ ] **Laravel Setup**
  ```bash
  php artisan key:generate
  php artisan migrate --force
  php artisan storage:link
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  ```

- [ ] **Set Permissions**
  ```bash
  chmod -R 775 storage
  chmod -R 775 bootstrap/cache
  ```

---

## 🔒 Security Setup

- [ ] **SSL Certificate**
  - [ ] Aktifkan SSL di Hostinger
  - [ ] Force HTTPS
  - [ ] Test https://yourdomain.com

- [ ] **File Permissions**
  - [ ] `.env` → 644 (read only)
  - [ ] `storage/` → 775
  - [ ] `bootstrap/cache/` → 775

- [ ] **Security Headers**
  - [ ] Update `.htaccess` dengan security headers
  - [ ] Test dengan securityheaders.com

---

## 🌍 Konfigurasi Domain

- [ ] **Set Document Root**
  - [ ] Hostinger: Set ke `public_html/public`
  - [ ] Atau buat `.htaccess` redirect ke `/public`

- [ ] **DNS & Nameservers**
  - [ ] Update nameservers domain ke Hostinger
  - [ ] Tunggu propagasi DNS (24-48 jam)

---

## 💳 Midtrans Production

- [ ] **Upgrade ke Production**
  - [ ] Login Midtrans Dashboard
  - [ ] Switch dari Sandbox ke Production
  - [ ] Dapatkan Production Keys

- [ ] **Set Notification URL**
  - [ ] Payment Notification: `https://yourdomain.com/api/midtrans/notification`
  - [ ] Finish URL: `https://yourdomain.com/siswa/tagihan`
  - [ ] Error URL: `https://yourdomain.com/siswa/tagihan`

- [ ] **Test Payment**
  - [ ] Test dengan kartu kredit real (minimal amount)
  - [ ] Check callback diterima

---

## ✅ Testing Production

- [ ] **Akses Website**
  - [ ] https://yourdomain.com load normal
  - [ ] No 500 error
  - [ ] No 404 error

- [ ] **Test Login**
  - [ ] Admin login berhasil
  - [ ] Guru login berhasil
  - [ ] Siswa login berhasil

- [ ] **Test Fitur**
  - [ ] Upload materi berhasil
  - [ ] Upload tugas berhasil
  - [ ] Absensi berfungsi
  - [ ] Nilai raport muncul
  - [ ] Payment Midtrans berhasil
  - [ ] Email notifikasi terkirim

- [ ] **Test Mobile**
  - [ ] Responsive di mobile
  - [ ] Touch navigation OK
  - [ ] Forms berfungsi di mobile

- [ ] **Check Browser Console**
  - [ ] No JavaScript errors
  - [ ] CSS load semua
  - [ ] Images load semua

---

## 🔍 Monitoring

- [ ] **Setup Monitoring**
  - [ ] Aktifkan UptimeRobot atau similar
  - [ ] Monitor disk space
  - [ ] Monitor database size

- [ ] **Backup Automation**
  - [ ] Setup cron job backup database harian
  - [ ] Download backup mingguan ke lokal
  - [ ] Test restore dari backup

- [ ] **Error Logging**
  - [ ] Check `storage/logs/laravel.log`
  - [ ] Setup log rotation
  - [ ] Monitor error patterns

---

## 📝 Post-Deployment

- [ ] **Dokumentasi**
  - [ ] Catat semua credentials di password manager
  - [ ] Update README.md dengan info production
  - [ ] Share credentials dengan tim (secure)

- [ ] **User Training**
  - [ ] Train admin cara pakai system
  - [ ] Train guru cara upload materi
  - [ ] Train siswa cara akses & bayar

- [ ] **Support**
  - [ ] Siapkan support channel (WhatsApp/Email)
  - [ ] Monitor feedback user minggu pertama
  - [ ] Fix bugs yang ditemukan

---

## 🚨 Rollback Plan

Jika ada masalah serius:

- [ ] **Backup current state**
  ```bash
  mysqldump -u user -p database > rollback_backup.sql
  ```

- [ ] **Restore previous version**
  - [ ] Restore database dari backup terakhir yang stabil
  - [ ] Upload kode versi sebelumnya
  - [ ] Clear cache

- [ ] **Notify users**
  - [ ] Buat pengumuman maintenance
  - [ ] Estimasi waktu perbaikan

---

## ✨ Success Criteria

Website dianggap berhasil deploy jika:

✅ Accessible 24/7 tanpa downtime  
✅ Semua fitur berfungsi normal  
✅ Payment gateway aktif  
✅ Email notifikasi terkirim  
✅ Mobile responsive  
✅ Load time < 3 detik  
✅ No critical errors di logs  

---

**Created:** December 2025  
**Last Updated:** -  
**Status:** 🟡 Ready for Deployment

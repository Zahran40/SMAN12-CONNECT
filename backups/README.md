# Folder untuk menyimpan backup database

## Format Backup
File backup disimpan dengan format:
```
sman_connect_YYYYMMDD_HHMMSS.sql
```

## Cara Backup

### Manual via phpMyAdmin:
1. Buka http://localhost/phpmyadmin
2. Pilih database `sman_connect`
3. Tab "Export"
4. Pilih "Quick" method
5. Klik "Go"
6. Simpan file SQL di folder ini

### Otomatis via Script:
Jalankan file `backup_database.bat` di root project

## Restore Database

### Di Laragon (Lokal):
```bash
C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysql -u root sman_connect < backup_file.sql
```

### Di Hostinger (Production):
Via phpMyAdmin Hostinger:
1. Login phpMyAdmin
2. Pilih database
3. Tab "Import"
4. Upload file SQL
5. Klik "Go"

## ⚠️ Important
- Backup sebelum setiap deployment
- Simpan minimal 3 backup terakhir
- Test restore secara berkala

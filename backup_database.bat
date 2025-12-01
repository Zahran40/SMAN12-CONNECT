@echo off
REM ============================================
REM Script Backup Database SMAN12-CONNECT
REM Untuk dijalankan di Laragon sebelum deploy
REM ============================================

SET MYSQL_PATH=C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin
SET BACKUP_DIR=C:\laragon\www\SMAN12-CONNECT\backups
SET DB_NAME=sman_connect
SET DB_USER=root
SET DB_PASS=

REM Buat folder backup jika belum ada
if not exist "%BACKUP_DIR%" mkdir "%BACKUP_DIR%"

REM Generate filename dengan tanggal
SET BACKUP_FILE=%BACKUP_DIR%\sman_connect_%date:~-4,4%%date:~-7,2%%date:~-10,2%_%time:~0,2%%time:~3,2%%time:~6,2%.sql
SET BACKUP_FILE=%BACKUP_FILE: =0%

echo ============================================
echo Backup Database: %DB_NAME%
echo Backup ke: %BACKUP_FILE%
echo ============================================

REM Jalankan mysqldump
"%MYSQL_PATH%\mysqldump.exe" -u %DB_USER% %DB_NAME% > "%BACKUP_FILE%"

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ✅ Backup berhasil!
    echo File: %BACKUP_FILE%
    echo.
) else (
    echo.
    echo ❌ Backup gagal! Error code: %ERRORLEVEL%
    echo.
)

pause

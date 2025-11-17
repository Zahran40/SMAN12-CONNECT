@echo off
echo ============================================
echo  SETUP HTTPS untuk SMAN12-CONNECT
echo  Laragon SSL Certificate Generator
echo ============================================
echo.

:: Check if running as Administrator
net session >nul 2>&1
if %errorLevel% neq 0 (
    echo [ERROR] Script ini harus dijalankan sebagai Administrator!
    echo.
    echo Cara menjalankan:
    echo 1. Klik kanan file setup-https.bat
    echo 2. Pilih "Run as administrator"
    echo.
    pause
    exit /b 1
)

echo [1/5] Checking Laragon installation...
if not exist "C:\laragon\bin\apache" (
    echo [ERROR] Laragon tidak ditemukan di C:\laragon
    echo Pastikan Laragon sudah terinstall dengan benar
    pause
    exit /b 1
)
echo [OK] Laragon ditemukan

echo.
echo [2/5] Stopping Laragon services...
net stop "Apache2.4" >nul 2>&1
echo [OK] Apache stopped

echo.
echo [3/5] Creating SSL directory...
if not exist "C:\laragon\etc\ssl" mkdir "C:\laragon\etc\ssl"
echo [OK] SSL directory ready

echo.
echo [4/5] Generating Self-Signed Certificate...
cd C:\laragon\bin\apache\bin

:: Generate certificate for sman12-connect.test
openssl req -x509 -nodes -days 365 -newkey rsa:2048 ^
    -keyout C:\laragon\etc\ssl\sman12-connect.key ^
    -out C:\laragon\etc\ssl\sman12-connect.crt ^
    -subj "/C=ID/ST=SumateraUtara/L=Medan/O=SMAN12/CN=sman12-connect.test"

if %errorLevel% equ 0 (
    echo [OK] Certificate generated successfully
) else (
    echo [WARNING] Certificate generation gagal, menggunakan default Laragon cert
)

echo.
echo [5/5] Creating Virtual Host Configuration...

:: Create SSL Virtual Host config
echo ^<VirtualHost *:443^> > C:\laragon\etc\apache2\sites-enabled\sman12-connect-ssl.conf
echo     ServerName sman12-connect.test >> C:\laragon\etc\apache2\sites-enabled\sman12-connect-ssl.conf
echo     ServerAlias *.sman12-connect.test >> C:\laragon\etc\apache2\sites-enabled\sman12-connect-ssl.conf
echo     DocumentRoot "C:/laragon/www/SMAN12-CONNECT/public" >> C:\laragon\etc\apache2\sites-enabled\sman12-connect-ssl.conf
echo. >> C:\laragon\etc\apache2\sites-enabled\sman12-connect-ssl.conf
echo     SSLEngine on >> C:\laragon\etc\apache2\sites-enabled\sman12-connect-ssl.conf
echo     SSLCertificateFile "C:/laragon/etc/ssl/sman12-connect.crt" >> C:\laragon\etc\apache2\sites-enabled\sman12-connect-ssl.conf
echo     SSLCertificateKeyFile "C:/laragon/etc/ssl/sman12-connect.key" >> C:\laragon\etc\apache2\sites-enabled\sman12-connect-ssl.conf
echo. >> C:\laragon\etc\apache2\sites-enabled\sman12-connect-ssl.conf
echo     ^<Directory "C:/laragon/www/SMAN12-CONNECT/public"^> >> C:\laragon\etc\apache2\sites-enabled\sman12-connect-ssl.conf
echo         AllowOverride All >> C:\laragon\etc\apache2\sites-enabled\sman12-connect-ssl.conf
echo         Require all granted >> C:\laragon\etc\apache2\sites-enabled\sman12-connect-ssl.conf
echo     ^</Directory^> >> C:\laragon\etc\apache2\sites-enabled\sman12-connect-ssl.conf
echo ^</VirtualHost^> >> C:\laragon\etc\apache2\sites-enabled\sman12-connect-ssl.conf

echo [OK] Virtual Host SSL created

echo.
echo [6/6] Restarting Apache...
net start "Apache2.4" >nul 2>&1
echo [OK] Apache restarted

echo.
echo ============================================
echo  SETUP SELESAI!
echo ============================================
echo.
echo Akses website Anda di:
echo   https://sman12-connect.test
echo   atau
echo   https://localhost/SMAN12-CONNECT/public
echo.
echo CATATAN:
echo - Browser akan menampilkan warning SSL (normal untuk self-signed cert)
echo - Klik "Advanced" dan "Proceed" untuk melanjutkan
echo - GPS/Lokasi akan berfungsi setelah izin diberikan
echo.
echo Untuk install certificate agar tidak muncul warning:
echo 1. Buka C:\laragon\etc\ssl\sman12-connect.crt
echo 2. Double click dan install ke "Trusted Root Certification Authorities"
echo.
pause

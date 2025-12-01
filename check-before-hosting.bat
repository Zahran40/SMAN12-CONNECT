@echo off
chcp 65001 > nul
color 0A
echo.
echo ================================================
echo 🔍 CHECKING FILES BEFORE HOSTING
echo ================================================
echo.

SET ERRORS=0
SET WARNINGS=0

REM 1. Check vendor folder
echo [1/7] Checking vendor folder...
if exist "vendor\autoload.php" (
    echo ✓ vendor folder EXISTS
) else (
    echo ✗ vendor folder MISSING! Run: composer install
    SET /A ERRORS+=1
)
echo.

REM 2. Check public/build folder
echo [2/7] Checking public/build folder...
if exist "public\build\manifest.json" (
    echo ✓ public/build folder EXISTS
) else (
    echo ✗ public/build MISSING! Run: npm run build
    SET /A ERRORS+=1
)
echo.

REM 3. Check .env file
echo [3/7] Checking .env file...
if exist ".env" (
    echo ✓ .env EXISTS
) else (
    echo ✗ .env MISSING! Copy from .env.example
    SET /A ERRORS+=1
)
echo.

REM 4. Check .env.production file
echo [4/7] Checking .env.production file...
if exist ".env.production" (
    echo ✓ .env.production EXISTS (template for hosting)
) else (
    echo ⚠ .env.production NOT FOUND (optional but recommended)
    SET /A WARNINGS+=1
)
echo.

REM 5. Check node_modules
echo [5/7] Checking node_modules...
if exist "node_modules" (
    echo ✓ node_modules EXISTS
    echo   (Note: Don't upload this to hosting, too big!)
) else (
    echo ⚠ node_modules MISSING (will need npm install at server)
    SET /A WARNINGS+=1
)
echo.

REM 6. Check composer.lock
echo [6/7] Checking composer.lock...
if exist "composer.lock" (
    echo ✓ composer.lock EXISTS
) else (
    echo ✗ composer.lock MISSING!
    SET /A ERRORS+=1
)
echo.

REM 7. Check critical Laravel folders
echo [7/7] Checking Laravel core folders...
SET MISSING_FOLDERS=0
if not exist "app" SET /A MISSING_FOLDERS+=1
if not exist "bootstrap" SET /A MISSING_FOLDERS+=1
if not exist "config" SET /A MISSING_FOLDERS+=1
if not exist "database" SET /A MISSING_FOLDERS+=1
if not exist "public" SET /A MISSING_FOLDERS+=1
if not exist "resources" SET /A MISSING_FOLDERS+=1
if not exist "routes" SET /A MISSING_FOLDERS+=1
if not exist "storage" SET /A MISSING_FOLDERS+=1

if %MISSING_FOLDERS% EQU 0 (
    echo ✓ All core Laravel folders EXIST
) else (
    echo ✗ Missing %MISSING_FOLDERS% core Laravel folders!
    SET /A ERRORS+=1
)
echo.

echo ================================================
echo 📦 WHAT TO INCLUDE IN ZIP FILE:
echo ================================================
echo.
echo ✅ MUST INCLUDE:
echo    - vendor/ folder
echo    - public/build/ folder
echo    - .env.production (rename to .env at server)
echo    - app/, bootstrap/, config/, database/
echo    - public/, resources/, routes/, storage/
echo    - composer.json, composer.lock
echo    - package.json, package-lock.json
echo    - .htaccess, artisan
echo.
echo ❌ DO NOT INCLUDE:
echo    - node_modules/ (too big, 100MB+)
echo    - .git/ folder
echo    - .env (use .env.production instead)
echo    - storage/logs/*.log
echo    - backups/ folder
echo.

echo ================================================
echo 📊 SUMMARY:
echo ================================================

if %ERRORS% EQU 0 (
    if %WARNINGS% EQU 0 (
        color 0A
        echo.
        echo ✅ ALL CHECKS PASSED! Ready for hosting.
        echo.
        echo Next steps:
        echo 1. Create ZIP file with required files
        echo 2. Upload to Hostinger
        echo 3. Follow DEPLOYMENT_GUIDE.md
        echo.
    ) else (
        color 0E
        echo.
        echo ⚠️  %WARNINGS% warning(s) found.
        echo    You can proceed but review warnings above.
        echo.
    )
) else (
    color 0C
    echo.
    echo ❌ %ERRORS% CRITICAL error(s) found!
    echo.
    if %WARNINGS% GTR 0 (
        echo    Also %WARNINGS% warning(s) found.
    )
    echo.
    echo Please fix errors above before creating ZIP.
    echo.
)

pause

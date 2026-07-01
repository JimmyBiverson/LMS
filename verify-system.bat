@echo off
REM LMS System Verification Script (Windows)

echo 🚀 LMS System Verification Script
echo ==================================
echo.

REM Check if we're in the right directory
if not exist "artisan" (
    echo ✗ Error: Not in LMS directory
    echo Please run this script from the LMS root directory
    pause
    exit /b 1
)

echo 📋 Checking system requirements...
echo.

REM Check PHP
php -v >nul 2>&1
if %ERRORLEVEL% EQU 0 (
    for /f "tokens=2" %%i in ('php -v ^| findstr PHP') do set PHP_VERSION=%%i
    echo ✓ PHP %PHP_VERSION% found
) else (
    echo ✗ PHP not found
    pause
    exit /b 1
)

REM Check Composer
composer -V >nul 2>&1
if %ERRORLEVEL% EQU 0 (
    echo ✓ Composer found
) else (
    echo ✗ Composer not found
    pause
    exit /b 1
)

echo.
echo 🗂️  Checking project files...
echo.

REM Check key files
setlocal enabledelayedexpansion
set files=app\Http\Controllers\AuthController.php app\Models\User.php routes\web.php resources\views\courses\lesson.blade.php resources\views\components\video-player.blade.php

for %%F in (%files%) do (
    if exist "%%F" (
        echo ✓ %%F
    ) else (
        echo ✗ %%F missing
    )
)

echo.
echo 💾 Checking database...
echo.

REM Check if .env exists
if exist ".env" (
    echo ✓ .env file found
) else (
    echo ✗ .env file not found
    echo Copy .env.example to .env and configure it
)

echo.
echo 👥 Checking test users...
echo.

REM Try to check if users exist
php artisan tinker --execute "echo App\Models\User::count();" >nul 2>&1
if %ERRORLEVEL% EQU 0 (
    echo ✓ Database is accessible
    echo Run: php artisan db:seed --class=PresentationTestUsersSeeder
) else (
    echo ⚠ Could not access database
)

echo.
echo ==================================
echo ✅ Verification Complete!
echo ==================================
echo.
echo 📚 Next Steps:
echo.
echo 1. Start development server:
echo    php artisan serve
echo.
echo 2. Open in browser:
echo    http://localhost:8000
echo.
echo 3. Login with test account:
echo    Email: student1@lms.test
echo    Password: Password@123
echo.
echo 4. For more info, see:
echo    - README_PRESENTATION.md
echo    - IMPLEMENTATION_GUIDE.md
echo    - TESTING_CHECKLIST.md
echo.
echo Good luck with your presentation! 🚀
echo.
pause

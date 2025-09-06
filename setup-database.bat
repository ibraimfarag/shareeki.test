@echo off
echo ========================================
echo       Laravel Database Setup
echo ========================================
echo.

echo [1/4] Checking database connection...
php artisan migrate:status
if %errorlevel% neq 0 (
    echo ERROR: Database connection failed!
    pause
    exit /b 1
)

echo.
echo [2/4] Running safe migrations...
php artisan migrate:safe --force

echo.
echo [3/4] Checking and creating missing tables...
php artisan db:check-and-migrate

echo.
echo [4/4] Final migration status...
php artisan migrate:status

echo.
echo ========================================
echo      Database setup completed!
echo ========================================
pause

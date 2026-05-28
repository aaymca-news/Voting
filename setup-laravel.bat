@echo off
REM ============================================================
REM  AAYMCAVoting — Laravel Setup Script
REM  Run this ONCE after installing PHP, Composer, PostgreSQL
REM  Double-click or run from terminal inside project folder
REM ============================================================

echo.
echo ==========================================
echo   AAYMCAVoting - Laravel Setup
echo ==========================================
echo.

REM --- Check we're in the right folder ---
if not exist "artisan" (
    echo ERROR: artisan file not found.
    echo Make sure you run this script from inside the project folder.
    echo Example: cd "C:\PhP Projects\aaymca-voting\aaymca-voting"
    pause
    exit /b 1
)

REM --- Step 1: Create database ---
echo [Step 1/7] Creating PostgreSQL database...
set PGPASSWORD=1234
psql -U postgres -h 127.0.0.1 -p 5432 -c "CREATE DATABASE aaymca_voting;" 2>nul
if %errorlevel% == 0 (
    echo   SUCCESS: Database 'aaymca_voting' created.
) else (
    echo   INFO: Database may already exist, continuing...
)
echo.

REM --- Step 2: Composer install (if vendor missing) ---
echo [Step 2/7] Checking Composer dependencies...
if not exist "vendor\autoload.php" (
    echo   Installing Composer packages...
    composer install --no-interaction
) else (
    echo   vendor/ already present, skipping.
)
echo.

REM --- Step 3: NPM install (if node_modules missing) ---
echo [Step 3/7] Checking NPM dependencies...
if not exist "node_modules" (
    echo   Installing NPM packages...
    npm install
) else (
    echo   node_modules/ already present, skipping.
)
echo.

REM --- Step 4: Generate APP_KEY if missing ---
echo [Step 4/7] Checking APP_KEY...
findstr /c:"APP_KEY=base64:" .env >nul 2>&1
if %errorlevel% neq 0 (
    echo   Generating APP_KEY...
    php artisan key:generate
) else (
    echo   APP_KEY already set, skipping.
)
echo.

REM --- Step 5: Run migrations ---
echo [Step 5/7] Running database migrations...
php artisan migrate --force
if %errorlevel% neq 0 (
    echo   ERROR: Migrations failed. Check your DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD in .env
    pause
    exit /b 1
)
echo.

REM --- Step 6: Storage symlink ---
echo [Step 6/7] Creating storage symlink...
php artisan storage:link
echo.

REM --- Step 7: Build frontend assets ---
echo [Step 7/7] Building frontend assets...
npm run build
echo.

REM --- Clear caches ---
echo Clearing application caches...
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
echo.

echo ==========================================
echo   Setup Complete!
echo ==========================================
echo.
echo   To start the development server, run:
echo.
echo     php artisan serve
echo.
echo   Then open: http://127.0.0.1:8000
echo.
echo   To run frontend in watch mode (2nd terminal):
echo     npm run dev
echo.
echo   First-time admin setup:
echo     1. Register at http://127.0.0.1:8000/register
echo     2. Then visit: http://127.0.0.1:8000/make-ray-admin
echo.
pause

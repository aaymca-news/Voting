# ============================================================
#  AAYMCAVoting — Prerequisites Checker
#  Run this script from PowerShell inside the project folder:
#  PS> .\check-prerequisites.ps1
# ============================================================

$pass = [char]0x2705   # ✅
$fail = [char]0x274C   # ❌
$warn = [char]0x26A0   # ⚠️

Write-Host ""
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "  AAYMCAVoting — Prerequisites Check" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""

# --- PHP ---
Write-Host "[ PHP ]" -ForegroundColor Yellow
$phpPath = Get-Command php -ErrorAction SilentlyContinue
if ($phpPath) {
    $phpVersion = php -r "echo PHP_VERSION;"
    $major = [int]($phpVersion.Split('.')[0])
    $minor = [int]($phpVersion.Split('.')[1])
    if ($major -ge 8 -and $minor -ge 4) {
        Write-Host "  $pass PHP $phpVersion — OK" -ForegroundColor Green
    } else {
        Write-Host "  $fail PHP $phpVersion found but project requires PHP ^8.4" -ForegroundColor Red
        Write-Host "       Download: https://windows.php.net/download (VS17 x64 Non Thread Safe)" -ForegroundColor Gray
    }

    # Check required extensions
    $required = @('pdo_pgsql','pgsql','openssl','mbstring','fileinfo','gd','zip','tokenizer','xml','ctype','json','bcmath')
    Write-Host ""
    Write-Host "  PHP Extensions:" -ForegroundColor Yellow
    foreach ($ext in $required) {
        $loaded = php -r "echo extension_loaded('$ext') ? 'yes' : 'no';"
        if ($loaded -eq 'yes') {
            Write-Host "    $pass $ext" -ForegroundColor Green
        } else {
            Write-Host "    $fail $ext  <-- MISSING (enable in php.ini)" -ForegroundColor Red
        }
    }
} else {
    Write-Host "  $fail PHP not found in PATH" -ForegroundColor Red
    Write-Host "       Download: https://windows.php.net/download" -ForegroundColor Gray
    Write-Host "       (choose VS17 x64 Non Thread Safe, add to system PATH)" -ForegroundColor Gray
}

Write-Host ""

# --- Composer ---
Write-Host "[ Composer ]" -ForegroundColor Yellow
$composerPath = Get-Command composer -ErrorAction SilentlyContinue
if ($composerPath) {
    $composerVersion = composer --version --no-ansi 2>&1 | Select-String "Composer version" | ForEach-Object { $_.ToString().Trim() }
    Write-Host "  $pass $composerVersion" -ForegroundColor Green
} else {
    Write-Host "  $fail Composer not found" -ForegroundColor Red
    Write-Host "       Download: https://getcomposer.org/Composer-Setup.exe" -ForegroundColor Gray
}

Write-Host ""

# --- Node.js ---
Write-Host "[ Node.js ]" -ForegroundColor Yellow
$nodePath = Get-Command node -ErrorAction SilentlyContinue
if ($nodePath) {
    $nodeVersion = node --version
    Write-Host "  $pass Node $nodeVersion — OK" -ForegroundColor Green
} else {
    Write-Host "  $fail Node.js not found" -ForegroundColor Red
    Write-Host "       Download: https://nodejs.org (LTS)" -ForegroundColor Gray
}

# --- npm ---
$npmPath = Get-Command npm -ErrorAction SilentlyContinue
if ($npmPath) {
    $npmVersion = npm --version
    Write-Host "  $pass npm v$npmVersion — OK" -ForegroundColor Green
} else {
    Write-Host "  $fail npm not found (comes with Node.js)" -ForegroundColor Red
}

Write-Host ""

# --- PostgreSQL ---
Write-Host "[ PostgreSQL ]" -ForegroundColor Yellow
$psqlPath = Get-Command psql -ErrorAction SilentlyContinue
if ($psqlPath) {
    $psqlVersion = psql --version
    Write-Host "  $pass $psqlVersion — OK" -ForegroundColor Green

    # Try to connect and check if database exists
    Write-Host ""
    Write-Host "  Checking database 'aaymca_voting'..." -ForegroundColor Yellow
    $env:PGPASSWORD = "1234"
    $dbCheck = psql -U postgres -h 127.0.0.1 -p 5432 -lqt 2>&1 | Select-String "aaymca_voting"
    if ($dbCheck) {
        Write-Host "  $pass Database 'aaymca_voting' exists" -ForegroundColor Green
    } else {
        Write-Host "  $warn Database 'aaymca_voting' not found — will be created by setup script" -ForegroundColor DarkYellow
    }
} else {
    Write-Host "  $fail PostgreSQL not found" -ForegroundColor Red
    Write-Host "       Download: https://www.postgresql.org/download/windows/" -ForegroundColor Gray
    Write-Host "       Set password to: 1234  (or update .env DB_PASSWORD)" -ForegroundColor Gray
}

Write-Host ""

# --- .env file ---
Write-Host "[ .env File ]" -ForegroundColor Yellow
if (Test-Path ".env") {
    $appKey = Select-String -Path ".env" -Pattern "APP_KEY=base64:" -Quiet
    if ($appKey) {
        Write-Host "  $pass .env exists and APP_KEY is set" -ForegroundColor Green
    } else {
        Write-Host "  $warn .env exists but APP_KEY may be missing — run: php artisan key:generate" -ForegroundColor DarkYellow
    }
} else {
    Write-Host "  $fail .env not found — run: copy .env.example .env" -ForegroundColor Red
}

# --- vendor folder ---
Write-Host ""
Write-Host "[ Composer Dependencies ]" -ForegroundColor Yellow
if (Test-Path "vendor\autoload.php") {
    Write-Host "  $pass vendor/ folder present" -ForegroundColor Green
} else {
    Write-Host "  $fail vendor/ missing — run: composer install" -ForegroundColor Red
}

# --- node_modules folder ---
Write-Host ""
Write-Host "[ NPM Dependencies ]" -ForegroundColor Yellow
if (Test-Path "node_modules") {
    Write-Host "  $pass node_modules/ folder present" -ForegroundColor Green
} else {
    Write-Host "  $fail node_modules missing — run: npm install" -ForegroundColor Red
}

# --- public/build ---
Write-Host ""
Write-Host "[ Frontend Build ]" -ForegroundColor Yellow
if (Test-Path "public\build\manifest.json") {
    Write-Host "  $pass public/build exists (assets compiled)" -ForegroundColor Green
} else {
    Write-Host "  $warn No build found — run: npm run build  (or npm run dev for development)" -ForegroundColor DarkYellow
}

Write-Host ""
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "  Done! Fix any RED items above, then" -ForegroundColor Cyan
Write-Host "  run:  .\setup-laravel.bat" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""

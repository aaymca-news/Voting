# AAYMCAVoting — Local Development Setup Guide

## What Needs to Be Installed

Your project code, Composer packages (`vendor/`), and NPM packages (`node_modules/`) are all intact.
You only need to install these three tools on this machine:

| Tool | Required Version | Status |
|------|-----------------|--------|
| PHP | ^8.4 | ❌ Missing |
| Composer | Latest | ❌ Missing |
| PostgreSQL | 14+ | ❌ Missing |
| Node.js | Already installed (v22) | ✅ OK |
| npm | Already installed (v10) | ✅ OK |

---

## Step 1 — Install PHP 8.4

1. Go to: **https://windows.php.net/download**
2. Download **PHP 8.4 — VS17 x64 Non Thread Safe** (the `.zip` file)
3. Extract it to `C:\php` (create that folder)
4. Add `C:\php` to your Windows **System PATH**:
   - Search → "Edit the system environment variables"
   - Environment Variables → System Variables → Path → Edit → New → `C:\php`
5. Copy `php.ini-development` → rename to `php.ini`
6. Open `php.ini` and uncomment (remove the `;`) these lines:

```ini
extension=pdo_pgsql
extension=pgsql
extension=openssl
extension=mbstring
extension=fileinfo
extension=gd
extension=zip
extension=curl
extension=intl
extension=bcmath
```

7. Also find and set the extension directory path:
```ini
extension_dir = "C:\php\ext"
```

8. Verify: open a new terminal and run:
```
php --version
```
You should see `PHP 8.4.x`

---

## Step 2 — Install Composer

1. Download: **https://getcomposer.org/Composer-Setup.exe**
2. Run the installer — it will auto-detect your PHP at `C:\php\php.exe`
3. Verify:
```
composer --version
```

---

## Step 3 — Install PostgreSQL

1. Download: **https://www.postgresql.org/download/windows/**
2. Run the installer. During setup:
   - **Password**: set to `1234` (matches your `.env` — or change `.env` to match your password)
   - **Port**: `5432` (default)
   - **Locale**: default
3. When asked to launch Stack Builder at the end — you can skip it
4. Add PostgreSQL `bin` to your PATH (the installer usually does this):
   `C:\Program Files\PostgreSQL\17\bin`
5. Verify:
```
psql --version
```

---

## Step 4 — Run the Setup Script

Once PHP, Composer, and PostgreSQL are installed:

1. Open a terminal (Command Prompt or PowerShell)
2. Navigate to the project folder:
```
cd "C:\PhP Projects\aaymca-voting\aaymca-voting"
```
3. Run the setup script:
```
.\setup-laravel.bat
```

This script will automatically:
- Create the `aaymca_voting` database
- Run all 21 migrations
- Create the storage symlink
- Build frontend assets
- Clear all caches

---

## Step 5 — Start Developing

### Start the server:
```bash
php artisan serve
```
Open: **http://127.0.0.1:8000**

### Start frontend watcher (in a second terminal):
```bash
npm run dev
```

### Or run everything at once:
```bash
composer run dev
```
This starts PHP server, queue worker, log watcher, and Vite — all together.

---

## First Login & Admin Access

1. Register a new account at **http://127.0.0.1:8000/register**
2. Then visit: **http://127.0.0.1:8000/make-ray-admin**
   This promotes your account to admin role
3. You'll have full access to the admin dashboard at **/dashboard**

---

## Verify Everything is Working

Run the prerequisite checker at any time:
```powershell
powershell -ExecutionPolicy Bypass -File .\check-prerequisites.ps1
```

---

## Project Overview

| Feature | Details |
|---------|---------|
| Framework | Laravel 13.8 |
| Frontend | Blade + TailwindCSS + AlpineJS |
| Database | PostgreSQL (pgsql) |
| Auth | Laravel Breeze |
| PDF Export | barryvdh/laravel-dompdf |
| Build tool | Vite |
| Deployment | Railway (railway.json configured) |

### Key Routes
| URL | Description |
|-----|-------------|
| `/` | Welcome / Landing page |
| `/register` | User registration |
| `/login` | Login |
| `/dashboard` | Admin dashboard (admin only) |
| `/voter` | Voter panel |
| `/groups` | Manage groups (admin) |
| `/elections` | Manage elections (admin) |
| `/audit-logs` | Audit trail (admin) |
| `/reports` | Reports + PDF export (admin) |

---

## Troubleshooting

**Migrations fail:**
Check `.env` DB settings match your PostgreSQL setup:
```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=aaymca_voting
DB_USERNAME=postgres
DB_PASSWORD=1234
```

**`php` not found after installing:**
Close and reopen your terminal (PATH changes need a fresh terminal).

**PostgreSQL connection refused:**
Make sure the PostgreSQL service is running:
- Search → "Services" → find `postgresql-x64-17` → Start

**Vite/assets not loading:**
Run `npm run build` or keep `npm run dev` running in a separate terminal.

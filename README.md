# Work Order RS (Laravel 12)

Sistem Work Order untuk pelaporan kerusakan, tracking status, chat user-admin, export data, dan notifikasi WhatsApp (Fonnte).

## Pindah Hasil Push ke Windows

## 1) Prasyarat di Windows

- Git
- PHP 8.2+ (aktifkan extension: `pdo_mysql`, `mbstring`, `openssl`, `curl`, `fileinfo`, `zip`)
- Composer 2.x
- Node.js 20+ dan npm
- MySQL Server

## 2) Ambil Kode Terbaru

```bash
git clone https://github.com/rizalfadeli/workorder-rs.git
cd workorder-rs
git checkout WA
git pull origin WA
```

## 3) Install Dependency

```bash
composer install
npm install
```

## 4) Buat dan Atur `.env`

```bash
copy .env.example .env
php artisan key:generate
```

Edit `.env` minimal:

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=workorder_rs
DB_USERNAME=workorder_user
DB_PASSWORD=PasswordKuat123!

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=database

FONNTE_TOKEN=ISI_TOKEN_FONNTE
FONNTE_ENDPOINT=https://api.fonnte.com/send
```

## 5) Buat Database MySQL

Masuk MySQL lalu jalankan:

```sql
CREATE DATABASE IF NOT EXISTS workorder_rs CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'workorder_user'@'127.0.0.1' IDENTIFIED BY 'PasswordKuat123!';
GRANT ALL PRIVILEGES ON workorder_rs.* TO 'workorder_user'@'127.0.0.1';
FLUSH PRIVILEGES;
```

## 6) Migrasi dan Storage Link

```bash
php artisan config:clear
php artisan migrate
php artisan storage:link
```

## 7) Jalankan Aplikasi

Terminal 1:

```bash
php artisan serve
```

Terminal 2:

```bash
php artisan queue:listen --tries=1 --timeout=0
```

Terminal 3:

```bash
npm run dev
```

## 8) Verifikasi

- App: `http://127.0.0.1:8000`
- Test WA dummy: `http://127.0.0.1:8000/test-wa?target=08xxxxxxxxxx`

Jika error, cek log:

```bash
type storage\logs\laravel.log
```

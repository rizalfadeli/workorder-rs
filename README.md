# Work Order RS (Laravel 12)

Sistem Work Order untuk pelaporan kerusakan, tracking status, chat user-admin, export data, dan notifikasi WhatsApp via Fonnte.

## Fitur Utama

- Laporan Work Order publik dan user login
- Tracking kode Work Order
- Dashboard admin dan user
- Chat per Work Order
- Export Work Order selesai ke Excel
- Notifikasi WhatsApp otomatis saat Work Order dibuat

## Kebutuhan Sistem (Linux Mint)

- PHP 8.2+
- Composer 2.x
- Node.js 20+ dan npm
- MySQL/MariaDB atau SQLite
- Ekstensi PHP umum Laravel (`mbstring`, `xml`, `curl`, `sqlite3`/`mysql`, `bcmath`, `zip`)

## Setup Cepat (Linux Mint)

1. Install dependency backend dan frontend:

```bash
composer install
npm install
```

2. Buat file environment:

```bash
cp .env.example .env
php artisan key:generate
```

3. Atur database di `.env`.

Contoh SQLite:

```env
DB_CONNECTION=sqlite
```

Pastikan file DB ada:

```bash
mkdir -p database
touch database/database.sqlite
```

4. Jalankan migrasi:

```bash
php artisan migrate
```

5. Buat symlink storage:

```bash
php artisan storage:link
```

6. Atur WhatsApp Fonnte di `.env`:

```env
FONNTE_TOKEN=ISI_TOKEN_FONNTE
FONNTE_ENDPOINT=https://api.fonnte.com/send
```

7. Bersihkan cache config:

```bash
php artisan config:clear
```

8. Jalankan aplikasi:

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

## Catatan Migrasi dari Windows ke Linux Mint

- Path di Linux case-sensitive. Pastikan nama file dan import class huruf besar-kecilnya tepat.
- Jalankan ulang `php artisan storage:link` setelah pindah environment.
- Jika file upload gagal, cek hak akses:

```bash
chmod -R ug+rw storage bootstrap/cache
```

- Jika pernah copy dari Windows dan ada masalah line ending:

```bash
git config core.autocrlf input
```

- Jika `.env` lama terbawa dari Windows, cek lagi host DB, port, dan kredensial.

## Verifikasi Fitur WhatsApp

1. Device di dashboard Fonnte harus online.
2. Buat Work Order baru dari halaman public atau user.
3. Cek pesan masuk di nomor tujuan.
4. Jika gagal, cek log:

```bash
tail -f storage/logs/laravel.log
```

## Testing

```bash
php artisan test
```


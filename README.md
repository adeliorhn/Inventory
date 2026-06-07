# Adelio Inventory

Web app inventory barang berbasis Laravel, PostgreSQL Neon, Docker Compose, dan Traefik.

## Fitur

- Pencatatan master barang, stok awal, minimum stok, lokasi, serta mutasi barang masuk, keluar, dan penyesuaian.
- Cetak laporan stok dan riwayat mutasi dengan filter periode.
- Notifikasi stok rendah dan komunikasi internal antar tim.

## Konfigurasi Neon PostgreSQL

1. Salin `.env.example` menjadi `.env`.
2. Isi `DB_URL` dari connection string Neon, contoh:

```dotenv
DB_CONNECTION=pgsql
DB_URL=postgresql://USER:PASSWORD@HOST.neon.tech/DBNAME?sslmode=require
DB_SSLMODE=require
```

3. Isi `APP_KEY` dengan `php artisan key:generate` atau jalankan perintah container di bawah.

## Menjalankan Dengan Docker Compose

```bash
docker compose build
docker compose run --rm app php artisan key:generate
docker compose run --rm app php artisan migrate --seed
docker compose up -d
```

Aplikasi tersedia di `http://inventory.localhost`.
Dashboard Traefik tersedia di `http://localhost:8080`.

Jika ingin migrasi otomatis saat container start, set `RUN_MIGRATIONS=true` di `.env`.

## Rute Utama

- `/` untuk pencatatan barang, mutasi stok, notifikasi, dan komunikasi.
- `/laporan` untuk rekap dan filter laporan.
- `/laporan/cetak` untuk tampilan cetak laporan.

## Test

```bash
docker run --rm -v "$PWD:/app" -w /app composer:2 php artisan test
```

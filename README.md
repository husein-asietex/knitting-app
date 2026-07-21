# Knitting PT. Asietex Sinar Indopratama

Project ini dibuat menggunakan **Laravel**, **Livewire**, dan **PostgreSQL**. README ini berisi panduan untuk menjalankan project di local setelah melakukan clone dari repository GitHub.

---

## 1. Requirement

Pastikan perangkat local sudah memiliki aplikasi berikut:

- PHP 8.3 atau lebih
- Composer
- PostgreSQL
- Node.js dan NPM
- Git

Cek versi dengan perintah berikut:

```bash
php -v
composer -V
psql --version
node -v
npm -v
git --version
```

Untuk versi PHP yang dibutuhkan, lihat file `composer.json` pada bagian:

```json
"require": {
    "php": "..."
}
```

---

## 2. Clone Repository

Clone project dari GitHub:

```bash
git clone https://github.com/husein-asietex/knitting-app.git
```

Masuk ke folder project:

```bash
cd knitting-app
```

---

## 3. Install Dependency Backend

Install dependency Laravel menggunakan Composer:

```bash
composer install
```

Jika muncul error dependency, coba jalankan:

```bash
composer update
```

> Gunakan `composer update` hanya jika `composer install` gagal atau dependency memang perlu diperbarui.

---

## 4. Buat File Environment

Copy file `.env.example` menjadi `.env`:

### Linux / macOS

```bash
cp .env.example .env
```

### Windows PowerShell

```powershell
copy .env.example .env
```

---

## 5. Generate Application Key

Jalankan perintah berikut:

```bash
php artisan key:generate
```

Perintah ini akan mengisi nilai `APP_KEY` pada file `.env`.

---

## 6. Buat Database PostgreSQL

Masuk ke PostgreSQL:

```bash
psql -U postgres
```

Buat database baru:

```sql
CREATE DATABASE nama_database;
```

Keluar dari PostgreSQL:

```sql
\q
```

Contoh:

```sql
CREATE DATABASE asietex_knitting;
```

---

## 7. Konfigurasi Database di `.env`

Buka file `.env`, lalu sesuaikan konfigurasi database:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=asietex_knitting
DB_USERNAME=postgres
DB_PASSWORD=password_postgres_kamu
```

Keterangan:

- `DB_CONNECTION=pgsql` digunakan untuk PostgreSQL.
- `DB_HOST=127.0.0.1` digunakan jika database berjalan di komputer local.
- `DB_PORT=5432` adalah port default PostgreSQL.
- `DB_DATABASE` sesuaikan dengan nama database yang sudah dibuat.
- `DB_USERNAME` dan `DB_PASSWORD` sesuaikan dengan akun PostgreSQL local.

---

## 8. Jalankan Migration dan Seeder

Jalankan migration + seeder untuk membuat tabel beserta mengisi data dummy:

```bash
php artisan migrate --seed
```

Jika ingin reset database local dari awal:

```bash
php artisan migrate:fresh --seed
```

> Hati-hati: `migrate:fresh` akan menghapus semua tabel + data dan membuat ulang dari awal.

---

## 9. Install Dependency Frontend

Install dependency frontend:

```bash
npm install
```

Jalankan Vite untuk development:

```bash
npm run dev
```

Untuk build asset production:

```bash
npm run build
```

---

## 10. Jalankan Project Laravel

Buka terminal baru, lalu jalankan server Laravel:

```bash
php artisan serve
```

Secara default, project bisa diakses melalui:

```text
http://127.0.0.1:8000
```

Jika ingin menggunakan port lain:

```bash
php artisan serve --port=8080
```

Akses melalui:

```text
http://127.0.0.1:8080
```

---

## 11. Akun Login Default

Project memiliki seeder akun admin, sesuaikan informasi login berikut berdasarkan file seeder project:

Akun Super Admin:

```text
Username : superadmin
Password : password
```

Jika akun default belum tersedia, tambahkan data user melalui seeder/database sesuai kebutuhan project.

---

## 12. Troubleshooting

### Error: `could not find driver` atau `PDOException`

Pastikan extension PostgreSQL untuk PHP sudah aktif.

Cek extension:

```bash
php -m | grep pgsql
```

Jika di Windows, aktifkan extension berikut di file `php.ini`:

```ini
extension=pdo_pgsql
extension=pgsql
```

Setelah itu restart terminal atau web server.

---

### Error: database tidak terkoneksi

Periksa kembali konfigurasi `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=asietex_knitting
DB_USERNAME=postgres
DB_PASSWORD=password_postgres_kamu
```

Lalu jalankan:

```bash
php artisan config:clear
php artisan cache:clear
```

---

### Error: halaman kosong atau asset tidak muncul

Pastikan dependency frontend sudah diinstall dan Vite berjalan:

```bash
npm install
npm run dev
```

Jika untuk production, jalankan:

```bash
npm run build
```

---

### Error: permission denied pada folder `storage` atau `bootstrap/cache`

Untuk Linux / macOS:

```bash
chmod -R 775 storage bootstrap/cache
```

Untuk Windows, pastikan folder project tidak berada di lokasi yang membutuhkan akses administrator.

---

### Error setelah mengubah file `.env`

Jalankan:

```bash
php artisan optimize:clear
```

Lalu jalankan ulang server:

```bash
php artisan serve
```

---

## 13. Alur Cepat Instalasi

Gunakan perintah berikut setelah clone repository:

```bash
git clone https://github.com/husein-asietex/knitting-app
cd asietex-knitting
composer install
cp .env.example .env
php artisan key:generate
npm install
```

Edit file `.env` untuk PostgreSQL:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=asietex_knitting
DB_USERNAME=postgres
DB_PASSWORD=password_postgres_kamu
```

Lalu jalankan:

```bash
php artisan migrate --seed
npm run dev
php artisan serve
```

Buka aplikasi:

```text
http://127.0.0.1:8000
```

---

## 14. Catatan Developer

- Jangan commit file `.env` ke GitHub.
- Gunakan `.env.example` sebagai contoh konfigurasi environment.
- Jalankan `php artisan optimize:clear` jika ada perubahan konfigurasi.
- Pastikan PostgreSQL berjalan sebelum menjalankan migration.
- Pastikan terminal untuk `npm run dev` tetap aktif saat development.

---

## 15. Referensi Dokumentasi

- Laravel Documentation: https://laravel.com/docs
- Laravel Vite Documentation: https://laravel.com/docs/vite
- Livewire Documentation: https://livewire.laravel.com/docs
- PostgreSQL Documentation: https://www.postgresql.org/docs/
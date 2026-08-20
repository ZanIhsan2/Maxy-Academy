# Sales Management System

Aplikasi web untuk membantu pengelolaan data master dan proses purchasing. Proyek ini dibuat menggunakan Laravel 12, Blade, Tailwind CSS, Vite, dan MySQL.

## Fitur Utama

### Autentikasi dan Profil

- Registrasi akun dan login.
- Logout dan pengelolaan profil pengguna.
- Dashboard yang hanya dapat diakses pengguna terautentikasi.
- Verifikasi email menggunakan fitur autentikasi Laravel Breeze.

### Manajemen Produk

Modul produk tersedia di halaman `/products` dan dapat digunakan oleh pengguna yang sudah login.

- Menampilkan daftar produk.
- Menambahkan produk baru.
- Mengubah data produk langsung dari tabel.
- Menghapus produk.
- Validasi SKU agar bersifat unik.
- Pengelolaan nama produk, harga, stok, dan deskripsi.
- Pencarian, pengurutan, pagination, dan pengaturan jumlah data per halaman menggunakan DataTables.
- Export data menggunakan DataTables Buttons Extension ke:
    - Copy
    - CSV
    - Excel
    - Print

### Manajemen Kategori

Modul kategori tersedia di halaman `/categories` dan menjadi master untuk data produk.

- CRUD kategori: tambah, ubah, dan hapus.
- DataTables server-side dengan pencarian, sorting, dan pagination.
- DataTables Buttons Extension untuk Copy, CSV, Excel, dan Print.
- Validasi nama kategori agar unik.
- Relasi `Category hasMany Product` dan `Product belongsTo Category`.
- Dropdown kategori pada form tambah dan edit Product.
- Product DataTables menampilkan nama kategori melalui `LEFT JOIN`.

### Purchase Order

Modul Purchase Order dilindungi oleh autentikasi dan permission `manage-purchase`.

- Melihat daftar Purchase Order.
- Membuat Purchase Order baru.
- Memilih vendor.
- Menambahkan beberapa detail barang dalam satu Purchase Order.
- Menyimpan kuantitas, harga unit, dan total harga setiap barang.
- Melihat detail Purchase Order beserta vendor dan itemnya.
- Penyimpanan header dan detail transaksi menggunakan database transaction.

### Role dan Permission

Proyek menggunakan `spatie/laravel-permission` untuk membatasi akses fitur.

Permission yang tersedia:

- `manage-master`
- `manage-purchase`
- `manage-sales`

Role yang disediakan oleh `RolePermissionSeeder`:

- `Admin`: memiliki semua permission.
- `Purchasing`: memiliki permission `manage-master` dan `manage-purchase`.

## Teknologi

- PHP 8.2 atau lebih baru
- Laravel 12
- MySQL
- Blade Template Engine
- Laravel Breeze
- Tailwind CSS
- Vite
- Alpine.js
- DataTables 2
- DataTables Buttons Extension
- Spatie Laravel Permission

Catatan prompt, review kode, debugging, dan refactoring AI tersedia di [AI_REVIEW.md](AI_REVIEW.md).

## Persyaratan Sistem

Pastikan perangkat sudah memiliki:

- PHP 8.2+
- Composer
- Node.js dan npm
- MySQL atau MariaDB
- Web server lokal seperti XAMPP, Laragon, atau PHP Artisan Server

## Instalasi

1. Masuk ke folder proyek:

    ```bash
    cd sales
    ```

2. Install dependency PHP:

    ```bash
    composer install
    ```

3. Install dependency frontend:

    ```bash
    npm install
    ```

4. Buat file environment:

    ```bash
    copy .env.example .env
    ```

    Pada Linux atau macOS, gunakan `cp .env.example .env`.

5. Generate application key:

    ```bash
    php artisan key:generate
    ```

6. Buat database MySQL, kemudian sesuaikan konfigurasi berikut di `.env`:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=db_sales
    DB_USERNAME=root
    DB_PASSWORD=
    ```

7. Jalankan migration:

    ```bash
    php artisan migrate
    ```

8. Isi data permission, role, dan produk dummy:

    ```bash
    php artisan db:seed --class=RolePermissionSeeder
    php artisan db:seed --class=ProductSeeder
    ```

    Seeder produk dapat dijalankan berulang kali karena menggunakan SKU sebagai kunci unik.

9. Build asset frontend:

    ```bash
    npm run build
    ```

10. Jalankan aplikasi:

    ```bash
    php artisan serve
    ```

    Buka `http://127.0.0.1:8000` di browser.

    Untuk pengembangan frontend dengan hot reload, gunakan terminal lain:

    ```bash
    npm run dev
    ```

## Route Utama

| Method | URL                      | Keterangan            | Akses                         |
| ------ | ------------------------ | --------------------- | ----------------------------- |
| GET    | `/`                      | Halaman awal          | Publik                        |
| GET    | `/dashboard`             | Dashboard             | Login dan email terverifikasi |
| GET    | `/products`              | Daftar produk         | Login                         |
| POST   | `/products`              | Tambah produk         | Login                         |
| PUT    | `/products/{product}`    | Perbarui produk       | Login                         |
| DELETE | `/products/{product}`    | Hapus produk          | Login                         |
| GET    | `/categories`            | Daftar kategori       | Login                         |
| GET    | `/categories/data`       | DataTables kategori   | Login                         |
| POST   | `/categories`            | Tambah kategori       | Login                         |
| PUT    | `/categories/{category}` | Perbarui kategori     | Login                         |
| DELETE | `/categories/{category}` | Hapus kategori        | Login                         |
| GET    | `/purchase-order`        | Daftar Purchase Order | Login dan `manage-purchase`   |
| GET    | `/purchase-order/create` | Form Purchase Order   | Login dan `manage-purchase`   |
| POST   | `/purchase-order`        | Simpan Purchase Order | Login dan `manage-purchase`   |
| GET    | `/purchase-order/{id}`   | Detail Purchase Order | Login dan `manage-purchase`   |

## Struktur Folder Penting

```text
app/
├── Http/Controllers/
│   ├── CategoriesController.php
│   ├── ProductsController.php
│   └── PurchaseOrderController.php
└── Models/
    ├── Category.php
    └── Product.php

database/
├── migrations/
│   ├── *_create_categories_table.php
│   ├── *_add_category_id_to_products_table.php
│   └── *_create_products_table.php
└── seeders/
    ├── CategorySeeder.php
    ├── ProductSeeder.php
    └── RolePermissionSeeder.php

resources/views/
├── categories/index.blade.php
├── products/index.blade.php
└── purchasing/

routes/
└── web.php
```

## Struktur Tabel Products

| Kolom         | Tipe             | Keterangan       |
| ------------- | ---------------- | ---------------- |
| `id`          | bigint           | Primary key      |
| `sku`         | varchar          | Kode produk unik |
| `name`        | varchar          | Nama produk      |
| `price`       | decimal          | Harga produk     |
| `stock`       | unsigned integer | Jumlah stok      |
| `description` | text nullable    | Deskripsi produk |
| `created_at`  | timestamp        | Waktu dibuat     |
| `updated_at`  | timestamp        | Waktu diperbarui |

Kolom `products.category_id` adalah foreign key nullable ke `categories.id`. Jika kategori dihapus, produk tetap ada dan `category_id` menjadi `null`.

## Struktur Tabel Categories

| Kolom         | Tipe          | Keterangan         |
| ------------- | ------------- | ------------------ |
| `id`          | bigint        | Primary key        |
| `name`        | varchar       | Nama kategori unik |
| `description` | text nullable | Deskripsi kategori |
| `created_at`  | timestamp     | Waktu dibuat       |
| `updated_at`  | timestamp     | Waktu diperbarui   |

## Pengujian

Jalankan test dengan perintah:

```bash
php artisan test
```

## Lisensi

Proyek ini menggunakan framework Laravel yang dirilis di bawah lisensi MIT.

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

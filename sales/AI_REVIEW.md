# AI Review Documentation - Project Products & Categories

## Konteks Proyek

Fitur Category ditambahkan ke proyek `sales`, yaitu aplikasi Laravel untuk pengelolaan Products dan Purchase Order. Tujuan pekerjaan ini bukan hanya menghasilkan kode, tetapi juga meninjau hasil AI, menguji asumsi, dan memperbaiki bagian yang berisiko menimbulkan bug.

## Fitur 1: Migration dan Model Category

### 1. Prompt yang Digunakan

> Buatkan model Category dan migration Laravel untuk tabel categories. Tabel minimal memiliki id, name, description, dan timestamps. Description boleh kosong dan name harus unik.

### 2. Kode yang Dihasilkan (Ringkasan Raw Output AI)

```php
Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->string('name')->unique();
    $table->text('description')->nullable();
    $table->timestamps();
});
```

Model menggunakan `$fillable` untuk `name` dan `description`.

### 3. Hasil Review

- Nama tabel `categories` mengikuti konvensi plural Laravel.
- `name` dibuat unik agar kategori duplikat tidak tersimpan.
- `description` dibuat `nullable` karena deskripsi bukan data wajib.
- Mass assignment dibatasi melalui `$fillable`.
- Validasi request tetap diperlukan di controller; migration saja tidak cukup untuk melindungi input aplikasi.

### 4. Modifikasi yang Dilakukan

- Menambahkan unique validation pada controller dengan pengecualian ID saat update.
- Menambahkan `Category::products()` dengan relasi `hasMany`.

### 5. Verifikasi / Status

- File migration, model, dan controller lolos diagnostik editor.
- Migration perlu dijalankan pada database lokal dengan `php artisan migrate`.

## Fitur 2: CRUD Category dan DataTables Server-side

### 1. Prompt yang Digunakan

> Buatkan CategoriesController dan route CRUD Category. Halaman categories.index harus memiliki form tambah, edit, hapus, dan DataTables server-side dengan endpoint JSON yang mengembalikan draw, recordsTotal, recordsFiltered, dan data.

### 2. Kode yang Dihasilkan (Ringkasan Raw Output AI)

```php
public function data(Request $request): JsonResponse
{
    $query = Category::query();
    $search = $request->input('search.value', '');

    if ($search !== '') {
        $query->where('name', 'like', "%{$search}%");
    }

    return response()->json([
        'draw' => (int) $request->input('draw', 0),
        'recordsTotal' => Category::count(),
        'recordsFiltered' => $query->count(),
        'data' => $query->skip($start)->take($length)->get(),
    ]);
}
```

### 3. Hasil Review

- DataTables server-side membutuhkan format response khusus agar pagination dan jumlah record bekerja.
- Nilai `draw`, `start`, `length`, dan order dari client tidak boleh dipercaya sebagai nama kolom SQL secara langsung.
- Karena itu, kolom sorting dipetakan ke daftar kolom yang diizinkan.
- Pencarian dilakukan pada `name` dan `description`.
- Endpoint diletakkan di balik middleware `auth` agar data master tidak terbuka untuk pengguna anonim.
- Buttons Extension digunakan untuk Copy, CSV, Excel, dan Print.

### 4. Modifikasi yang Dilakukan

- Menggunakan native Laravel Query Builder/Eloquent, bukan menambahkan package baru, karena proyek belum memiliki package server-side DataTables seperti Yajra.
- Membatasi kolom `orderBy` melalui whitelist.
- Menambahkan escape HTML pada data yang dirender ke form edit oleh JavaScript.
- Menggunakan token CSRF dari meta tag untuk seluruh form dinamis.

### 5. Verifikasi / Status

- Endpoint `GET /categories/data` telah didaftarkan.
- View menggunakan `processing: true` dan `serverSide: true`.
- File PHP terkait lolos diagnostik editor.
- Perlu diuji setelah migration dijalankan menggunakan browser dan database proyek.

## Fitur 3: Relasi Product dan Category

### 1. Prompt yang Digunakan

> Tambahkan migration baru untuk products.category_id sebagai foreign key ke categories.id. Tambahkan belongsTo pada Product dan hasMany pada Category. Produk lama tetap boleh tampil jika belum memiliki kategori.

### 2. Kode yang Dihasilkan (Ringkasan Raw Output AI)

```php
$table->foreignId('category_id')
    ->nullable()
    ->constrained('categories')
    ->nullOnDelete();
```

```php
// Product
public function category(): BelongsTo
{
    return $this->belongsTo(Category::class);
}

// Category
public function products(): HasMany
{
    return $this->hasMany(Product::class);
}
```

### 3. Hasil Review

- Foreign key menjaga integritas referensi antar tabel.
- `nullable()` dipilih agar migration aman untuk data Product lama yang belum memiliki kategori.
- `nullOnDelete()` mencegah penghapusan kategori ikut menghapus produk.
- Relasi Eloquent dibuat eksplisit dan menggunakan return type.

### 4. Modifikasi yang Dilakukan

- Menambahkan migration terpisah setelah migration `products` dan `categories`.
- Menambahkan `category_id` ke `$fillable` Product.
- Menambahkan validasi `exists:categories,id` saat membuat dan mengubah Product.

### 5. Verifikasi / Status

- Model dan migration tidak memiliki error diagnostik.
- Relasi menggunakan `belongsTo` dan `hasMany` sesuai kebutuhan.

## Fitur 4: Product DataTables dengan JOIN Category

### 1. Prompt yang Digunakan

> Ubah ProductController agar tabel Product menggunakan DataTables server-side. Gunakan LEFT JOIN categories sehingga nama kategori tampil, bukan category_id. Hindari kolom ambigu ketika melakukan search dan sorting.

### 2. Kode yang Dihasilkan (Ringkasan Raw Output AI)

```php
$query = Product::query()
    ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
    ->select('products.*', 'categories.name as category_name');
```

### 3. Hasil Review

- `LEFT JOIN` dipilih agar Product tanpa kategori tetap muncul.
- `categories.name` diberi alias `category_name` agar tidak bentrok dengan `products.name`.
- Search harus menggunakan nama tabel lengkap, misalnya `products.name` dan `categories.name`.
- Sorting juga memakai whitelist kolom yang memenuhi syarat agar input DataTables tidak dapat menjadi SQL identifier sembarang.
- Query memakai pagination database melalui `skip` dan `take`, bukan mengambil seluruh tabel ke memory.

### 4. Modifikasi yang Dilakukan

- Method `data()` ditambahkan pada `ProductsController`.
- View Product diubah dari data Blade statis menjadi DataTables server-side.
- Kolom kategori menampilkan `category_name` dari hasil JOIN.
- Form tambah dan edit Product diberi dropdown kategori.
- Data lama tetap terlihat walaupun `category_id` masih null.

### 5. Verifikasi / Status

- Route `GET /products/data` telah didaftarkan.
- Query menggunakan `LEFT JOIN` dan kolom SQL yang qualified.
- Controller dan model lolos diagnostik editor.
- Pengujian runtime membutuhkan database dengan migration terbaru.

## Fitur 5: Seeder dan Data Dummy

### 1. Prompt yang Digunakan

> Buatkan CategorySeeder dan sesuaikan ProductSeeder agar beberapa Product memiliki category_id. Seeder aman dijalankan lebih dari satu kali.

### 2. Hasil Review

- `upsert` dengan key `name` untuk Category dan `sku` untuk Product mencegah duplikasi data dummy.
- ProductSeeder mengambil ID kategori berdasarkan nama, bukan mengasumsikan ID tertentu.
- DatabaseSeeder memanggil CategorySeeder sebelum ProductSeeder.

### 3. Modifikasi yang Dilakukan

- Menambahkan `CategorySeeder` ke `DatabaseSeeder`.
- Menambahkan `category_id` ke data dummy Product.
- Menambahkan `category_id` ke daftar kolom yang diperbarui oleh Product `upsert`.

### 4. Verifikasi / Status

Urutan seed yang benar:

```bash
php artisan db:seed --class=CategorySeeder
php artisan db:seed --class=ProductSeeder
```

Atau gunakan `php artisan db:seed` setelah `DatabaseSeeder` terdaftar.

## Kendala dan Debugging

1. Editor awalnya membaca Blade expression seperti `@json(...)` dan `route(...)` di dalam JavaScript sebagai sintaks JavaScript biasa.
2. Data endpoint dan daftar kategori dipindahkan ke atribut `data-*` pada elemen table.
3. JavaScript membaca data menggunakan `dataset`, sehingga template Blade tidak bercampur dengan object JavaScript.
4. Form dinamis memakai token CSRF dari `meta[name="csrf-token"]`.

## Status Akhir

- [x] Model dan migration Category
- [x] CRUD Category
- [x] DataTables server-side Category
- [x] DataTables Buttons Extension
- [x] Migration foreign key Product-Category
- [x] Relasi `belongsTo` dan `hasMany`
- [x] Dropdown Category pada form tambah Product
- [x] Dropdown Category pada form edit Product
- [x] Product DataTables server-side dengan `LEFT JOIN`
- [x] Seeder Category dan Product
- [x] Dokumentasi proses review AI
- [ ] Menjalankan migration dan pengujian browser pada database lokal

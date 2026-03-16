# Changelog

## Step 5 - Case Study 1 & 2: Wilayah Dropdown & POS - 2026-03-16

### Added

- **Database Migrations**
  - Added `kode` column to `barangs` table for POS lookup
  - Created `penjualans` table with fields: id, timestamp, total
  - Created `penjualan_details` table with fields: id, id_penjualan (FK), id_barang (FK), jumlah, subtotal

- **Database Seeders**
  - Created `BarangSeeder` with 12 sample records including electronics items

- **Eloquent Models**
  - `RegProvince` - table: reg_provinces
  - `RegRegency` - table: reg_regencies
  - `RegDistrict` - table: reg_districts
  - `RegVillage` - table: reg_villages
  - `Penjualan` - table: penjualans
  - `PenjualanDetail` - table: penjualan_details
  - Updated `Barang` model to use 'kode' as primary key

- **Case Study 1: Indonesian Administrative Region Dropdown**
  - Created `WilayahController` with methods:
    - `getProvinsi()` - returns all provinces
    - `getKota($id)` - returns regencies by province_id
    - `getKecamatan($id)` - returns districts by regency_id
    - `getKelurahan($id)` - returns villages by district_id
  - Created two view versions:
    - `resources/views/dashboard/wilayah/jquery.blade.php` - using jQuery AJAX
    - `resources/views/dashboard/wilayah/axios.blade.php` - using Axios
  - Both versions include:
    - Cascading dropdowns (Provinsi → Kota → Kecamatan → Kelurahan)
    - "Memuat..." loading text while fetching data
    - SweetAlert2 error notifications
    - Dependent dropdowns disabled until parent is selected

- **Case Study 2: Point of Sales (POS) Page**
  - Created `PosController` with methods:
    - `index()` - returns POS view
    - `findBarang($kode)` - finds barang by kode
    - `bayar(Request $req)` - saves transaction to database
  - Created two view versions:
    - `resources/views/dashboard/pos/jquery.blade.php` - using jQuery AJAX
    - `resources/views/dashboard/pos/axios.blade.php` - using Axios
  - Both versions include:
    - Form with Kode, Nama, Harga, Jumlah inputs
    - Enter key triggers barang lookup
    - Add items to cart table (update qty if exists, add new if not)
    - Editable quantity in table rows
    - Delete button per row
    - Real-time total calculation
    - Bayar button saves to database with transaction details
    - SweetAlert2 notifications for success/error

- **Routes Added**
  - `/wilayah` - wilayah index
  - `/wilayah/jquery` - wilayah jQuery version
  - `/wilayah/axios` - wilayah Axios version
  - `/wilayah/provinsi` - get provinces
  - `/wilayah/kota/{id}` - get cities
  - `/wilayah/kecamatan/{id}` - get districts
  - `/wilayah/kelurahan/{id}` - get villages
  - `/pos` - pos index
  - `/pos/jquery` - pos jQuery version
  - `/pos/axios` - pos Axios version
  - `/pos/barang/{kode}` - find barang
  - `/pos/bayar` - process payment

- **Sidebar Updated**
  - Added navigation links for:
    - Wilayah (AJAX) → routes to wilayah.jquery
    - Wilayah (AXIOS) → routes to wilayah.axios
    - POS (AJAX) → routes to pos.jquery
    - POS (AXIOS) → routes to pos.axios

- **Files Created:**
  - `database/migrations/2026_03_16_000001_add_kode_to_barangs_table.php`
  - `database/migrations/2026_03_16_000002_create_penjualans_table.php`
  - `database/migrations/2026_03_16_000003_create_penjualan_details_table.php`
  - `database/seeders/BarangSeeder.php`
  - `app/Models/RegProvince.php`
  - `app/Models/RegRegency.php`
  - `app/Models/RegDistrict.php`
  - `app/Models/RegVillage.php`
  - `app/Models/Penjualan.php`
  - `app/Models/PenjualanDetail.php`
  - `app/Http/Controllers/WilayahController.php`
  - `app/Http/Controllers/PosController.php`
  - `resources/views/dashboard/wilayah/jquery.blade.php`
  - `resources/views/dashboard/wilayah/axios.blade.php`
  - `resources/views/dashboard/pos/jquery.blade.php`
  - `resources/views/dashboard/pos/axios.blade.php`

- **Files Modified:**
  - `app/Models/Barang.php`
  - `database/seeders/DatabaseSeeder.php`
  - `routes/web.php`
  - `resources/views/layouts/partials/sidebar.blade.php`

## Step 1 - Submit Button Spinner / Loader - 2026-03-10

### Changed

- **Submit Button Spinner (jQuery)**
  - Semua form CRUD (Kategori, Buku, Barang - create & edit) telah dilengkapi dengan spinner/loader pada button submit menggunakan jQuery
  - Menambahkan atribut `required` pada semua input yang diperlukan untuk validasi HTML5
  - Mengubah button `type="submit"` menjadi `type="button"` dan memindahkan button ke luar tag `<form>` untuk mencegah submit langsung
  - Implementasi validasi menggunakan `checkValidity()` dan `reportValidity()` HTML5 melalui JavaScript
  - Button berubah menjadi spinner (Bootstrap `spinner-border`) dan disabled saat proses submit berjalan
  - Mencegah double submit: button di-disable setelah klik pertama sehingga user tidak bisa klik berulang kali
  - Memberikan feedback visual bahwa proses sedang berjalan dengan teks "Processing..."

- **Files Modified:**
  - `resources/views/dashboard/kategori/create.blade.php`
  - `resources/views/dashboard/kategori/edit.blade.php`
  - `resources/views/dashboard/buku/create.blade.php`
  - `resources/views/dashboard/buku/edit.blade.php`
  - `resources/views/dashboard/barang/create.blade.php`
  - `resources/views/dashboard/barang/edit.blade.php`

## Step 2 - Latihan HTML Table & DataTables - 2026-03-10

### Added

- **Latihan HTML Table (Page 1)**
  - Form barang front-end based (session only) validation menggunakan HTML5 dan jQuery
  - Render data list ke dalam tag HTML `<table>` biasa
  - Menampilkan total *data count* dan handling state *empty table*
  - Data yang disubmit di-insert langsung ke dalam tabel menggunakan `.append()`

- **Latihan DataTables (Page 2)**
  - Mengintegrasikan plugin `DataTables` (jQuery) versi `1.13.7`
  - Menyediakan form barang front-end dengan validasi sama seperti Page 1
  - Data disubmit lalu dimasukkan ke DataTables menggunakan API `.row.add().draw(false)`
  - Translation dan pagination aktif otomatis untuk komponen tabel

- **Files Modified/Created:**
  - `routes/web.php`
  - `app/Http/Controllers/DashboardController.php`
  - `resources/views/layouts/partials/sidebar.blade.php`
  - `resources/views/dashboard/latihan/html-table.blade.php`
  - `resources/views/dashboard/latihan/datatables.blade.php`

## Step 3 - Interactive Row Update & Delete - 2026-03-10

### Added

- **Update (U) & Delete (D) Functionality via Modal**
  - Mengimplementasikan klik baris tabel (dengan `cursor: pointer` pada *hover*) untuk memunculkan modal *update* & *delete* pada halaman HTML Table dan DataTables
  - Modal berisi input `ID Barang` (readonly), `Nama Barang` (required), dan `Harga Barang` (required) yang terisi otomatis
  - Aksi **Ubah** (Update) memvalidasi form dan memperbarui baris terkait di tabel secara *in-memory*
  - Aksi **Hapus** (Delete) menghapus baris terkait di tabel secara langsung
  - *No database interaction*, sepenuhnya *front-end DOM manipulation*

- **Page 1: HTML Table**
  - DOM manipulation melalui `jQuery` untuk mengubah DOM `tr` langsung (termasuk `.data()`) dan memperbarui tampilan

- **Page 2: DataTables**
  - Integrasi DataTables API (`.row().data()` dan `.row().remove()`) ditambah `.draw(false)` agar state pencarian, paginasi dan *sorting* tetap sinkron

- **Files Modified:**
  - `resources/views/dashboard/jquery/html-table.blade.php`
  - `resources/views/dashboard/jquery/datatables.blade.php` 

## Step 4 - JQuery Select & Select2 - 2026-03-10

### Added

- **JQuery Select Page**
  - Membuat halaman baru yang berisi dua card berdampingan untuk demonstrasi Native HTML Select dan Select2 Plugin
  - Fitur tambah kota secara dinamis: input teks divalidasi tidak boleh kosong sebelum opsi kota ditambahkan ke elemen `<select>`
  - Update realtime tulisan "Kota Terpilih" setiap kali opsi pada dropdown berubah
  - Implementasi Native Select pada Card 1 menggunakan fungsi jQuery dasar (`.append()`, `.change()`)
  - Implementasi Select2 Plugin pada Card 2 yang menggabungkan fitur pencarian bawaan Select2 dan penambahan opsi dinamis via API
  - *No database interaction*, sepenuhnya menggunakan DOM manipulation (*in-memory*)

- **Files Modified/Created:**
  - `routes/web.php`
  - `app/Http/Controllers/DashboardController.php`
  - `resources/views/layouts/partials/sidebar.blade.php`
  - `resources/views/dashboard/jquery/select.blade.php`
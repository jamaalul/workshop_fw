# CHANGELOG - WORKSHOP PENGEMBANGAN WEB BERBASIS FRAMEWORK

Repository: https://github.com/jamaalul/workshop_fw.git

---

## 📦 1. Inisialisasi Laravel

### Instalasi Framework
- Membuat project Laravel baru menggunakan Composer
- Menggunakan Laravel versi 12.0 dengan PHP 8.2
- Mengkonfigurasi environment file (`.env`) untuk koneksi database
- Setup struktur folder standar Laravel (app, resources, routes, database, public)

### Dependencies Utama
- **Laravel Framework**: ^12.0 - Framework utama aplikasi
- **Laravel Tinker**: ^2.10.1 - REPL untuk debugging
- **Laravel UI**: ^4.6 - Package untuk scaffolding UI dan autentikasi

---

## 🎨 2. Migrasi Asset Template ke Public Laravel

### Pemindahan File Template
- Memindahkan seluruh asset template HTML/CSS/JS ke folder `public/assets/`
- Struktur folder assets mencakup:
  - CSS stylesheets untuk styling halaman
  - JavaScript files untuk interaktivitas
  - Images dan icons
  - Vendor libraries (Bootstrap, jQuery, dll)
  
### Integrasi dengan Laravel
- Mengupdate path referensi asset menggunakan helper `asset()` Laravel
- Memastikan semua file CSS, JS, dan gambar dapat diakses melalui URL public
- Mengkonfigurasi `.htaccess` untuk routing yang benar

**Total Files**: 119+ file asset dipindahkan ke `public/assets/`

---

## 🏗️ 3. Membuat Master Layout

### Layout Utama (`layouts/app.blade.php`)
- Membuat master layout untuk halaman frontend/landing page
- Mengintegrasikan asset template (CSS, JS) menggunakan Blade syntax
- Menyiapkan section untuk:
  - `@yield('title')` - Dynamic page title
  - `@yield('content')` - Main content area
  - Meta tags untuk SEO
  - Link ke stylesheet dan script

### Layout Dashboard (`layouts/dashboard.blade.php`)
- Membuat layout khusus untuk area dashboard/admin
- Mengintegrasikan komponen navbar, sidebar, dan footer
- Menyiapkan container untuk konten dashboard
- Mengatur struktur responsive untuk tampilan mobile dan desktop

### Blade Template Engine
- Memanfaatkan fitur Blade seperti `@extends`, `@section`, `@yield`
- Implementasi template inheritance untuk reusability
- Menggunakan `@include` untuk partial views

**Files Created**:
- `resources/views/layouts/app.blade.php`
- `resources/views/layouts/dashboard.blade.php`

---

## 🧩 4. Membuat Komponen Navbar, Footer, dan Sidebar

### Navbar Component (`layouts/partials/navbar.blade.php`)
- Membuat navigation bar untuk dashboard
- Menampilkan logo aplikasi dan nama user yang login
- Implementasi dropdown menu untuk user profile
- Tombol logout dengan form POST ke route logout
- Responsive design dengan hamburger menu untuk mobile

### Footer Component (`layouts/partials/footer.blade.php`)
- Membuat footer untuk dashboard
- Menampilkan copyright dan informasi aplikasi
- Styling konsisten dengan template

### Sidebar Component (`layouts/partials/sidebar.blade.php`)
- Membuat sidebar navigation untuk menu dashboard
- Menu items yang dibuat:
  - **Dashboard/Home** - Halaman utama dashboard
  - **Kategori** - Menu manajemen kategori buku
  - **Buku** - Menu manajemen data buku
- Implementasi active state untuk menu yang sedang dibuka
- Icon untuk setiap menu item
- Collapsible sidebar untuk responsive design

### Component Integration
- Menggunakan `@include` directive untuk memasukkan komponen ke layout
- Memastikan komponen dapat digunakan kembali (reusable)
- Styling yang konsisten antar komponen

**Files Created**:
- `resources/views/layouts/partials/navbar.blade.php`
- `resources/views/layouts/partials/footer.blade.php`
- `resources/views/layouts/partials/sidebar.blade.php`

---

## 🏠 5. Membuat View Dashboard dan Routing

### Welcome Page (`welcome.blade.php`)
- Membuat halaman landing page/homepage
- Extends dari layout `app.blade.php`
- Menampilkan informasi aplikasi perpustakaan
- Call-to-action untuk login/register

### Dashboard Main View (`dashboard/main.blade.php`)
- Membuat halaman utama dashboard setelah login
- Extends dari layout `dashboard.blade.php`
- Menampilkan statistik atau informasi ringkasan
- Widget untuk quick access ke fitur utama

### Dashboard Controller (`app/Http/Controllers/DashboardController.php`)
- Membuat controller untuk handle request dashboard
- Method `index()` - Menampilkan halaman utama dashboard
- Method `kategori()` - Menampilkan halaman list kategori
- Method `buku()` - Menampilkan halaman list buku
- Mengambil data dari database untuk ditampilkan

### Routing Configuration (`routes/web.php`)
- Route untuk homepage: `GET /` → welcome page
- Route untuk dashboard: `GET /home` → dashboard.index
- Route untuk kategori: `GET /kategori` → dashboard.kategori
- Route untuk buku: `GET /buku` → dashboard.buku
- Menggunakan named routes untuk kemudahan referensi

**Files Created**:
- `resources/views/welcome.blade.php`
- `resources/views/dashboard/main.blade.php`
- `app/Http/Controllers/DashboardController.php`

**Files Modified**:
- `routes/web.php` - Menambahkan route definitions

---

## 🔐 6. Membuat Authentication System

### Laravel UI Authentication
- Install package `laravel/ui` via Composer
- Menjalankan command: `php artisan ui bootstrap --auth`
- Generate scaffolding untuk authentication:
  - Login functionality
  - Register functionality
  - Password reset functionality
  - Email verification

### Auth Controllers (Generated)
**Directory**: `app/Http/Controllers/Auth/`
- `LoginController.php` - Handle login process
- `RegisterController.php` - Handle user registration
- `ForgotPasswordController.php` - Handle password reset request
- `ResetPasswordController.php` - Handle password reset
- `ConfirmPasswordController.php` - Handle password confirmation
- `VerificationController.php` - Handle email verification

### Auth Views (Generated)
**Directory**: `resources/views/auth/`
- `login.blade.php` - Login form
- `register.blade.php` - Registration form
- `passwords/email.blade.php` - Request password reset form
- `passwords/reset.blade.php` - Reset password form
- `passwords/confirm.blade.php` - Confirm password form
- `verify.blade.php` - Email verification notice

### Authentication Routes
- `Auth::routes()` - Generate semua route untuk authentication
- Route untuk login, logout, register, password reset
- Middleware `auth` untuk protect route yang memerlukan login

### User Model
- Model `User.php` sudah tersedia secara default
- Menggunakan trait `Authenticatable`
- Fillable fields: name, email, password
- Password hashing otomatis

**Files Created**:
- 6 Auth Controllers
- 6 Auth Views

**Files Modified**:
- `routes/web.php` - Menambahkan `Auth::routes()`

---

## 🗄️ 7. Membuat Database: Tabel dan Model

### Migration Kategori (`2026_02_16_123843_create_kategoris_table.php`)
**Struktur Tabel `kategoris`**:
- `id` - Primary key (auto increment)
- `nama_kategori` - VARCHAR - Nama kategori buku
- `timestamps` - created_at & updated_at

### Migration Buku (`2026_02_16_123853_create_bukus_table.php`)
**Struktur Tabel `bukus`**:
- `id` - Primary key (auto increment)
- `kode` - VARCHAR - Kode unik buku
- `judul` - VARCHAR - Judul buku
- `pengarang` - VARCHAR - Nama pengarang
- `kategori_id` - Foreign key ke tabel kategoris
- `timestamps` - created_at & updated_at

**Relasi Database**:
- Foreign key constraint: `kategori_id` references `kategoris(id)`
- ON DELETE CASCADE - Jika kategori dihapus, buku terkait juga terhapus

### Model Kategori (`app/Models/Kategori.php`)
- Extends Eloquent Model
- Fillable: `nama_kategori`
- Timestamps disabled: `public $timestamps = false`
- Relationship: `hasMany(Buku::class)` - Satu kategori punya banyak buku

### Model Buku (`app/Models/Buku.php`)
- Extends Eloquent Model
- Fillable: `kode`, `judul`, `pengarang`, `kategori_id`
- Timestamps enabled (default)
- Relationship: `belongsTo(Kategori::class)` - Buku milik satu kategori

### Running Migrations
- Command: `php artisan migrate`
- Membuat tabel di database sesuai schema yang didefinisikan

**Files Created**:
- `database/migrations/2026_02_16_123843_create_kategoris_table.php`
- `database/migrations/2026_02_16_123853_create_bukus_table.php`
- `app/Models/Kategori.php`
- `app/Models/Buku.php`

---

## 📚 8. Membuat Menu Kategori (CRUD Lengkap)

### Kategori Controller (`app/Http/Controllers/KategoriController.php`)
**Methods yang diimplementasikan**:
- `create()` - Menampilkan form tambah kategori
- `store(Request $request)` - Menyimpan kategori baru ke database
  - Validasi input: nama_kategori required
  - Insert data menggunakan Eloquent
  - Redirect dengan success message
- `edit($id)` - Menampilkan form edit kategori
  - Find kategori by ID
  - Passing data ke view
- `update(Request $request, $id)` - Update data kategori
  - Validasi input
  - Update menggunakan Eloquent
  - Redirect dengan success message
- `destroy($id)` - Hapus kategori
  - Delete kategori by ID
  - Cascade delete buku terkait (via foreign key)
  - Redirect dengan success message

### Kategori Views
**Directory**: `resources/views/dashboard/kategori/`

#### `table.blade.php` - List Kategori
- Menampilkan tabel data kategori
- Menggunakan component `<x-data-table>`
- Kolom: No, Nama Kategori, Aksi
- Tombol tambah kategori baru
- Action buttons: Edit & Delete untuk setiap row

#### `create.blade.php` - Form Tambah Kategori
- Form input nama kategori
- Validasi client-side dan server-side
- Tombol submit dan cancel
- Error handling untuk validasi

#### `edit.blade.php` - Form Edit Kategori
- Form pre-filled dengan data kategori yang dipilih
- Update nama kategori
- Tombol save changes dan cancel
- Error handling untuk validasi

### Routing Kategori
**Routes yang ditambahkan**:
- `GET /kategori` → Menampilkan list kategori
- `GET /kategori/create` → Form tambah kategori
- `POST /kategori/store` → Proses simpan kategori baru
- `GET /kategori/{id}/edit` → Form edit kategori
- `PUT /kategori/{id}` → Proses update kategori
- `DELETE /kategori/{id}` → Hapus kategori

**Files Created**:
- `app/Http/Controllers/KategoriController.php`
- `resources/views/dashboard/kategori/table.blade.php`
- `resources/views/dashboard/kategori/create.blade.php`
- `resources/views/dashboard/kategori/edit.blade.php`

**Files Modified**:
- `routes/web.php` - Menambahkan 5 route untuk kategori CRUD

---

## 📖 9. Membuat Menu Buku (CRUD Lengkap)

### Buku Controller (`app/Http/Controllers/BukuController.php`)
**Methods yang diimplementasikan**:
- `create()` - Menampilkan form tambah buku
  - Mengambil semua kategori untuk dropdown
  - Passing data kategori ke view
- `store(Request $request)` - Menyimpan buku baru
  - Validasi: kode, judul, pengarang, kategori_id required
  - Insert data menggunakan Eloquent
  - Redirect dengan success message
- `edit($id)` - Menampilkan form edit buku
  - Find buku by ID dengan relasi kategori
  - Mengambil semua kategori untuk dropdown
  - Passing data ke view
- `update(Request $request, $id)` - Update data buku
  - Validasi semua field
  - Update menggunakan Eloquent
  - Redirect dengan success message
- `destroy($id)` - Hapus buku
  - Delete buku by ID
  - Redirect dengan success message

### Buku Views
**Directory**: `resources/views/dashboard/buku/`

#### `table.blade.php` - List Buku
- Menampilkan tabel data buku
- Menggunakan component `<x-data-table>`
- Kolom: No, Kode, Judul, Pengarang, Kategori, Aksi
- Menampilkan nama kategori melalui relasi
- Tombol tambah buku baru
- Action buttons: Edit & Delete untuk setiap row

#### `create.blade.php` - Form Tambah Buku
- Form input: kode, judul, pengarang
- Dropdown select kategori (populated dari database)
- Validasi client-side dan server-side
- Tombol submit dan cancel
- Error handling untuk validasi

#### `edit.blade.php` - Form Edit Buku
- Form pre-filled dengan data buku yang dipilih
- Dropdown kategori dengan selected value
- Update semua field buku
- Tombol save changes dan cancel
- Error handling untuk validasi

### Routing Buku
**Routes yang ditambahkan**:
- `GET /buku` → Menampilkan list buku
- `GET /buku/create` → Form tambah buku
- `POST /buku/store` → Proses simpan buku baru
- `GET /buku/{id}/edit` → Form edit buku
- `PUT /buku/{id}` → Proses update buku
- `DELETE /buku/{id}` → Hapus buku

**Files Created**:
- `app/Http/Controllers/BukuController.php`
- `resources/views/dashboard/buku/table.blade.php`
- `resources/views/dashboard/buku/create.blade.php`
- `resources/views/dashboard/buku/edit.blade.php`

**Files Modified**:
- `routes/web.php` - Menambahkan 5 route untuk buku CRUD

---

## 🧱 10. Membuat Reusable Data Table Component

### Motivasi
Menghindari duplikasi kode HTML tabel yang sama untuk setiap menu (kategori, buku, dll). Dengan membuat component, kita bisa reuse kode dan maintain lebih mudah.

### DataTable Component Class (`app/View/Components/DataTable.php`)
**Properties**:
- `$tableData` - Array data yang akan ditampilkan
- `$model` - Nama model untuk keperluan delete
- `$idField` - Nama field ID (default: 'id')
- `$editRoute` - Route name untuk edit
- `$deleteRoute` - Route name untuk delete
- `$createRoute` - Route name untuk create
- `$title` - Judul tabel

**Constructor**:
- Menerima semua properties sebagai parameter
- Set default values untuk optional parameters
- Flexible untuk berbagai jenis data table

### DataTable Blade View (`resources/views/components/data-table.blade.php`)
**Features**:
- Header dengan title dan tombol "Tambah Data"
- Tabel responsive dengan Bootstrap styling
- Auto-generate table headers dari array keys
- Loop data untuk generate table rows
- Action column dengan tombol Edit dan Delete
- Delete confirmation menggunakan JavaScript
- Form delete dengan CSRF token dan method spoofing
- Icon styling untuk action buttons (edit: pencil, delete: trash)

### Penggunaan Component
**Syntax**:
```blade
<x-data-table 
    :tableData="$kategoris"
    model="kategori"
    editRoute="kategori.edit"
    deleteRoute="kategori.destroy"
    createRoute="kategori.create"
    title="Data Kategori"
/>
```

### Benefits
- ✅ **DRY Principle** - Don't Repeat Yourself
- ✅ **Maintainability** - Update sekali, berlaku untuk semua tabel
- ✅ **Consistency** - Tampilan tabel konsisten di seluruh aplikasi
- ✅ **Reusability** - Bisa digunakan untuk tabel apapun
- ✅ **Flexibility** - Customizable melalui properties

### Implementasi pada Menu
- **Kategori Table**: Menggunakan DataTable component
- **Buku Table**: Menggunakan DataTable component
- Mengganti kode HTML tabel manual dengan component tag `<x-data-table>`

**Files Created**:
- `app/View/Components/DataTable.php` - Component class
- `resources/views/components/data-table.blade.php` - Component view

**Files Modified**:
- `resources/views/dashboard/kategori/table.blade.php` - Menggunakan component
- `resources/views/dashboard/buku/table.blade.php` - Menggunakan component

---

## 🎯 11. Implementasi Menu Highlighting Berdasarkan URL

### Motivasi
Memberikan visual feedback kepada user tentang halaman mana yang sedang aktif/dibuka. Ini meningkatkan user experience dengan membuat navigasi lebih intuitif dan mudah dipahami.

### Perbaikan Struktur Sidebar
**File**: `resources/views/layouts/partials/sidebar.blade.php`

**Masalah Sebelumnya**:
- Dua menu item (Kategori dan Buku) berada dalam satu `<li>` element
- Tidak ada indikator visual untuk menu yang sedang aktif
- Struktur HTML tidak semantik

**Solusi yang Diimplementasikan**:
- Memisahkan setiap menu item ke dalam `<li>` element tersendiri
- Menambahkan class `active` secara dinamis berdasarkan URL
- Menggunakan Laravel helper `request()->is()` untuk pattern matching

### Dynamic Active State Implementation

**Syntax yang Digunakan**:
```blade
<li class="nav-item {{ request()->is('kategori*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('kategori') }}">
        <span class="menu-title">Kategori</span>
        <i class="mdi mdi-bookshelf menu-icon"></i>
    </a>
</li>
```

**Cara Kerja**:
- `request()->is('kategori*')` - Mengecek apakah URL saat ini dimulai dengan 'kategori'
- Wildcard `*` memastikan semua sub-route juga terdeteksi (kategori, kategori/create, kategori/{id}/edit)
- Jika kondisi true, class `active` ditambahkan ke `<li>` element
- Template CSS akan otomatis styling menu yang active (biasanya dengan highlight color)

### URL Patterns yang Terdeteksi

**Menu Kategori** - Active ketika URL:
- `/kategori` - List kategori
- `/kategori/create` - Form tambah kategori
- `/kategori/{id}/edit` - Form edit kategori

**Menu Buku** - Active ketika URL:
- `/buku` - List buku
- `/buku/create` - Form tambah buku
- `/buku/{id}/edit` - Form edit buku

### Icon Updates
Mengganti icon generic `mdi-home` dengan icon yang lebih sesuai:
- **Kategori**: `mdi-bookshelf` - Ikon rak buku untuk kategori
- **Buku**: `mdi-book-open-page-variant` - Ikon buku terbuka

### Benefits
- ✅ **Better UX** - User tahu posisi mereka di aplikasi
- ✅ **Visual Feedback** - Highlight otomatis pada menu aktif
- ✅ **Semantic HTML** - Struktur yang benar dan valid
- ✅ **Maintainable** - Mudah menambah menu baru dengan pattern yang sama
- ✅ **Automatic** - Tidak perlu manual set active state di controller

**Files Modified**:
- `resources/views/layouts/partials/sidebar.blade.php` - Struktur dan active state logic

---

## 📊 Ringkasan Perubahan

### Total Files Created: 30+ files
- 2 Layouts (app, dashboard)
- 3 Layout Partials (navbar, footer, sidebar)
- 2 Controllers (DashboardController, KategoriController, BukuController)
- 6 Auth Controllers (generated by Laravel UI)
- 2 Models (Kategori, Buku)
- 2 Migrations (kategoris, bukus)
- 6 Auth Views (login, register, password reset, etc.)
- 6 Dashboard Views (kategori: table/create/edit, buku: table/create/edit)
- 2 Components (DataTable class & view)
- 1 Welcome page

### Total Files Modified: 3+ files
- `routes/web.php` - Routing configuration
- `composer.json` - Dependencies (laravel/ui)

### Database Tables Created: 4 tables
- `users` - User authentication
- `kategoris` - Kategori buku
- `bukus` - Data buku
- `password_resets` - Password reset tokens

### Features Implemented:
✅ Laravel Project Setup  
✅ Template Integration  
✅ Master Layouts  
✅ Navigation Components  
✅ Authentication System  
✅ Database Schema & Models  
✅ Kategori CRUD (Create, Read, Update, Delete)  
✅ Buku CRUD (Create, Read, Update, Delete)  
✅ Reusable DataTable Component  
✅ Relational Database (Kategori ↔ Buku)  
✅ Form Validation  
✅ Responsive Design  
✅ URL-based Menu Highlighting  

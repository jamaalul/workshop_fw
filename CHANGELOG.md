# Changelog

Semua perubahan penting pada proyek ini akan didokumentasikan dalam file ini.

### 🔐 Autentikasi & Keamanan
- **Integrasi Google SSO**: Mengimplementasikan login Google OAuth menggunakan Laravel Socialite untuk pengalaman autentikasi yang lancar.
  ![Layar Login Google](path_screenshot_di_sini)
- **Alur Verifikasi OTP**: Menambahkan lapisan verifikasi One-Time Password (OTP) 6 digit wajib setelah login SSO yang berhasil untuk menjamin keamanan akun.
  ![Formulir Verifikasi OTP](path_screenshot_di_sini)
- **Email OTP Otomatis**: Mengembangkan `OtpMail` dan mengintegrasikan sistem Mail Laravel untuk mengirimkan kode aman secara instan ke email pengguna.
- **Perlindungan Rute Aman**: Menerapkan middleware `auth` pada semua rute administratif termasuk Dashboard, Kategori, dan Buku.

### 📚 Manajemen Sumber Daya (CRUD)
- **Manajemen Kategori**: Implementasi penuh operasi Create, Read, Update, dan Delete untuk kategori buku.
  ![Tabel Indeks Kategori](path_screenshot_di_sini)
- **Manajemen Buku**: Fungsionalitas CRUD yang komprehensif untuk buku, menampilkan pengikatan data relasional dengan Kategori dan validasi input yang kuat.
  ![Formulir Manajemen Buku](path_screenshot_di_sini)
- **Arsitektur Database**: Membuat migrasi untuk tabel `kategoris` dan `bukus`, serta memperluas tabel `users` dengan field `id_google` dan `otp`.

### 📊 Sistem Pelaporan
- **Mesin Pembuat PDF**: Mengintegrasikan `barryvdh/laravel-dompdf` untuk pembuatan PDF sisi server yang berkualitas tinggi.
- **Laporan Buku Dinamis**: Pembuatan laporan buku secara otomatis dalam format A4 Landscape, termasuk detail kategori terkait.
  ![Laporan PDF Buku](path_screenshot_di_sini)
- **Laporan Kategori Dinamis**: Pembuatan laporan kategori secara otomatis dalam format A4 Portrait, menampilkan jumlah buku per kategori.
  ![Laporan PDF Kategori](path_screenshot_di_sini)

### 🎨 UI/UX & Komponen
- **Navigasi Sidebar Dinamis**: Mengimplementasikan highlighting sidebar cerdas yang mencerminkan rute aktif saat ini menggunakan logika Blade `request()->is()`.
- **Data Tables yang Terpoles**: Menyempurnakan komponen `data-table` dengan penyelarasan ikon yang dioptimalkan dan spasi untuk tombol aksi (Edit/Hapus).
- **Desain Auth Terpadu**: Menata halaman Login, Register, dan OTP dengan estetika premium yang konsisten sesuai dengan tema dashboard.


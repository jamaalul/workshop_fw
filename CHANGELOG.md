# Workshop Framework Changelog

## [Authentication System]
- **New Auth Layout**: Created `auth.blade.php` with a centered, card-based structure for a cleaner look.
- **Improved Views**: Updated `login.blade.php` and `register.blade.php` with Purple Admin form styling and gradient buttons.
- **Consistency**: Unified form input structures across auth and dashboard pages.
- **Security**: Ensured CSRF protection and proper validation error handling.

> [!TIP]
> **Auth Pages Screenshot**
> (Add screenshot here for login/register layout)
>
> <br><br><br>

---

## [Route Protection]
- **Middleware Integration**: Wrapped core routes (`/home`, `/kategori`, `/buku`) in `auth` middleware group.
- **Access Control**: Restricted dashboard and CRUD functionality to authenticated users only.

```php
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    Route::resource('kategori', KategoriController.class);
    Route::resource('buku', BukuController.class);
});
```

> [!TIP]
> **Route Configuration Screenshot**
> (Add screenshot here for web.php middleware group)
>
> <br><br><br>

---
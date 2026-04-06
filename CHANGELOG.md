# Changelog

## [Unreleased]
### Added
- **Midtrans Core API Integration**: Installed `midtrans/midtrans-php` package, added Sandbox keys to `.env`. Built custom payment interface flow separating 'QRIS' and 'Virtual Account' options. Implemented an auto-polling status page for guest view.
- **Database Schema**: Added tables matching ERD for `vendor`, `menu`, `pesanan`, `detail_pesanan`, and `payments` migrations.
- **Models & Relationships**: Configured `Vendor`, `Menu`, `Pesanan`, `DetailPesanan`, and `Payment` Eloquent models with appropriate relationships (`hasMany`, `belongsTo`, `hasOne`).
- **Database Seeders**: Added `CanteenSeeder` populated with dummy vendor and menu items for local testing.
- **Controllers**:
  - `CanteenController`: Manages POS frontend for guest checkouts, automatic incrementing `Guest_000000x` generation, and order persistence.
  - `PaymentController`: Interacts with Midtrans CoreAPI to generate charge intents, retrieve transaction IDs/Virtual Account Numbers/QRIS urls, handle webhooks (notification), and retrieve live transaction statuses.
  - `VendorController`: Lists paid orders by checking `status_bayar = 1`.
  - `VendorMenuController`: Stubbed methods for Menu CRUD management flow.
- **Views & UI Integration**:
  - Inserted links into `layouts/partials/sidebar.blade.php`.
  - `canteen/index.blade.php`: POS interface utilizing jQuery AJAX fetching for cascading vendor -> menu filtering. Shopping cart updates dynamically without full-page reloads.
  - `canteen/payment.blade.php`: Midtrans Custom User Interface integrating existing design system templates with options for Bank Transfer (VAs) and GoPay QRIS polling.
  - `vendor/orders.blade.php`: Dashboard table highlighting paid order history.
- **Webhooks**: Routed API notifications on `ROUTES` excluding CSRF validation globally.
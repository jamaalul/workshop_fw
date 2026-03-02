# Changelog

## [Unreleased] - 2026-03-02

### Added

- **Barang Module (CRUD)**
  - New `BarangController` with full CRUD operations (`create`, `store`, `edit`, `update`, `destroy`)
  - New `Barang` model and database migration (`create_barangs_table`)
  - New Barang dashboard view (`resources/views/dashboard/barang/`)
  - Sidebar navigation entry for "Barang" menu item
  - `DashboardController::barang()` method to display Barang data table

- **Barang Label Printing**
  - `printLabel()` method in `BarangController` for generating printable labels
  - Label report view (`resources/views/reports/barang_label.blade.php`)
  - "Cetak Label" button on the Barang data table (enabled when rows are selected)
  - Row checkbox selection with "Select All" functionality on the data table

- **Data Table Component Enhancements**
  - `labelRoute` prop support in `data-table.blade.php` component
  - Conditional checkbox column (header + body) when `labelRoute` is provided
  - JavaScript for check-all toggle and dynamic button state management

- **Routes**
  - `GET /barang` — Barang listing page
  - `GET /barang/create` — Create form
  - `POST /barang/store` — Store new barang
  - `GET /barang/{id}/edit` — Edit form
  - `PUT /barang/{id}` — Update barang
  - `DELETE /barang/{id}` — Delete barang
  - `POST /barang/print-label` — Print selected labels

### Fixed

- Trailing comma consistency in `DashboardController` for `kategori()` and `buku()` view data arrays

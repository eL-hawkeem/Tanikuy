# ARCHITECTURE.md — Tanikuy Inventory Management System

> **Version:** 1.0  
> **Stack:** Laravel 11 + Livewire 3 + Tailwind CSS 3 + MySQL 8  
> **Last Updated:** 2026-10-11

---

## 1. System Architecture Overview

```
┌─────────────────────────────────────────────────────────┐
│                      CLIENT LAYER                       │
│  ┌─────────────────────────────────────────────────┐    │
│  │     Browser (Chrome / Firefox / Edge)            │    │
│  │     Blade Templates + Livewire Components        │    │
│  │     Tailwind CSS + Alpine.js + Chart.js          │    │
│  └─────────────────────────────────────────────────┘    │
└──────────────────────────┬──────────────────────────────┘
                           │ HTTP/HTTPS + WebSocket (Livewire)
┌──────────────────────────▼──────────────────────────────┐
│                   APPLICATION LAYER                     │
│  ┌─────────────────────────────────────────────────┐    │
│  │              Laravel 11 Framework                │    │
│  │  ┌──────────┬──────────┬──────────┬──────────┐  │    │
│  │  │  Routes  │ Middleware│Controllers│  Views  │  │    │
│  │  └──────────┴──────────┴──────────┴──────────┘  │    │
│  │  ┌──────────┬──────────┬──────────┬──────────┐  │    │
│  │  │ Services │   EOQ    │  Export  │ Livewire │  │    │
│  │  │  Layer   │  Engine  │  Engine  │Components│  │    │
│  │  └──────────┴──────────┴──────────┴──────────┘  │    │
│  │  ┌──────────┬──────────┬──────────┬──────────┐  │    │
│  │  │  Models  │ Observers│  Events  │  Jobs    │  │    │
│  │  │ (Eloquent)│         │ Listeners│ (Queue)  │  │    │
│  │  └──────────┴──────────┴──────────┴──────────┘  │    │
│  └─────────────────────────────────────────────────┘    │
└──────────────────────────┬──────────────────────────────┘
                           │
┌──────────────────────────▼──────────────────────────────┐
│                      DATA LAYER                         │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  │
│  │   MySQL 8    │  │  File Storage│  │    Cache      │  │
│  │  (Primary DB)│  │ (Product Img)│  │   (File)      │  │
│  └──────────────┘  └──────────────┘  └──────────────┘  │
└─────────────────────────────────────────────────────────┘
```

---

## 2. Technology Stack

| Layer | Technology | Version | Purpose |
|-------|-----------|---------|---------|
| **Runtime** | PHP | 8.2+ | Server-side language |
| **Framework** | Laravel | 11.x | MVC framework |
| **Frontend Reactivity** | Livewire | 3.x | Server-driven SPA-like UX |
| **CSS** | Tailwind CSS | 3.x | Utility-first styling |
| **JS Interactivity** | Alpine.js | 3.x | Lightweight JS (bundled with Livewire) |
| **Charts** | Chart.js | 4.x | Dashboard visualizations |
| **Database** | MySQL | 8.0+ | Relational data store |
| **PDF Export** | Barryvdh/DomPDF | 2.x | PDF report generation |
| **Excel Export** | Maatwebsite/Excel | 3.x | Excel import/export |
| **Auth** | Laravel Breeze | 2.x | Authentication scaffolding |
| **Dev Server** | Laragon | Latest | Local development |
| **Version Control** | Git + GitHub | - | Source code management |

---

## 3. Directory Structure

```
Tanikuy/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── DashboardController.php
│   │   │   ├── ProductController.php
│   │   │   ├── CategoryController.php
│   │   │   ├── SupplierController.php
│   │   │   ├── TransactionController.php
│   │   │   ├── StockController.php
│   │   │   ├── EoqController.php
│   │   │   └── ReportController.php
│   │   ├── Middleware/
│   │   └── Requests/
│   │       ├── ProductRequest.php
│   │       ├── SupplierRequest.php
│   │       └── TransactionRequest.php
│   ├── Livewire/
│   │   ├── Dashboard/
│   │   │   ├── StatsCards.php
│   │   │   ├── SalesChart.php
│   │   │   ├── StockAlertTable.php
│   │   │   └── RecentTransactions.php
│   │   ├── Products/
│   │   │   ├── ProductTable.php
│   │   │   ├── ProductForm.php
│   │   │   └── ProductImport.php
│   │   ├── Suppliers/
│   │   │   ├── SupplierTable.php
│   │   │   └── SupplierForm.php
│   │   ├── Transactions/
│   │   │   ├── TransactionForm.php
│   │   │   ├── TransactionTable.php
│   │   │   └── TransactionDetail.php
│   │   ├── Stock/
│   │   │   ├── StockMonitor.php
│   │   │   ├── StockMovement.php
│   │   │   └── StockAdjustment.php
│   │   ├── Eoq/
│   │   │   ├── EoqDashboard.php
│   │   │   └── EoqCalculator.php
│   │   └── Reports/
│   │       ├── SalesReport.php
│   │       ├── StockReport.php
│   │       └── EoqReport.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── Category.php
│   │   ├── Supplier.php
│   │   ├── Transaction.php
│   │   ├── TransactionDetail.php
│   │   ├── StockMovement.php
│   │   └── EoqCalculation.php
│   ├── Services/
│   │   ├── EoqService.php
│   │   ├── StockService.php
│   │   ├── TransactionService.php
│   │   └── ReportService.php
│   ├── Observers/
│   │   ├── TransactionObserver.php
│   │   └── StockMovementObserver.php
│   ├── Exports/
│   │   ├── ProductsExport.php
│   │   ├── TransactionsExport.php
│   │   ├── StockExport.php
│   │   └── EoqExport.php
│   ├── Imports/
│   │   └── ProductsImport.php
│   └── Enums/
│       ├── ProductUnit.php
│       ├── PaymentMethod.php
│       ├── StockMovementType.php
│       └── TransactionStatus.php
├── database/
│   ├── migrations/
│   │   ├── 0001_create_categories_table.php
│   │   ├── 0002_create_suppliers_table.php
│   │   ├── 0003_create_products_table.php
│   │   ├── 0004_create_transactions_table.php
│   │   ├── 0005_create_transaction_details_table.php
│   │   ├── 0006_create_stock_movements_table.php
│   │   └── 0007_create_eoq_calculations_table.php
│   ├── seeders/
│   │   ├── DatabaseSeeder.php
│   │   ├── CategorySeeder.php
│   │   ├── SupplierSeeder.php
│   │   └── ProductSeeder.php
│   └── factories/
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php
│       │   └── guest.blade.php
│       ├── components/
│       │   ├── sidebar.blade.php
│       │   ├── navbar.blade.php
│       │   ├── modal.blade.php
│       │   ├── alert.blade.php
│       │   └── stat-card.blade.php
│       ├── dashboard.blade.php
│       ├── products/
│       ├── suppliers/
│       ├── transactions/
│       ├── stock/
│       ├── eoq/
│       ├── reports/
│       └── livewire/
├── routes/
│   └── web.php
├── tests/
│   ├── Feature/
│   └── Unit/
│       └── EoqServiceTest.php
├── docs/
│   ├── PRD.md
│   ├── ARCHITECTURE.md
│   ├── DATABASE.md
│   ├── AGENTS.md
│   └── RULES.md
└── public/
    └── assets/
```

---

## 4. Design Patterns

### 4.1 Service Layer Pattern

Business logic dipisahkan dari Controller ke Service class.

```
Controller → Service → Model → Database
     ↑                    ↓
    View ← Livewire ← Events
```

**Services:**
- `EoqService` — Kalkulasi EOQ, demand forecasting
- `StockService` — Pergerakan stok, validasi, adjustment
- `TransactionService` — Proses transaksi, pembatalan, invoice
- `ReportService` — Aggregasi data, generate laporan

### 4.2 Observer Pattern

Model Observers untuk side-effects otomatis:
- `TransactionObserver` — Auto-reduce stok saat transaksi `completed`, auto-restore saat `cancelled`
- `StockMovementObserver` — Log setiap perubahan stok

### 4.3 Enum Pattern (PHP 8.1+)

Typed enums untuk nilai tetap:
- `ProductUnit` — kg, pcs, liter, karung, botol, pack
- `PaymentMethod` — cash, transfer, qris
- `StockMovementType` — in, out, adjustment
- `TransactionStatus` — pending, completed, cancelled

### 4.4 Form Request Validation

Validasi input dipisahkan ke Form Request classes:
- `ProductRequest` — Validasi CRUD produk
- `SupplierRequest` — Validasi CRUD supplier
- `TransactionRequest` — Validasi transaksi penjualan

---

## 5. EOQ Engine Architecture

```
┌──────────────────────────────────────────────┐
│              EoqService                       │
│                                              │
│  calculate(Product $product): EoqResult      │
│  ┌────────────────────────────────────────┐  │
│  │  1. Get Annual Demand (D)              │  │
│  │     → TransactionDetail::where(...)    │  │
│  │     → Sum qty last 12 months           │  │
│  │     → If < 12 months data: extrapolate │  │
│  │                                        │  │
│  │  2. Get Ordering Cost (S)              │  │
│  │     → product->biaya_pemesanan         │  │
│  │                                        │  │
│  │  3. Get Holding Cost (H)               │  │
│  │     → product->biaya_penyimpanan       │  │
│  │                                        │  │
│  │  4. Calculate EOQ                      │  │
│  │     → sqrt(2 * D * S / H)             │  │
│  │                                        │  │
│  │  5. Derived Metrics                    │  │
│  │     → Order Frequency = D / EOQ        │  │
│  │     → Total Cost = (D/EOQ)*S + (EOQ/2)*H│ │
│  │     → Daily Demand = D / 365           │  │
│  └────────────────────────────────────────┘  │
│                                              │
│  calculateAll(): Collection<EoqResult>       │
│  needsReorder(Product): bool                 │
│  getReorderList(): Collection                │
└──────────────────────────────────────────────┘
```

**EoqResult DTO:**
```php
class EoqResult {
    public float $eoq;               // Jumlah optimal per order
    public float $annualDemand;      // Permintaan tahunan
    public float $orderFrequency;    // Frekuensi order per tahun
    public float $totalOptimalCost;  // Total biaya optimal
    public float $dailyDemand;       // Permintaan harian
    public string $recommendation;   // Teks rekomendasi
}
```

---

## 6. Data Flow Diagrams

### 6.1 Transaction Flow

```
[Admin] → Input Penjualan
    │
    ▼
[TransactionForm Livewire]
    │ Validate input
    │ Check stock availability
    ▼
[TransactionService::create()]
    │
    ├──→ Create Transaction (header)
    ├──→ Create TransactionDetails (items)
    ├──→ StockService::reduceStock()
    │       ├──→ Update product.stok_saat_ini
    │       └──→ Create StockMovement (type: out)
    │
    └──→ Check stock alerts
            └──→ If stok ≤ stok_minimum → Flash alert
```

### 6.2 Stock Monitoring Flow

```
[StockMonitor Livewire] ← Auto-refresh via polling/wire:poll
    │
    ▼
[StockService::getStockStatus()]
    │
    ├── 🟢 Normal: stok > stok_minimum × 1.5
    ├── 🟡 Warning: stok_minimum < stok ≤ stok_minimum × 1.5
    └── 🔴 Critical: stok ≤ stok_minimum
              │
              └── Trigger EOQ recommendation
                    └── EoqService::calculate(product)
```

### 6.3 Report Generation Flow

```
[ReportController] → Select report type + filters
    │
    ▼
[ReportService::generate()]
    │
    ├──→ Chart Data (JSON) → Chart.js rendering
    ├──→ PDF → DomPDF::loadView() → download
    └──→ Excel → Maatwebsite\Excel → download
```

---

## 7. Security Architecture

| Layer | Measure |
|-------|---------|
| **Authentication** | Laravel Breeze (session-based) |
| **CSRF** | Automatic via `@csrf` directive |
| **XSS** | Blade `{{ }}` auto-escaping |
| **SQL Injection** | Eloquent ORM parameterized queries |
| **Mass Assignment** | `$fillable` whitelists on all models |
| **File Upload** | Validation (image types, max 2MB) |
| **Rate Limiting** | Throttle middleware on auth routes |

---

## 8. Performance Strategy

| Area | Strategy |
|------|----------|
| **Queries** | Eager loading (`with()`), avoid N+1 |
| **Pagination** | Server-side pagination (15-25 per page) |
| **Caching** | Cache dashboard stats (5-min TTL) |
| **Assets** | Vite bundling + minification |
| **Images** | Resize on upload, serve optimized |
| **Livewire** | Lazy loading, `wire:poll` dengan interval wajar |
| **Database** | Proper indexing on FK, search, dan sort columns |

---

## 9. Third-Party Packages

| Package | Purpose | Composer |
|---------|---------|----------|
| laravel/breeze | Auth scaffolding | `laravel/breeze` |
| livewire/livewire | Reactive components | `livewire/livewire` |
| barryvdh/laravel-dompdf | PDF generation | `barryvdh/laravel-dompdf` |
| maatwebsite/excel | Excel import/export | `maatwebsite/excel` |
| intervention/image | Image processing | `intervention/image-laravel` |

---

## 10. Environment Configuration

```env
APP_NAME=Tanikuy
APP_ENV=local
APP_DEBUG=true
APP_URL=http://tanikuy.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tanikuy
DB_USERNAME=root
DB_PASSWORD=

FILESYSTEM_DISK=public
```

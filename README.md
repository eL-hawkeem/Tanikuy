<p align="center">
  <img src="docs/assets/logo-placeholder.png" alt="Tanikuy Logo" width="120">
</p>

<h1 align="center">Tanikuy</h1>
<p align="center">
  <strong>Sistem Manajemen Inventory & Pencatatan Penjualan untuk UMKM Pertanian</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/Livewire-3.x-FB70A9?style=for-the-badge&logo=livewire&logoColor=white" alt="Livewire">
  <img src="https://img.shields.io/badge/Tailwind_CSS-3.x-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="Tailwind">
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Status-In_Development-yellow?style=flat-square" alt="Status">
  <img src="https://img.shields.io/badge/License-MIT-green?style=flat-square" alt="License">
  <img src="https://img.shields.io/badge/Version-1.0.0--dev-blue?style=flat-square" alt="Version">
</p>

---

## 📋 Tentang Proyek

**Tanikuy** adalah sistem manajemen inventory berbasis web yang dirancang khusus untuk UMKM yang bergerak di bidang **produk pertanian** (beras, pupuk, benih, pestisida) dan **peralatan pertanian**. Sistem ini menggunakan **Algoritma EOQ (Economic Order Quantity)** untuk mengoptimalkan pemesanan barang, sehingga meminimalkan biaya inventory secara keseluruhan.

### Mengapa Tanikuy?

| Masalah | Solusi Tanikuy |
|---------|---------------|
| Pencatatan stok manual & rawan error | Dashboard stok real-time dengan alert otomatis |
| Tidak tahu kapan harus pesan barang | Rekomendasi pemesanan otomatis via algoritma EOQ |
| Susah melacak riwayat penjualan | Riwayat transaksi lengkap dengan filter & export |
| Tidak ada laporan bisnis | Laporan visual (chart) + export PDF & Excel |
| Proses penjualan lambat | Form penjualan cepat dengan auto-complete & kalkulasi otomatis |

---

## ✨ Fitur Utama

### 📦 Kelola Barang
- CRUD produk lengkap dengan kode otomatis (TK-XXXX)
- Kategori produk: Beras, Pupuk, Benih, Pestisida, Peralatan
- Upload gambar produk
- Bulk import via Excel
- Filter, search, dan sortable table

### 🏭 Kelola Supplier
- CRUD data supplier/pemasok
- Tracking produk per supplier
- Riwayat pemesanan ke supplier
- Filter berdasarkan kota dan status

### 🛒 Pencatatan Transaksi
- Form penjualan multi-item dengan auto-complete produk
- Validasi stok sebelum transaksi
- Kalkulasi otomatis (subtotal, diskon, total)
- Dukungan metode pembayaran: Cash, Transfer, QRIS
- Cetak invoice/struk (PDF)
- Pembatalan transaksi dengan rollback stok otomatis

### 📜 Riwayat Penjualan
- Tabel riwayat dengan pagination
- Filter berdasarkan tanggal, metode pembayaran, status
- Detail view per transaksi
- Export riwayat ke Excel/PDF

### 📊 Pemantauan Stok Otomatis + EOQ
- Dashboard stok dengan indikator warna (🟢 Normal / 🟡 Warning / 🔴 Critical)
- Alert produk stok menipis
- **Algoritma EOQ** — menghitung jumlah pemesanan optimal
- Riwayat pergerakan stok (masuk/keluar/adjustment)
- Kartu stok per produk

### 📋 Data Kebutuhan Barang
- Daftar produk yang perlu dipesan (stok ≤ minimum)
- Rekomendasi jumlah berdasarkan EOQ
- Estimasi biaya pemesanan
- Export daftar kebutuhan

### 📈 Laporan Komprehensif
- **Laporan Penjualan** — tren penjualan dengan chart interaktif
- **Laporan Stok** — posisi stok dan pergerakan
- **Laporan Laba Kotor** — pendapatan vs HPP
- **Laporan Produk Terlaris** — ranking produk
- **Laporan EOQ** — rekomendasi pemesanan optimal
- Export ke **PDF** dan **Excel**
- Filter periode: harian, mingguan, bulanan, custom range

---

## 🧮 Algoritma EOQ

Tanikuy menggunakan **Economic Order Quantity (EOQ)** untuk menentukan jumlah pemesanan optimal:

```
EOQ = √(2 × D × S / H)

D = Permintaan tahunan (dihitung otomatis dari riwayat penjualan)
S = Biaya pemesanan per order
H = Biaya penyimpanan per unit per tahun
```

**Output:**
- Jumlah pemesanan optimal per order
- Frekuensi pemesanan per tahun
- Total biaya inventory optimal
- Rekomendasi teks untuk pengambilan keputusan

---

## 🛠️ Tech Stack

| Layer | Teknologi |
|-------|-----------|
| **Backend** | Laravel 11, PHP 8.2+ |
| **Frontend** | Blade Templates, Livewire 3, Alpine.js |
| **Styling** | Tailwind CSS 3 |
| **Database** | MySQL 8.0+ |
| **Charts** | Chart.js 4 |
| **PDF Export** | Barryvdh/DomPDF |
| **Excel Export** | Maatwebsite/Excel |
| **Image Processing** | Intervention/Image |
| **Auth** | Laravel Breeze |
| **Build Tool** | Vite |

---

## 📁 Struktur Proyek

```
Tanikuy/
├── app/
│   ├── Http/Controllers/     # Route controllers
│   ├── Livewire/             # Livewire interactive components
│   ├── Models/               # Eloquent models
│   ├── Services/             # Business logic (EOQ, Stock, Transaction)
│   ├── Observers/            # Model event observers
│   ├── Enums/                # PHP 8.1+ Enums
│   ├── Exports/              # Excel export classes
│   └── Imports/              # Excel import classes
├── database/
│   ├── migrations/           # Database schema
│   └── seeders/              # Sample data
├── resources/views/
│   ├── layouts/              # App layout templates
│   ├── components/           # Blade components
│   └── livewire/             # Livewire views
├── routes/
│   └── web.php               # Web routes
├── tests/
│   ├── Unit/                 # Unit tests
│   └── Feature/              # Feature tests
└── docs/                     # Documentation
    ├── PRD.md                # Product Requirements
    ├── ARCHITECTURE.md       # System Architecture
    ├── DATABASE.md           # Database Design
    ├── AGENTS.md             # Agent Configuration
    └── RULES.md              # Coding Standards
```

---

## 🚀 Instalasi & Setup

### Prasyarat

- PHP 8.2+
- Composer 2.x
- Node.js 18+ & NPM
- MySQL 8.0+
- Laragon (recommended) atau XAMPP

### Langkah Instalasi

```bash
# 1. Clone repository
git clone https://github.com/YOUR_USERNAME/Tanikuy.git
cd Tanikuy

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Setup environment
cp .env.example .env
php artisan key:generate

# 5. Konfigurasi database di .env
# DB_DATABASE=tanikuy
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Buat database
mysql -u root -e "CREATE DATABASE tanikuy CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 7. Jalankan migration + seeder
php artisan migrate --seed

# 8. Link storage
php artisan storage:link

# 9. Build assets
npm run build

# 10. Jalankan aplikasi
php artisan serve
```

### Development Mode

```bash
# Terminal 1 — Laravel server
php artisan serve

# Terminal 2 — Vite dev server (hot reload)
npm run dev
```

Akses aplikasi di: `http://localhost:8000` atau `http://tanikuy.test` (via Laragon)

### Default Login

```
Email: admin@tanikuy.com
Password: password
```

---

## 🗂️ Database Schema

Lihat dokumentasi lengkap di [`docs/DATABASE.md`](docs/DATABASE.md)

### Tabel Utama

| Tabel | Deskripsi |
|-------|-----------|
| `categories` | Kategori produk |
| `suppliers` | Data pemasok |
| `products` | Master produk |
| `transactions` | Header transaksi penjualan |
| `transaction_details` | Item detail per transaksi |
| `stock_movements` | Log pergerakan stok |
| `eoq_calculations` | Riwayat kalkulasi EOQ |

---

## 📖 Dokumentasi

| Dokumen | Deskripsi |
|---------|-----------|
| [`docs/PRD.md`](docs/PRD.md) | Product Requirements Document |
| [`docs/ARCHITECTURE.md`](docs/ARCHITECTURE.md) | System Architecture & Design Patterns |
| [`docs/DATABASE.md`](docs/DATABASE.md) | Database Design & ERD |
| [`docs/AGENTS.md`](docs/AGENTS.md) | Development Agent Configuration |
| [`docs/RULES.md`](docs/RULES.md) | Coding Standards & Rules |

---

## 🗓️ Roadmap

### v1.0 — MVP (Current)
- [x] Project setup & documentation
- [ ] Authentication system
- [ ] Dashboard dengan chart interaktif
- [ ] CRUD Kategori, Produk, Supplier
- [ ] Pencatatan transaksi penjualan
- [ ] Manajemen stok + pergerakan
- [ ] Algoritma EOQ
- [ ] Laporan (PDF + Excel + Chart)

### v2.0 — Enhanced (Future)
- [ ] Reorder Point (ROP) & Safety Stock
- [ ] Multi-user role (Admin, Kasir, Gudang)
- [ ] Barcode/QR Code scanning
- [ ] Notifikasi email stok menipis
- [ ] Dashboard mobile-optimized

### v3.0 — Scale (Future)
- [ ] Multi-cabang support
- [ ] Integrasi marketplace (Tokopedia, Shopee)
- [ ] REST API untuk mobile app
- [ ] Backup otomatis terjadwal

---

## 🧪 Testing

```bash
# Jalankan semua test
php artisan test

# Jalankan test spesifik
php artisan test --filter=EoqServiceTest

# Test dengan coverage
php artisan test --coverage
```

---

## 🤝 Kontribusi

1. Fork repository ini
2. Buat feature branch (`git checkout -b feature/nama-fitur`)
3. Commit changes (`git commit -m 'feat: tambah fitur baru'`)
4. Push ke branch (`git push origin feature/nama-fitur`)
5. Buat Pull Request

Lihat [`docs/RULES.md`](docs/RULES.md) untuk coding standards.

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

---

<p align="center">
  <strong>Tanikuy</strong> — Solusi Cerdas Inventory UMKM Pertanian 🌾
</p>

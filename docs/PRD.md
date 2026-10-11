# PRD — Tanikuy Inventory Management System

> **Version:** 1.0  
> **Author:** Tanikuy Development Team  
> **Date:** 2026-10-11  
> **Status:** Draft → Review

---

## 1. Executive Summary

**Tanikuy** adalah Sistem Manajemen Inventory dan Pencatatan Penjualan untuk UMKM yang bergerak di bidang produk pertanian dan peralatan pertanian. Sistem ini dirancang untuk mengelola stok barang secara otomatis menggunakan **Algoritma Economic Order Quantity (EOQ)**, mencatat transaksi penjualan, mengelola data supplier, dan menghasilkan laporan bisnis komprehensif.

### Problem Statement

UMKM Tanikuy saat ini menghadapi:
- Pencatatan stok manual yang rawan kesalahan
- Tidak ada sistem otomatis untuk menentukan kapan dan berapa banyak barang harus dipesan
- Kesulitan melacak riwayat penjualan dan tren produk
- Tidak ada laporan bisnis yang terstruktur untuk pengambilan keputusan

### Solution

Membangun sistem web-based menggunakan **Laravel 11** dengan **Blade + Livewire + Tailwind CSS** yang menyediakan:
- Manajemen barang dan kategori produk pertanian
- Manajemen supplier dengan tracking performa
- Pencatatan transaksi penjualan real-time
- Pemantauan stok otomatis dengan kalkulasi EOQ
- Dashboard analitik dan laporan (PDF + Excel + Chart)

---

## 2. Product Vision

> **Menjadi sistem inventory management yang cerdas dan mudah digunakan untuk UMKM pertanian, dengan optimasi pemesanan berbasis data menggunakan algoritma EOQ.**

### Target User

| Attribute | Detail |
|-----------|--------|
| **User** | Single Admin (Pemilik UMKM) |
| **Industri** | Pertanian — Beras, Pupuk, Benih, Peralatan Pertanian |
| **Skala** | UMKM dengan 50-500 SKU produk |
| **Lokasi** | Indonesia |
| **Device** | Desktop primary, tablet secondary |

---

## 3. Feature Specifications

### 3.1 Dashboard Utama

**Deskripsi:** Halaman utama yang menampilkan ringkasan kondisi bisnis secara real-time.

| Komponen | Detail |
|----------|--------|
| Kartu Statistik | Total produk, total supplier, penjualan hari ini, stok menipis |
| Chart Penjualan | Line chart — tren penjualan 30 hari terakhir |
| Chart Produk Terlaris | Bar chart — top 10 produk terlaris bulan ini |
| Tabel Stok Alert | Daftar produk yang stoknya di bawah minimum |
| Tabel Transaksi Terbaru | 10 transaksi penjualan terakhir |
| EOQ Insights | Ringkasan rekomendasi pemesanan dari kalkulasi EOQ |

### 3.2 Kelola Barang (Product Management)

**Deskripsi:** CRUD lengkap untuk manajemen produk pertanian.

**Data Fields:**

| Field | Type | Required | Keterangan |
|-------|------|----------|------------|
| `kode_barang` | string | ✅ | Auto-generate (format: TK-XXXX) |
| `nama_barang` | string | ✅ | Nama produk |
| `kategori_id` | FK | ✅ | Relasi ke tabel kategori |
| `supplier_id` | FK | ❌ | Supplier utama |
| `satuan` | enum | ✅ | kg, pcs, liter, karung, botol, pack |
| `harga_beli` | decimal | ✅ | Harga beli per satuan |
| `harga_jual` | decimal | ✅ | Harga jual per satuan |
| `stok_saat_ini` | integer | ✅ | Stok tersedia saat ini |
| `stok_minimum` | integer | ✅ | Batas minimum stok (alert trigger) |
| `biaya_pemesanan` | decimal | ✅ | Ordering cost untuk EOQ |
| `biaya_penyimpanan` | decimal | ✅ | Holding cost per unit per tahun untuk EOQ |
| `deskripsi` | text | ❌ | Deskripsi produk |
| `gambar` | file | ❌ | Foto produk |
| `status` | enum | ✅ | active / inactive |

**Fitur:**
- CRUD produk dengan validasi
- Upload dan preview gambar produk
- Filter dan search (by nama, kategori, supplier, status)
- Sortable columns
- Bulk import via Excel
- Export data produk ke Excel
- Kategori produk: Beras, Pupuk, Benih, Pestisida, Peralatan, Lainnya

### 3.3 Kelola Supplier (Supplier Management)

**Deskripsi:** CRUD untuk mengelola data supplier/pemasok.

**Data Fields:**

| Field | Type | Required |
|-------|------|----------|
| `kode_supplier` | string | ✅ (auto: SUP-XXXX) |
| `nama` | string | ✅ |
| `perusahaan` | string | ❌ |
| `telepon` | string | ✅ |
| `email` | string | ❌ |
| `alamat` | text | ✅ |
| `kota` | string | ✅ |
| `catatan` | text | ❌ |
| `status` | enum | ✅ (active/inactive) |

**Fitur:**
- CRUD supplier dengan validasi
- Lihat daftar produk yang dipasok oleh supplier
- Riwayat pemesanan ke supplier
- Filter dan search

### 3.4 Pencatatan Transaksi Penjualan

**Deskripsi:** Sistem pencatatan penjualan dengan auto-update stok.

**Data Flow:**
```
Pilih Produk → Input Qty → Validasi Stok → Hitung Total
    → Pilih Metode Bayar → Simpan Transaksi → Kurangi Stok
        → Cek EOQ Alert → Generate Invoice (opsional)
```

**Data Fields - Header Transaksi:**

| Field | Type | Required |
|-------|------|----------|
| `nomor_transaksi` | string | ✅ (auto: INV-YYYYMMDD-XXXX) |
| `tanggal` | datetime | ✅ |
| `nama_pelanggan` | string | ❌ |
| `metode_pembayaran` | enum | ✅ (cash/transfer/qris) |
| `subtotal` | decimal | ✅ (calculated) |
| `diskon` | decimal | ❌ |
| `total` | decimal | ✅ (calculated) |
| `catatan` | text | ❌ |

**Data Fields - Detail Transaksi:**

| Field | Type | Required |
|-------|------|----------|
| `produk_id` | FK | ✅ |
| `jumlah` | integer | ✅ |
| `harga_satuan` | decimal | ✅ (auto from produk) |
| `subtotal` | decimal | ✅ (calculated) |

**Fitur:**
- Form penjualan dengan auto-complete produk
- Validasi stok sebelum transaksi
- Multi-item per transaksi
- Kalkulasi otomatis (subtotal, diskon, total)
- Auto-reduce stok setelah transaksi berhasil
- Cetak struk/invoice (PDF)
- Pembatalan transaksi (dengan rollback stok)

### 3.5 Riwayat Penjualan

**Deskripsi:** Halaman untuk melihat semua riwayat transaksi penjualan.

**Fitur:**
- Tabel riwayat transaksi dengan pagination
- Filter by: tanggal range, metode pembayaran, status
- Search by: nomor transaksi, nama pelanggan
- Detail view per transaksi
- Export riwayat ke Excel/PDF
- Ringkasan statistik (total penjualan periode, rata-rata per hari)

### 3.6 Pemantauan Stok Otomatis + EOQ

**Deskripsi:** Sistem monitoring stok dengan kalkulasi EOQ untuk optimasi pemesanan.

#### Algoritma EOQ (Economic Order Quantity)

**Formula:**

```
EOQ = √(2 × D × S / H)

Di mana:
  D = Demand (permintaan tahunan dalam unit)
  S = Ordering Cost (biaya pemesanan per order)
  H = Holding Cost (biaya penyimpanan per unit per tahun)
```

**Kalkulasi Otomatis:**
- `D` dihitung dari riwayat penjualan (rata-rata penjualan × 12 bulan)
- `S` diambil dari field `biaya_pemesanan` produk
- `H` diambil dari field `biaya_penyimpanan` produk

**Output EOQ Dashboard:**

| Informasi | Detail |
|-----------|--------|
| Jumlah Pemesanan Optimal (EOQ) | Berapa unit per order |
| Frekuensi Pemesanan | Berapa kali pesan per tahun |
| Total Cost Optimal | Total biaya inventory optimal per tahun |
| Demand Harian | Rata-rata permintaan per hari |

**Fitur Stok:**
- Dashboard stok dengan status warna (hijau/kuning/merah)
- Alert produk stok menipis (di bawah `stok_minimum`)
- Riwayat pergerakan stok (masuk/keluar/adjustment)
- Kartu stok per produk
- Adjustment stok manual (dengan alasan)
- Log semua perubahan stok

### 3.7 Data Kebutuhan Barang

**Deskripsi:** Analisis kebutuhan barang berdasarkan EOQ dan tren penjualan.

**Fitur:**
- Daftar produk yang perlu dipesan (stok ≤ stok_minimum)
- Rekomendasi jumlah pemesanan berdasarkan EOQ
- Estimasi biaya pemesanan
- Tren kebutuhan bulanan (chart)
- Export daftar kebutuhan ke Excel/PDF
- Status follow-up pemesanan

### 3.8 Laporan (Reports)

**Deskripsi:** Sistem pelaporan komprehensif dengan multiple format output.

**Jenis Laporan:**

| Laporan | Konten | Format |
|---------|--------|--------|
| Laporan Penjualan | Ringkasan penjualan per periode | PDF, Excel, Chart |
| Laporan Stok | Posisi stok saat ini + pergerakan | PDF, Excel |
| Laporan Laba Kotor | Pendapatan - HPP per periode | PDF, Excel, Chart |
| Laporan Produk Terlaris | Ranking produk by qty/revenue | PDF, Chart |
| Laporan EOQ | Rekomendasi pemesanan optimal | PDF, Excel |
| Laporan Supplier | Performa dan riwayat per supplier | PDF |
| Laporan Pergerakan Stok | In/Out/Adjustment per periode | PDF, Excel |

**Fitur:**
- Filter periode: harian, mingguan, bulanan, tahunan, custom range
- Preview laporan sebelum export
- Dashboard chart (Chart.js) untuk visualisasi
- Export PDF (DomPDF / Snappy)
- Export Excel (Laravel Excel / Maatwebsite)
- Print langsung dari browser

---

## 4. Non-Functional Requirements

| Requirement | Specification |
|-------------|---------------|
| **Performance** | Halaman load < 2 detik |
| **Browser** | Chrome, Firefox, Edge (terbaru) |
| **Responsive** | Desktop primary, tablet support |
| **Database** | MySQL 8.0+ |
| **PHP** | PHP 8.2+ |
| **Laravel** | Laravel 11.x |
| **Security** | CSRF, XSS protection, input validation |
| **Backup** | Database backup manual/scheduled |
| **Locale** | Bahasa Indonesia (UI), format Rupiah (IDR) |

---

## 5. User Stories

### Dashboard
- **US-001:** Sebagai admin, saya ingin melihat ringkasan bisnis di dashboard agar cepat memahami kondisi terkini.
- **US-002:** Sebagai admin, saya ingin melihat produk dengan stok menipis agar bisa segera memesan ulang.

### Barang
- **US-010:** Sebagai admin, saya ingin menambah produk baru dengan detail lengkap.
- **US-011:** Sebagai admin, saya ingin mengedit dan menghapus produk yang sudah tidak dijual.
- **US-012:** Sebagai admin, saya ingin mencari dan memfilter produk berdasarkan kategori.
- **US-013:** Sebagai admin, saya ingin mengimport data produk via Excel untuk setup awal.

### Supplier
- **US-020:** Sebagai admin, saya ingin mengelola data supplier lengkap dengan kontak.
- **US-021:** Sebagai admin, saya ingin melihat produk apa saja yang dipasok oleh supplier tertentu.

### Transaksi
- **US-030:** Sebagai admin, saya ingin mencatat penjualan dengan multi-item sekaligus.
- **US-031:** Sebagai admin, saya ingin stok otomatis berkurang setelah transaksi berhasil.
- **US-032:** Sebagai admin, saya ingin mencetak invoice/struk penjualan.
- **US-033:** Sebagai admin, saya ingin membatalkan transaksi dan stok kembali otomatis.

### Stok & EOQ
- **US-040:** Sebagai admin, saya ingin melihat rekomendasi jumlah pemesanan optimal (EOQ).
- **US-041:** Sebagai admin, saya ingin mendapat notifikasi ketika stok produk menipis.
- **US-042:** Sebagai admin, saya ingin melihat riwayat pergerakan stok setiap produk.

### Laporan
- **US-050:** Sebagai admin, saya ingin melihat laporan penjualan dalam bentuk chart interaktif.
- **US-051:** Sebagai admin, saya ingin mengexport laporan ke PDF dan Excel.
- **US-052:** Sebagai admin, saya ingin memfilter laporan berdasarkan periode custom.

---

## 6. Success Metrics

| Metric | Target |
|--------|--------|
| Waktu pencatatan transaksi | < 30 detik per transaksi |
| Akurasi stok | 100% sinkron dengan transaksi |
| Penghematan biaya inventory (EOQ) | Terukur via laporan EOQ |
| User satisfaction | Admin merasa mudah mengoperasikan |

---

## 7. Release Plan

### Phase 1 — MVP (Sprint 1-3)
- [x] Setup proyek Laravel + Auth
- [ ] Dashboard dasar
- [ ] CRUD Kategori
- [ ] CRUD Barang
- [ ] CRUD Supplier

### Phase 2 — Core (Sprint 4-6)
- [ ] Pencatatan Transaksi Penjualan
- [ ] Riwayat Penjualan
- [ ] Manajemen Stok + Pergerakan

### Phase 3 — Intelligence (Sprint 7-8)
- [ ] Implementasi Algoritma EOQ
- [ ] Data Kebutuhan Barang
- [ ] Stok Alert System

### Phase 4 — Reports (Sprint 9-10)
- [ ] Laporan Penjualan (Chart + PDF + Excel)
- [ ] Laporan Stok
- [ ] Laporan Laba Kotor
- [ ] Laporan EOQ

### Phase 5 — Polish (Sprint 11-12)
- [ ] UI/UX refinement
- [ ] Performance optimization
- [ ] Testing & bug fixing
- [ ] Documentation

---

## 8. Out of Scope (v1.0)

- Multi-user / multi-role access
- Multi-cabang / multi-gudang
- Integrasi marketplace (Tokopedia, Shopee)
- Integrasi payment gateway
- Mobile app (native)
- Reorder Point (ROP) dan Safety Stock (akan ditambahkan di v2.0)
- Barcode/QR Code scanning

---

## 9. Glossary

| Term | Definition |
|------|------------|
| **EOQ** | Economic Order Quantity — jumlah pemesanan optimal yang meminimalkan total biaya inventory |
| **SKU** | Stock Keeping Unit — kode unik per produk |
| **HPP** | Harga Pokok Penjualan — biaya langsung per produk |
| **Ordering Cost** | Biaya yang dikeluarkan setiap kali melakukan pemesanan |
| **Holding Cost** | Biaya penyimpanan per unit produk per tahun |
| **UMKM** | Usaha Mikro, Kecil, dan Menengah |

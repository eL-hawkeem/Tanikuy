# DESIGN.md — Tanikuy Semantic Design System

> **Project:** Tanikuy Inventory & EOQ Management System  
> **Version:** 1.0.0  
> **Design Philosophy:** Modern Executive, Forest & Neon Precision, Clean Component Architecture  
> **CSS Framework:** Bootstrap 5.3.8 + Vanilla CSS Design Tokens (`public/assets/css/main.css`)  
> **Icon System:** Bootstrap Icons (`bi bi-*`)  
> **Typography:** Plus Jakarta Sans (Google Fonts)

---

## 1. Visual Theme & Atmosphere

Tanikuy mengadopsi estetika **Modern Executive Agriculture**: memadukan nuansa alami tanah & dedaunan pertanian dengan ketelitian analitik modern. 

Sistem ini menghindari warna generik default dengan memasangkan palet **Deep Organic Forest** (`#051C12`, `#072F1F`) dengan aksen **Electric Neon Lime** (`#B4F105`) di atas kanvas **Warm Grayish-Green** (`#F4F6F5`).

### Prinsip Utama Desain
1. **Controlled Density:** Spasi bernapas (grid gutter 24px) menyeimbangkan kepadatan data analitik stok dan transaksi sehingga mudah dipindai mata pengelola UMKM.
2. **Modern Soft Geometry:** Sudut kartu dan komponen interaktif dibentuk halus mulai dari `--radius-md` (10px) hingga `--radius-xl` (18px) dan pill badges, menghindari sudut kaku 90 derajat.
3. **Whisper-Soft Elevation:** Kedalaman antarmuka menggunakan diffused box shadows bertingkat (`rgba(11, 19, 15, 0.02)` s.d. `0.08`), memberikan efek melayang yang elegan tanpa visual clutter.
4. **Signature Brand Accents:**
   - Ikon geometris bintang 6-sudut (`bi bi-asterisk`) sebagai emblem identitas Tanikuy.
   - Efek pendaran cahaya melingkar (*ambient radial glow*) pada portal login.
   - Kartu statistik berlatar belakang Deep Forest dengan aksen angka neon lime.

---

## 2. Color Palette & Design Tokens

### 2.1 Core Brand Colors

| Token Name | Hex Code | Visual Description | Semantic & Functional Role |
| :--- | :--- | :--- | :--- |
| `--brand-forest-dark` | `#051C12` | Deep Obsidian Forest | Latar belakang sidebar, identitas brand, kontras tinggi |
| `--brand-forest-medium` | `#072F1F` | Deep Emerald Pine | Kartu pengumuman, tombol primer, seri utama grafik |
| `--brand-forest-light` | `#1A3E30` | Muted Cypress Green | Elemen interaktif pada mode gelap, latar item aktif |
| `--brand-lime` | `#B4F105` | Electric Neon Lime | Aksen visibilitas tinggi, indikator aktif, tombol CTA, grafik sorotan |
| `--brand-lime-hover` | `#C1F824` | Bright Neon Chartreuse | Status hover untuk tombol lime dan cincin fokus |
| `--brand-lime-translucent`| `rgba(180, 241, 5, 0.15)` | Translucent Lime Tint | Latar belakang badge, ring fokus input, wadah ikon |

### 2.2 Neutral & Surface Canvas Colors

| Token Name | Hex Code | Visual Description | Semantic & Functional Role |
| :--- | :--- | :--- | :--- |
| `--bs-body-bg` | `#F4F6F5` | Warm Pale Sage Gray | Kanvas latar belakang global; lembut di mata, mengurangi silau putih |
| `--card-background` | `#FFFFFF` | Pure Crisp White | Permukaan kartu analitik, tabel data, form, dan widget |
| `--border-light` | `#E9EFEF` | Soft Pale Mineral Gray | Batas kartu standar, pembagi tabel, garis batas input |
| `--border-dark-green`| `rgba(255, 255, 255, 0.1)`| Translucent White Mist | Garis batas di dalam sidebar dan kartu gelap |

### 2.3 Typography & Text Roles

| Token Name | Hex Code | Visual Description | Semantic & Functional Role |
| :--- | :--- | :--- | :--- |
| `--text-main` | `#0B130F` | Deep Charcoal Black | Judul utama (H1–H6), angka metrik stat, label tabel |
| `--text-muted-green`| `#6C7E75` | Muted Sage Slate | Label sekunder, breadcrumb, keterangan tabel, teks pembantu |
| `--text-sidebar-muted`| `#879A91` | Silver-Green Muted | Menu sidebar tidak aktif, judul seksi, versi rilis |

### 2.4 Semantic Feedback & Status Tokens

| Status | Primary Color | Background Tint | Functional Role |
| :--- | :--- | :--- | :--- |
| **Success / Aman** | `--sys-green` (`#22C55E`) | `--sys-green-bg` (`#DCFCE7`) | Stok aman (>150% min), transaksi selesai, trend positif |
| **Danger / Kritis** | `--sys-red` (`#EF4444`) | `--sys-red-bg` (`#FEE2E2`) | Stok kritis (&le; min / butuh EOQ), transaksi batal, hapus data |
| **Warning / Menipis**| `--sys-orange` (`#F97316`) | `--sys-orange-bg` (`#FFEDD5`)| Stok menipis (100–150% min), stock opname penyesuaian |

---

## 3. Typography Hierarchy & Rules

### Font Specification
- **Primary Font Family:** `'Plus Jakarta Sans', sans-serif` (Google Fonts)
- **Bobot Didukung:** Regular (`400`), Medium (`500`), Semi-Bold (`600`), Bold (`700`), Extra-Bold (`800`)
- **Global Letter Spacing:** `-0.01em` (Body), `-0.025em` (Headings)

### Type Scale

| Element | Size | Weight | Line Height | Usage Context |
| :--- | :--- | :--- | :--- | :--- |
| **Page Title (H1)** | `1.65rem (26.4px)` | `700` | `1.25` | Judul teratas setiap halaman (Dashboard, Katalog, Kasir) |
| **Card Title (H2/H5)**| `1.15rem (18.4px)` | `700` | `1.3` | Judul bagian di dalam kartu & widget analitik |
| **Card Stat (Amount)**| `1.85rem (29.6px)` | `800` | `1.1` | Angka omset penjualan, total transaksi, total stok |
| **Section Label** | `0.75rem (12px)` | `700` | `1.4` | Header seksi sidebar (`UTAMA`, `INVENTORY & EOQ`) |
| **Body Regular** | `0.925rem (14.8px)` | `500` | `1.5` | Teks paragraf standar, baris tabel |
| **Caption / Helper** | `0.80rem (12.8px)` | `500` | `1.4` | Sub-judul halaman, keterangan SKU, timestamp |
| **Badge Micro** | `0.75rem (12px)` | `700` | `1.3` | Badge status, pill tags, trend persen |

---

## 4. Spacing, Geometry & Elevation

### 4.1 Border Radius Scale
- `--radius-sm`: `6px` — Tag kecil, tombol micro
- `--radius-md`: `10px` — Input form standar, tombol aksi, filter bar
- `--radius-lg`: `14px` — Dropdown menu, panel navigasi, modal dialog
- `--radius-xl`: `18px` — Kartu standar analitik, container tabel
- `--radius-xxl`: `24px` — Kartu promo besar, kartu autentikasi login

### 4.2 Elevation Tokens
- `--shadow-sm`: `0 2px 8px rgba(11, 19, 15, 0.02)` — Batas kartu flat standar
- `--shadow-md`: `0 10px 30px rgba(11, 19, 15, 0.04)` — Kartu elevated, status hover
- `--shadow-lg`: `0 20px 50px rgba(11, 19, 15, 0.08)` — Modal dialog, kartu login

---

## 5. Layout Architecture & System Shell

### 5.1 Struktur Grid Shell

```
+----------------------------------------------------------------------------------------------------+
| SIDEBAR (Sticky 280px Desktop) | TOP NAVBAR (Sticky 80px)                                          |
| [*] Tanikuy                    | [Transaksi Baru]   [Tanggal & Jam]       [Alert Bell] [User Drop] |
|                                +-------------------------------------------------------------------+
| UTAMA                          | PAGE HEADER BANNER                                                |
| • Dashboard                    | Judul Halaman + Breadcrumb / Tombol Aksi Tambah                   |
| • Kasir / Penjualan (POS)      +-------------------------------------------------------------------+
| • Riwayat Penjualan            | TOP STATS ROW (Row g-4)                                           |
|                                | [Status Inventory] [Omset Bulan Ini] [Omset Hari Ini] [Total SKU] |
| INVENTORY & EOQ                +-------------------------------------------------------------------+
| • Kelola Barang                | MAIN ANALYTICS / DATA CANVAS                                      |
| • Kategori Produk              | [Col-8: ApexCharts Area Sales + Tabel Stok Kritis & Rekomendasi]  |
| • Kelola Supplier              | [Col-4: Ranking Produk Terlaris + Transaksi Terakhir]             |
| • Pemantauan Stok              +-------------------------------------------------------------------+
| • Algoritma EOQ                | FOOTER: Tanikuy &copy; 2026 &bull; Spark Admin Design Tokens      |
+----------------------------------------------------------------------------------------------------+
```

### 5.2 Responsive Breakpoints
- **Desktop (&ge; 1200px):** Sidebar tetap 280px di sebelah kiri, main wrapper `margin-left: 280px`.
- **Tablet / Mobile (< 1200px):** Sidebar menjadi off-canvas drawer, padding konten menyesuaikan untuk kenyamanan layar sentuh.

---

## 6. Spesifikasi Komponen Inti

### 6.1 Sidebar Navigation (`.sidebar-wrapper`)
- Latar Belakang: `--brand-forest-dark` (`#051C12`) dengan batas kanan `1px solid rgba(255, 255, 255, 0.1)`.
- Brand Logo: Ikon `bi bi-asterisk` neon lime (`#B4F105`), teks Tanikuy tebal putih.
- Item Menu: Padding `0.75rem 0.85rem`, border radius `--radius-md`.
- Status Aktif: Indikator vertikal neon lime di sisi kiri, latar putih transparan 5%.
- Dock Profil Bawah: Wadah foto profil, nama admin, email, dan tombol cepat logout.

### 6.2 Top Navbar (`.navbar-custom`)
- Posisi sticky atas dengan efek kaca `backdrop-filter: blur(12px)` pada `#F4F6F5`.
- Tombol aksi cepat *"Transaksi Baru"* berwarna Emerald dengan ikon panah.
- Indikator lonceng notifikasi menampilkan badge merah jika ada produk di bawah stok minimum.

### 6.3 Kartu Statistik & Banner Alert
- `.card-custom`: Permukaan putih, border `--border-light`, radius `--radius-xl`.
- Kartu Hero Gelap: Latar `--brand-forest-dark`, teks putih kontras tinggi dengan logo bintang samar di sudut kanan.
- Kartu Trend: Menampilkan angka metrik besar dengan pill hijau/merah.

### 6.4 Data Tables (`.table-card-custom` & `.table-hover`)
- Pembungkus tabel dengan header bar pencarian dan filter kategori dropdown.
- Header tabel berlatar abu-abu terang dengan teks uppercase kecil (`0.75rem`).
- Baris tabel mendukung efek hover lembut.
- Tombol aksi berupa ikon 32px (Edit, EOQ, Hapus).

### 6.5 Form Controls & Validasi
- `.form-control`: Border radius 10px, padding nyaman untuk interaksi kasir.
- Status fokus: Cincin cahaya halus dengan warna aksen neon lime (`box-shadow: 0 0 0 3px rgba(180, 241, 5, 0.2)`).
- Pesan kesalahan validasi tampil tepat di bawah input dengan warna merah terang.

### 6.6 Grafik Analitik (ApexCharts)
- Seri grafik penjualan harian menggunakan palet Deep Emerald (`#072F1F`) dengan gradien isi Neon Lime (`#B4F105`).
- Titik marker interaktif memantulkan angka nominal dalam format Rupiah (`Rp xxx.xxx`).
- Garis grid halus abu-abu putus-putus (`strokeDashArray: 4`).

---

## 7. Aturan Penerapan & Anti-Slop (Quality Standards)

1. **Zero Uncontrolled Slop:**
   - Dilarang menggunakan gradien ungu-biru generik AI. Seluruh palet wajib mengikuti token brand Tanikuy: Forest (`#051C12`), Pine (`#072F1F`), dan Lime (`#B4F105`).
   - Dilarang menggunakan emoji sebagai pengganti ikon UI. Seluruh ikon wajib menggunakan Bootstrap Icons SVG (`bi bi-*`).
2. **Strict Accessibility & Contrast:**
   - Teks pada kanvas `#F4F6F5` wajib memiliki rasio kontras &ge; 4.5:1 (menggunakan `--text-main` `#0B130F` atau `--text-muted-green` `#6C7E75`).
   - Teks pada kanvas gelap wajib menggunakan Putih Murni atau Neon Lime.
3. **Penyimpanan Aset Operasional:**
   - Seluruh aset runtime aplikasi (CSS, JS, Fonts, Icons) disimpan di direktori `public/assets/` sehingga aplikasi dapat langsung berjalan tanpa build step tambahan.
   - Semua aturan desain dipelihara secara terpusat pada file dokumen ini: `docs/DESIGN.md`.

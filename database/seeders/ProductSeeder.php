<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::pluck('id', 'nama');
        $suppliers = Supplier::pluck('id', 'kode_supplier');

        $products = [
            [
                'kode_barang' => 'TK-0001',
                'nama_barang' => 'Beras Pandan Wangi Super 5kg',
                'kategori_nama' => 'Beras & Biji-bijian',
                'supplier_kode' => 'SUP-0002',
                'satuan' => 'karung',
                'harga_beli' => 68000,
                'harga_jual' => 78000,
                'stok_saat_ini' => 45,
                'stok_minimum' => 20,
                'biaya_pemesanan' => 50000,
                'biaya_penyimpanan' => 3500,
                'deskripsi' => 'Beras pulen beraroma wangi alami khas Cianjur kualitas premium kemasan 5kg.',
            ],
            [
                'kode_barang' => 'TK-0002',
                'nama_barang' => 'Beras Rojolele Delanggu 10kg',
                'kategori_nama' => 'Beras & Biji-bijian',
                'supplier_kode' => 'SUP-0002',
                'satuan' => 'karung',
                'harga_beli' => 135000,
                'harga_jual' => 152000,
                'stok_saat_ini' => 12, // Kritis (stok <= stok_minimum)
                'stok_minimum' => 15,
                'biaya_pemesanan' => 50000,
                'biaya_penyimpanan' => 6500,
                'deskripsi' => 'Beras putih bersih tanpa pemutih kemasan karung 10kg.',
            ],
            [
                'kode_barang' => 'TK-0003',
                'nama_barang' => 'Pupuk NPK Mutiara 16-16-16 (1kg)',
                'kategori_nama' => 'Pupuk Organik & Kimia',
                'supplier_kode' => 'SUP-0001',
                'satuan' => 'pack',
                'harga_beli' => 18000,
                'harga_jual' => 23000,
                'stok_saat_ini' => 85,
                'stok_minimum' => 30,
                'biaya_pemesanan' => 45000,
                'biaya_penyimpanan' => 1200,
                'deskripsi' => 'Pupuk majemuk seimbang untuk memacu pertumbuhan vegetatif dan generatif.',
            ],
            [
                'kode_barang' => 'TK-0004',
                'nama_barang' => 'Pupuk Urea Petrokarang 50kg',
                'kategori_nama' => 'Pupuk Organik & Kimia',
                'supplier_kode' => 'SUP-0001',
                'satuan' => 'karung',
                'harga_beli' => 320000,
                'harga_jual' => 360000,
                'stok_saat_ini' => 8, // Kritis
                'stok_minimum' => 10,
                'biaya_pemesanan' => 75000,
                'biaya_penyimpanan' => 15000,
                'deskripsi' => 'Pupuk nitrogen tinggi 46% untuk percepatan anakan padi dan jagung.',
            ],
            [
                'kode_barang' => 'TK-0005',
                'nama_barang' => 'Benih Padi Ciherang Unggul 5kg',
                'kategori_nama' => 'Benih & Bibit Pertanian',
                'supplier_kode' => 'SUP-0002',
                'satuan' => 'pack',
                'harga_beli' => 75000,
                'harga_jual' => 90000,
                'stok_saat_ini' => 35,
                'stok_minimum' => 15,
                'biaya_pemesanan' => 40000,
                'biaya_penyimpanan' => 3800,
                'deskripsi' => 'Daya kecambah 85%+, tahan wereng coklat biotipe 2, hasil tinggi.',
            ],
            [
                'kode_barang' => 'TK-0006',
                'nama_barang' => 'Benih Jagung Hibrida Pioneer P27 (1kg)',
                'kategori_nama' => 'Benih & Bibit Pertanian',
                'supplier_kode' => 'SUP-0002',
                'satuan' => 'pack',
                'harga_beli' => 95000,
                'harga_jual' => 115000,
                'stok_saat_ini' => 18,
                'stok_minimum' => 15, // Peringatan
                'biaya_pemesanan' => 40000,
                'biaya_penyimpanan' => 4500,
                'deskripsi' => 'Tongkol besar rapat, rendemen tinggi, cocok untuk lahan tadah hujan.',
            ],
            [
                'kode_barang' => 'TK-0007',
                'nama_barang' => 'Insektisida Regent 50 SC (250ml)',
                'kategori_nama' => 'Pestisida & Proteksi Tanaman',
                'supplier_kode' => 'SUP-0004',
                'satuan' => 'botol',
                'harga_beli' => 62000,
                'harga_jual' => 75000,
                'stok_saat_ini' => 28,
                'stok_minimum' => 10,
                'biaya_pemesanan' => 35000,
                'biaya_penyimpanan' => 3000,
                'deskripsi' => 'Insektisida sistemik spektrum luas dengan ZPT penambah hijau daun.',
            ],
            [
                'kode_barang' => 'TK-0008',
                'nama_barang' => 'Fungisida Score 250 EC (100ml)',
                'kategori_nama' => 'Pestisida & Proteksi Tanaman',
                'supplier_kode' => 'SUP-0004',
                'satuan' => 'botol',
                'harga_beli' => 78000,
                'harga_jual' => 92000,
                'stok_saat_ini' => 6, // Kritis
                'stok_minimum' => 10,
                'biaya_pemesanan' => 35000,
                'biaya_penyimpanan' => 4000,
                'deskripsi' => 'Pencegah bercak daun dan blast leher malai pada tanaman padi.',
            ],
            [
                'kode_barang' => 'TK-0009',
                'nama_barang' => 'Tangki Semprot Elektrik Swan 16 Liter',
                'kategori_nama' => 'Peralatan & Mesin Pertanian',
                'supplier_kode' => 'SUP-0003',
                'satuan' => 'pcs',
                'harga_beli' => 420000,
                'harga_jual' => 495000,
                'stok_saat_ini' => 14,
                'stok_minimum' => 5,
                'biaya_pemesanan' => 60000,
                'biaya_penyimpanan' => 20000,
                'deskripsi' => 'Sprayer elektrik baterai tahan 8 jam kerja, nozel kuningan lengkap 4 pcs.',
            ],
            [
                'kode_barang' => 'TK-0010',
                'nama_barang' => 'Cangkul Baja Asli Cap Buaya',
                'kategori_nama' => 'Peralatan & Mesin Pertanian',
                'supplier_kode' => 'SUP-0003',
                'satuan' => 'pcs',
                'harga_beli' => 85000,
                'harga_jual' => 110000,
                'stok_saat_ini' => 22,
                'stok_minimum' => 8,
                'biaya_pemesanan' => 50000,
                'biaya_penyimpanan' => 4000,
                'deskripsi' => 'Cangkul baja tempa pandai besi lokal tahan benturan batu dan tanah liat.',
            ],
        ];

        foreach ($products as $p) {
            $catId = $categories[$p['kategori_nama']] ?? null;
            $supId = $suppliers[$p['supplier_kode']] ?? null;

            $product = Product::updateOrCreate(
                ['kode_barang' => $p['kode_barang']],
                [
                    'nama_barang' => $p['nama_barang'],
                    'kategori_id' => $catId,
                    'supplier_id' => $supId,
                    'satuan' => $p['satuan'],
                    'harga_beli' => $p['harga_beli'],
                    'harga_jual' => $p['harga_jual'],
                    'stok_saat_ini' => $p['stok_saat_ini'],
                    'stok_minimum' => $p['stok_minimum'],
                    'biaya_pemesanan' => $p['biaya_pemesanan'],
                    'biaya_penyimpanan' => $p['biaya_penyimpanan'],
                    'deskripsi' => $p['deskripsi'],
                    'status' => 'active',
                ]
            );

            // Buat audit log saldo awal di stock_movements jika belum ada
            if ($product->wasRecentlyCreated) {
                StockMovement::create([
                    'product_id' => $product->id,
                    'tipe' => 'in',
                    'jumlah' => $product->stok_saat_ini,
                    'stok_sebelum' => 0,
                    'stok_sesudah' => $product->stok_saat_ini,
                    'referensi' => 'INIT-STOCK',
                    'keterangan' => 'Saldo awal stok sistem',
                ]);
            }
        }
    }
}

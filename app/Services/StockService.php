<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StockService
{
    /**
     * Kurangi stok produk (misal karena penjualan)
     */
    public function reduceStock(Product $product, int $quantity, string $reference, ?string $keterangan = null): void
    {
        DB::transaction(function () use ($product, $quantity, $reference, $keterangan) {
            $product->refresh();

            if ($product->stok_saat_ini < $quantity) {
                throw new Exception("Stok untuk {$product->nama_barang} tidak mencukupi. Tersedia: {$product->stok_saat_ini}, Diminta: {$quantity}");
            }

            $before = $product->stok_saat_ini;
            $after = $before - $quantity;

            $product->update(['stok_saat_ini' => $after]);

            StockMovement::create([
                'product_id' => $product->id,
                'tipe' => 'out',
                'jumlah' => $quantity,
                'stok_sebelum' => $before,
                'stok_sesudah' => $after,
                'referensi' => $reference,
                'keterangan' => $keterangan ?? "Pengurangan stok transaksi {$reference}",
            ]);
        });
    }

    /**
     * Tambah stok produk (misal barang masuk dari supplier atau restock EOQ)
     */
    public function addStock(Product $product, int $quantity, string $reference, ?string $keterangan = null): void
    {
        DB::transaction(function () use ($product, $quantity, $reference, $keterangan) {
            $product->refresh();
            $before = $product->stok_saat_ini;
            $after = $before + $quantity;

            $product->update(['stok_saat_ini' => $after]);

            StockMovement::create([
                'product_id' => $product->id,
                'tipe' => 'in',
                'jumlah' => $quantity,
                'stok_sebelum' => $before,
                'stok_sesudah' => $after,
                'referensi' => $reference,
                'keterangan' => $keterangan ?? "Penerimaan stok {$reference}",
            ]);
        });
    }

    /**
     * Penyesuaian stok manual (Stock Opname / Adjustment)
     */
    public function adjustStock(Product $product, int $newStock, ?string $keterangan = null): void
    {
        DB::transaction(function () use ($product, $newStock, $keterangan) {
            $product->refresh();
            $before = $product->stok_saat_ini;
            $diff = $newStock - $before;

            $product->update(['stok_saat_ini' => $newStock]);

            StockMovement::create([
                'product_id' => $product->id,
                'tipe' => 'adjustment',
                'jumlah' => abs($diff),
                'stok_sebelum' => $before,
                'stok_sesudah' => $newStock,
                'referensi' => 'ADJ-' . date('Ymd-His'),
                'keterangan' => $keterangan ?? "Penyesuaian stok manual dari {$before} menjadi {$newStock}",
            ]);
        });
    }

    /**
     * Daftar produk stok menipis (kritis & peringatan)
     */
    public function getLowStockProducts(): Collection
    {
        return Product::with(['kategori', 'supplier'])
            ->where('status', 'active')
            ->whereRaw('stok_saat_ini <= (stok_minimum * 1.5)')
            ->orderBy('stok_saat_ini', 'asc')
            ->get();
    }
}

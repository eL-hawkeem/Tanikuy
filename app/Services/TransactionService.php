<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Exception;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function __construct(
        protected StockService $stockService
    ) {}

    /**
     * Catat transaksi penjualan baru dan kurangi stok barang secara otomatis
     *
     * @param array $data ['tanggal', 'nama_pelanggan', 'metode_pembayaran', 'diskon', 'catatan']
     * @param array $items [['product_id' => int, 'jumlah' => int]]
     */
    public function createTransaction(array $data, array $items): Transaction
    {
        return DB::transaction(function () use ($data, $items) {
            if (empty($items)) {
                throw new Exception('Transaksi harus memiliki minimal 1 barang.');
            }

            // Generate nomor invoice unik: INV-YYYYMMDD-XXXX
            $dateStr = date('Ymd');
            $countToday = Transaction::whereDate('created_at', today())->count() + 1;
            $invoiceNumber = 'INV-' . $dateStr . '-' . str_pad($countToday, 4, '0', STR_PAD_LEFT);

            $subtotal = 0;
            $preparedDetails = [];

            // 1. Validasi stok semua item & hitung subtotal
            foreach ($items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $qty = (int) $item['jumlah'];

                if ($qty <= 0) {
                    throw new Exception("Jumlah barang {$product->nama_barang} harus lebih dari 0.");
                }

                if ($product->stok_saat_ini < $qty) {
                    throw new Exception("Stok {$product->nama_barang} tidak mencukupi (Tersedia: {$product->stok_saat_ini}, Diminta: {$qty}).");
                }

                $itemSubtotal = $qty * (float) $product->harga_jual;
                $subtotal += $itemSubtotal;

                $preparedDetails[] = [
                    'product' => $product,
                    'jumlah' => $qty,
                    'harga_satuan' => (float) $product->harga_jual,
                    'subtotal' => $itemSubtotal,
                ];
            }

            $diskon = isset($data['diskon']) ? (float) $data['diskon'] : 0;
            $total = max(0, $subtotal - $diskon);

            // 2. Buat header transaksi
            $transaction = Transaction::create([
                'nomor_transaksi' => $invoiceNumber,
                'tanggal' => $data['tanggal'] ?? now(),
                'nama_pelanggan' => $data['nama_pelanggan'] ?? 'Pelanggan Umum',
                'metode_pembayaran' => $data['metode_pembayaran'] ?? 'cash',
                'subtotal' => $subtotal,
                'diskon' => $diskon,
                'total' => $total,
                'status' => 'completed',
                'catatan' => $data['catatan'] ?? null,
            ]);

            // 3. Simpan detail dan kurangi stok
            foreach ($preparedDetails as $detail) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $detail['product']->id,
                    'jumlah' => $detail['jumlah'],
                    'harga_satuan' => $detail['harga_satuan'],
                    'subtotal' => $detail['subtotal'],
                ]);

                // Kurangi stok via StockService
                $this->stockService->reduceStock(
                    $detail['product'],
                    $detail['jumlah'],
                    $invoiceNumber,
                    "Penjualan transaksi {$invoiceNumber}"
                );
            }

            return $transaction;
        });
    }

    /**
     * Batalkan transaksi dan kembalikan stok
     */
    public function cancelTransaction(Transaction $transaction): void
    {
        DB::transaction(function () use ($transaction) {
            if ($transaction->status === 'cancelled') {
                throw new Exception('Transaksi ini sudah dibatalkan sebelumnya.');
            }

            $transaction->load('details.product');

            foreach ($transaction->details as $detail) {
                if ($detail->product) {
                    $this->stockService->addStock(
                        $detail->product,
                        $detail->jumlah,
                        $transaction->nomor_transaksi,
                        "Pengembalian stok pembatalan transaksi {$transaction->nomor_transaksi}"
                    );
                }
            }

            $transaction->update(['status' => 'cancelled']);
        });
    }
}

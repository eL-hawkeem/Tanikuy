<?php

namespace App\Services;

use App\Models\EoqCalculation;
use App\Models\Product;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EoqService
{
    /**
     * Hitung EOQ untuk suatu produk berdasarkan data riil penjualan atau estimasi
     */
    public function calculate(Product $product): array
    {
        $oneYearAgo = Carbon::now()->subYear();
        
        // 1. Hitung total penjualan 12 bulan terakhir
        $salesData = TransactionDetail::query()
            ->join('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->where('transaction_details.product_id', $product->id)
            ->where('transactions.status', 'completed')
            ->where('transactions.tanggal', '>=', $oneYearAgo)
            ->selectRaw('
                COALESCE(SUM(transaction_details.jumlah), 0) as total_qty,
                COUNT(DISTINCT transactions.id) as total_tx,
                MIN(transactions.tanggal) as first_tx,
                MAX(transactions.tanggal) as last_tx
            ')
            ->first();

        $totalQty = (float) ($salesData->total_qty ?? 0);
        $annualDemand = $totalQty;

        // Jika data penjualan kurang dari setahun tapi ada transaksi, lakukan ekstrapolasi tahunan
        if ($salesData && $salesData->first_tx && $salesData->last_tx) {
            $first = Carbon::parse($salesData->first_tx);
            $last = Carbon::parse($salesData->last_tx);
            $daysSpan = max(1, $first->diffInDays($last));

            if ($daysSpan > 0 && $daysSpan < 365 && $totalQty > 0) {
                $annualDemand = ($totalQty / $daysSpan) * 365;
            }
        }

        // Jika produk baru atau belum ada penjualan sama sekali, gunakan estimasi dari stok minimum x 12
        if ($annualDemand <= 0) {
            $annualDemand = max(10, (float) $product->stok_minimum * 12);
        }

        $orderingCost = (float) $product->biaya_pemesanan;
        $holdingCost = (float) $product->biaya_penyimpanan;

        // Fallback default jika parameter belum diisi
        if ($orderingCost <= 0) {
            $orderingCost = 50000; // Biaya pesan standar Rp 50.000
        }
        if ($holdingCost <= 0) {
            $holdingCost = max(500, (float) $product->harga_beli * 0.05); // 5% dari harga beli
        }

        // Formula: EOQ = sqrt((2 * D * S) / H)
        $eoqQty = sqrt((2 * $annualDemand * $orderingCost) / $holdingCost);
        $eoqQty = round(max(1, $eoqQty));

        $orderFrequency = round($annualDemand / $eoqQty, 2);
        $totalOptimalCost = round(($annualDemand / $eoqQty) * $orderingCost + ($eoqQty / 2) * $holdingCost, 2);
        $dailyDemand = round($annualDemand / 365, 2);

        // Rekomendasi teks
        $needsReorder = $product->stok_saat_ini <= $product->stok_minimum;
        $recommendation = $needsReorder
            ? "Stok ({$product->stok_saat_ini} {$product->satuan}) mencapai batas minimum ({$product->stok_minimum} {$product->satuan}). Segera pesan sejumlah {$eoqQty} {$product->satuan}."
            : "Kondisi stok aman ({$product->stok_saat_ini} {$product->satuan}). Saat stok mencapai {$product->stok_minimum} {$product->satuan}, lakukan pemesanan optimal sebesar {$eoqQty} {$product->satuan}.";

        $result = [
            'product_id' => $product->id,
            'annual_demand' => round($annualDemand, 2),
            'ordering_cost' => $orderingCost,
            'holding_cost' => $holdingCost,
            'eoq_qty' => $eoqQty,
            'order_frequency' => $orderFrequency,
            'total_optimal_cost' => $totalOptimalCost,
            'daily_demand' => $dailyDemand,
            'rekomendasi' => $recommendation,
            'needs_reorder' => $needsReorder,
            'periode_mulai' => $oneYearAgo->toDateString(),
            'periode_akhir' => Carbon::now()->toDateString(),
        ];

        // Simpan log kalkulasi ke database
        EoqCalculation::create([
            'product_id' => $product->id,
            'annual_demand' => $result['annual_demand'],
            'ordering_cost' => $result['ordering_cost'],
            'holding_cost' => $result['holding_cost'],
            'eoq_qty' => $result['eoq_qty'],
            'order_frequency' => $result['order_frequency'],
            'total_optimal_cost' => $result['total_optimal_cost'],
            'daily_demand' => $result['daily_demand'],
            'rekomendasi' => $result['rekomendasi'],
            'periode_mulai' => $result['periode_mulai'],
            'periode_akhir' => $result['periode_akhir'],
        ]);

        return $result;
    }

    /**
     * Dapatkan daftar semua produk yang perlu di-reorder berdasarkan EOQ
     */
    public function getReorderList(): Collection
    {
        return Product::with(['kategori', 'supplier'])
            ->where('status', 'active')
            ->whereColumn('stok_saat_ini', '<=', 'stok_minimum')
            ->get()
            ->map(function (Product $product) {
                $calc = $this->calculate($product);
                $product->eoq_data = (object) $calc;
                $product->estimated_order_cost = $calc['eoq_qty'] * (float) $product->harga_beli;
                return $product;
            });
    }
}

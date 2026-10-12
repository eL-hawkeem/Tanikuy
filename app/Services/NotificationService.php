<?php

namespace App\Services;

use App\Models\EoqCalculation;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Transaction;
use App\Models\User;

class NotificationService
{
    /**
     * Ambil seluruh notifikasi sistem yang relevan untuk pengguna aktif
     */
    public function getNotifications(?User $user = null): array
    {
        $notifications = [];
        $user = $user ?? auth()->user();
        $isAdmin = $user ? $user->isAdmin() : false;

        // 1. Notifikasi Stok Kritis (Stok <= Stok Minimum)
        $criticalProducts = Product::where('status', 'active')
            ->whereColumn('stok_saat_ini', '<=', 'stok_minimum')
            ->orderBy('stok_saat_ini', 'asc')
            ->get();

        $lowStockCount = $criticalProducts->count();

        foreach ($criticalProducts as $product) {
            $isZero = $product->stok_saat_ini <= 0;
            $notifications[] = [
                'id' => 'stock_crit_' . $product->id,
                'type' => 'critical',
                'category' => 'Stok Kritis',
                'title' => ($isZero ? 'Stok Habis: ' : 'Stok Kritis: ') . $product->nama_barang,
                'message' => 'Tersisa ' . $product->stok_saat_ini . ' ' . $product->satuan . ' (Batas Min: ' . $product->stok_minimum . ' ' . $product->satuan . '). Segera restock!',
                'url' => route('stock.index', ['search' => $product->nama_barang, 'status_stok' => 'critical']),
                'icon' => 'bi bi-exclamation-octagon-fill',
                'icon_color' => 'text-danger',
                'bg_color' => 'bg-danger-subtle',
                'badge' => $isZero ? 'Habis' : 'Kritis',
                'badge_class' => 'bg-danger text-white',
                'time' => 'Perlu Tindakan',
                'timestamp' => now()->timestamp + 2000,
                'is_urgent' => true,
            ];
        }

        // 2. Notifikasi Stok Menipis (Stok > Minimum dan <= Minimum * 1.5)
        $warningProducts = Product::where('status', 'active')
            ->whereRaw('stok_saat_ini > stok_minimum AND stok_saat_ini <= (stok_minimum * 1.5)')
            ->orderBy('stok_saat_ini', 'asc')
            ->take(5)
            ->get();

        foreach ($warningProducts as $product) {
            $notifications[] = [
                'id' => 'stock_warn_' . $product->id,
                'type' => 'warning',
                'category' => 'Stok Menipis',
                'title' => 'Stok Menipis: ' . $product->nama_barang,
                'message' => 'Sisa ' . $product->stok_saat_ini . ' ' . $product->satuan . ' mendekati batas minimum (' . $product->stok_minimum . ' ' . $product->satuan . ').',
                'url' => route('stock.index', ['search' => $product->nama_barang, 'status_stok' => 'warning']),
                'icon' => 'bi bi-exclamation-triangle-fill',
                'icon_color' => 'text-warning',
                'bg_color' => 'bg-warning-subtle',
                'badge' => 'Menipis',
                'badge_class' => 'bg-warning text-dark',
                'time' => 'Peringatan',
                'timestamp' => now()->timestamp + 1000,
                'is_urgent' => false,
            ];
        }

        // 3. Notifikasi Transaksi POS Terkini
        $recentTransactions = Transaction::latest('created_at')
            ->take(5)
            ->get();

        foreach ($recentTransactions as $tx) {
            $isCancelled = $tx->status === 'cancelled';
            $notifications[] = [
                'id' => 'tx_' . $tx->id,
                'type' => 'transaction',
                'category' => 'Penjualan POS',
                'title' => ($isCancelled ? 'Transaksi Dibatalkan: #' : 'Penjualan: #') . $tx->nomor_transaksi,
                'message' => 'Rp ' . number_format($tx->total, 0, ',', '.') . ' • ' . ($tx->nama_pelanggan ?: 'Pelanggan Umum') . ' (' . strtoupper($tx->metode_pembayaran) . ')',
                'url' => route('transactions.show', $tx->id),
                'icon' => $isCancelled ? 'bi bi-x-circle-fill' : 'bi bi-receipt',
                'icon_color' => $isCancelled ? 'text-secondary' : 'text-success',
                'bg_color' => $isCancelled ? 'bg-secondary-subtle' : 'bg-success-subtle',
                'badge' => $isCancelled ? 'Batal' : 'POS',
                'badge_class' => $isCancelled ? 'bg-secondary text-white' : 'bg-success text-white',
                'time' => $tx->tanggal ? $tx->tanggal->diffForHumans() : $tx->created_at->diffForHumans(),
                'timestamp' => ($tx->tanggal ?? $tx->created_at)->timestamp,
                'is_urgent' => false,
            ];
        }

        // 4. Notifikasi Khusus Administrator (EOQ & Restock)
        if ($isAdmin) {
            // Analisis EOQ
            $eoqCalculations = EoqCalculation::with('product')
                ->whereHas('product', fn($q) => $q->where('status', 'active'))
                ->latest('created_at')
                ->take(3)
                ->get();

            foreach ($eoqCalculations as $eoq) {
                if (!$eoq->product) {
                    continue;
                }
                $notifications[] = [
                    'id' => 'eoq_' . $eoq->id,
                    'type' => 'eoq',
                    'category' => 'Analisis EOQ',
                    'title' => 'Saran EOQ: ' . $eoq->product->nama_barang,
                    'message' => 'Pemesanan optimal ' . round($eoq->eoq_qty) . ' ' . $eoq->product->satuan . ' (Frek: ' . $eoq->order_frequency . 'x/thn)',
                    'url' => route('eoq.index'),
                    'icon' => 'bi bi-cpu-fill',
                    'icon_color' => 'text-primary',
                    'bg_color' => 'bg-primary-subtle',
                    'badge' => 'EOQ',
                    'badge_class' => 'bg-primary text-white',
                    'time' => $eoq->created_at ? $eoq->created_at->diffForHumans() : 'Tersedia',
                    'timestamp' => $eoq->created_at ? $eoq->created_at->timestamp : now()->timestamp,
                    'is_urgent' => false,
                ];
            }

            // Barang Masuk / Restock Terbaru
            $movements = StockMovement::with('product')
                ->where('tipe', 'masuk')
                ->latest('created_at')
                ->take(3)
                ->get();

            foreach ($movements as $mv) {
                if (!$mv->product) {
                    continue;
                }
                $notifications[] = [
                    'id' => 'move_' . $mv->id,
                    'type' => 'restock',
                    'category' => 'Barang Masuk',
                    'title' => 'Barang Masuk: ' . $mv->product->nama_barang,
                    'message' => '+' . $mv->jumlah . ' ' . $mv->product->satuan . ' • Ref: ' . ($mv->referensi ?: '-'),
                    'url' => route('stock.movements', ['product_id' => $mv->product_id]),
                    'icon' => 'bi bi-box-arrow-in-down',
                    'icon_color' => 'text-info',
                    'bg_color' => 'bg-info-subtle',
                    'badge' => 'Restock',
                    'badge_class' => 'bg-info text-dark',
                    'time' => $mv->created_at ? $mv->created_at->diffForHumans() : 'Baru',
                    'timestamp' => $mv->created_at ? $mv->created_at->timestamp : now()->timestamp,
                    'is_urgent' => false,
                ];
            }
        }

        return [
            'items' => $notifications,
            'total_count' => count($notifications),
            'urgent_count' => count($criticalProducts),
            'low_stock_count' => $lowStockCount,
        ];
    }
}

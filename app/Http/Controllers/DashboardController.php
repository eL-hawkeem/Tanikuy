<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Services\EoqService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct(
        protected EoqService $eoqService
    ) {}

    public function index()
    {
        // 1. KPI Stats
        $totalProducts = Product::where('status', 'active')->count();
        $totalCategories = Category::where('is_active', true)->count();
        $totalSuppliers = Supplier::where('status', 'active')->count();
        
        $todayRevenue = Transaction::where('status', 'completed')
            ->whereDate('tanggal', today())
            ->sum('total');

        $monthRevenue = Transaction::where('status', 'completed')
            ->whereYear('tanggal', now()->year)
            ->whereMonth('tanggal', now()->month)
            ->sum('total');

        $totalTransactionsMonth = Transaction::where('status', 'completed')
            ->whereYear('tanggal', now()->year)
            ->whereMonth('tanggal', now()->month)
            ->count();

        // 2. Alert Produk Stok Kritis
        $criticalProducts = Product::with(['kategori', 'supplier'])
            ->where('status', 'active')
            ->whereColumn('stok_saat_ini', '<=', 'stok_minimum')
            ->orderBy('stok_saat_ini', 'asc')
            ->limit(6)
            ->get();

        // 3. Transaksi Terbaru
        $recentTransactions = Transaction::with('details.product')
            ->latest('tanggal')
            ->limit(6)
            ->get();

        // 4. Data Tren Penjualan 14 Hari Terakhir untuk ApexCharts
        $chartLabels = [];
        $chartSales = [];

        for ($i = 13; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateStr = $date->format('Y-m-d');
            $chartLabels[] = $date->translatedFormat('d M');

            $daySum = Transaction::where('status', 'completed')
                ->whereDate('tanggal', $dateStr)
                ->sum('total');

            $chartSales[] = (float) $daySum;
        }

        // 5. Produk Terlaris (Top 5)
        $topProducts = TransactionDetail::query()
            ->join('products', 'products.id', '=', 'transaction_details.product_id')
            ->join('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->where('transactions.status', 'completed')
            ->select(
                'products.nama_barang',
                'products.satuan',
                DB::raw('SUM(transaction_details.jumlah) as total_qty'),
                DB::raw('SUM(transaction_details.subtotal) as total_omset')
            )
            ->groupBy('products.id', 'products.nama_barang', 'products.satuan')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalSuppliers',
            'todayRevenue',
            'monthRevenue',
            'totalTransactionsMonth',
            'criticalProducts',
            'recentTransactions',
            'chartLabels',
            'chartSales',
            'topProducts'
        ));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->toDateString());

        // Daily breakdown
        $dailyReports = Transaction::where('status', 'completed')
            ->whereDate('tanggal', '>=', $startDate)
            ->whereDate('tanggal', '<=', $endDate)
            ->select(
                DB::raw('DATE(tanggal) as tgl'),
                DB::raw('COUNT(id) as total_tx'),
                DB::raw('SUM(total) as omset'),
                DB::raw('SUM(diskon) as total_diskon')
            )
            ->groupBy('tgl')
            ->orderBy('tgl', 'desc')
            ->get();

        $totalRevenue = $dailyReports->sum('omset');
        $totalTransactions = $dailyReports->sum('total_tx');
        $averageTx = $totalTransactions > 0 ? ($totalRevenue / $totalTransactions) : 0;

        return view('reports.sales', compact('dailyReports', 'startDate', 'endDate', 'totalRevenue', 'totalTransactions', 'averageTx'));
    }

    public function stock()
    {
        $products = Product::with(['kategori', 'supplier'])->where('status', 'active')->get();

        $totalStockUnits = $products->sum('stok_saat_ini');
        $totalAssetCost = $products->sum(fn($p) => $p->stok_saat_ini * (float)$p->harga_beli);
        $totalAssetPotential = $products->sum(fn($p) => $p->stok_saat_ini * (float)$p->harga_jual);
        $potentialProfit = $totalAssetPotential - $totalAssetCost;

        return view('reports.stock', compact('products', 'totalStockUnits', 'totalAssetCost', 'totalAssetPotential', 'potentialProfit'));
    }

    public function profit(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->toDateString());

        $itemProfits = TransactionDetail::query()
            ->join('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->join('products', 'products.id', '=', 'transaction_details.product_id')
            ->where('transactions.status', 'completed')
            ->whereDate('transactions.tanggal', '>=', $startDate)
            ->whereDate('transactions.tanggal', '<=', $endDate)
            ->select(
                'products.nama_barang',
                'products.satuan',
                DB::raw('SUM(transaction_details.jumlah) as total_qty'),
                DB::raw('SUM(transaction_details.subtotal) as total_penjualan'),
                DB::raw('SUM(transaction_details.jumlah * products.harga_beli) as total_hpp'),
                DB::raw('SUM(transaction_details.subtotal) - SUM(transaction_details.jumlah * products.harga_beli) as laba_kotor')
            )
            ->groupBy('products.id', 'products.nama_barang', 'products.satuan')
            ->orderByDesc('laba_kotor')
            ->get();

        $totalSales = $itemProfits->sum('total_penjualan');
        $totalHpp = $itemProfits->sum('total_hpp');
        $totalGrossProfit = $itemProfits->sum('laba_kotor');
        $profitMargin = $totalSales > 0 ? (($totalGrossProfit / $totalSales) * 100) : 0;

        return view('reports.profit', compact('itemProfits', 'startDate', 'endDate', 'totalSales', 'totalHpp', 'totalGrossProfit', 'profitMargin'));
    }
}

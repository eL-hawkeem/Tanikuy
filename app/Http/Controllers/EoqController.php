<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\EoqCalculation;
use App\Models\Product;
use App\Services\EoqService;
use Illuminate\Http\Request;

class EoqController extends Controller
{
    public function __construct(
        protected EoqService $eoqService
    ) {}

    public function index(Request $request)
    {
        $kategoriId = $request->input('kategori_id');
        $onlyReorder = $request->boolean('only_reorder');

        $query = Product::with(['kategori', 'supplier'])
            ->where('status', 'active')
            ->when($kategoriId, fn($q) => $q->where('kategori_id', $kategoriId))
            ->when($onlyReorder, fn($q) => $q->whereColumn('stok_saat_ini', '<=', 'stok_minimum'));

        $products = $query->get()->map(function (Product $product) {
            $calc = $this->eoqService->calculate($product);
            $product->eoq = (object) $calc;
            $product->estimated_reorder_cost = $calc['eoq_qty'] * (float) $product->harga_beli;
            return $product;
        });

        $categories = Category::active()->get();

        $reorderNeededProducts = $products->filter(fn($p) => $p->stok_saat_ini <= $p->stok_minimum);
        $totalEstimatedReorderCost = $reorderNeededProducts->sum('estimated_reorder_cost');

        return view('eoq.index', compact('products', 'categories', 'kategoriId', 'onlyReorder', 'reorderNeededProducts', 'totalEstimatedReorderCost'));
    }

    public function calculate(Product $product)
    {
        $result = $this->eoqService->calculate($product);
        return back()->with('success', "Kalkulasi EOQ untuk {$product->nama_barang} berhasil diperbarui (Kuantitas Optimal: {$result['eoq_qty']} {$product->satuan}).");
    }

    public function calculateAll()
    {
        $products = Product::where('status', 'active')->get();
        foreach ($products as $p) {
            $this->eoqService->calculate($p);
        }

        return back()->with('success', "Seluruh kalkulasi EOQ untuk {$products->count()} produk berhasil diperbarui secara otomatis.");
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Services\StockService;
use Exception;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function __construct(
        protected StockService $stockService
    ) {}

    public function index(Request $request)
    {
        $kategoriId = $request->input('kategori_id');
        $statusStok = $request->input('status_stok');
        $search = $request->input('search');

        $query = Product::with(['kategori', 'supplier'])
            ->where('status', 'active')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('nama_barang', 'like', "%{$search}%")
                        ->orWhere('kode_barang', 'like', "%{$search}%");
                });
            })
            ->when($kategoriId, fn($q) => $q->where('kategori_id', $kategoriId))
            ->when($statusStok === 'critical', fn($q) => $q->whereColumn('stok_saat_ini', '<=', 'stok_minimum'))
            ->when($statusStok === 'warning', fn($q) => $q->whereRaw('stok_saat_ini > stok_minimum AND stok_saat_ini <= (stok_minimum * 1.5)'))
            ->when($statusStok === 'normal', fn($q) => $q->whereRaw('stok_saat_ini > (stok_minimum * 1.5)'));

        $products = $query->orderBy('stok_saat_ini', 'asc')->paginate(15)->withQueryString();
        $categories = Category::active()->get();

        $criticalCount = Product::where('status', 'active')->whereColumn('stok_saat_ini', '<=', 'stok_minimum')->count();
        $warningCount = Product::where('status', 'active')->whereRaw('stok_saat_ini > stok_minimum AND stok_saat_ini <= (stok_minimum * 1.5)')->count();
        $normalCount = Product::where('status', 'active')->whereRaw('stok_saat_ini > (stok_minimum * 1.5)')->count();

        return view('stock.index', compact('products', 'categories', 'kategoriId', 'statusStok', 'search', 'criticalCount', 'warningCount', 'normalCount'));
    }

    public function movements(Request $request)
    {
        $tipe = $request->input('tipe');
        $productId = $request->input('product_id');

        $movements = StockMovement::with(['product.kategori'])
            ->when($tipe, fn($q) => $q->where('tipe', $tipe))
            ->when($productId, fn($q) => $q->where('product_id', $productId))
            ->latest('created_at')
            ->paginate(20)
            ->withQueryString();

        $products = Product::where('status', 'active')->orderBy('nama_barang')->get();

        return view('stock.movements', compact('movements', 'products', 'tipe', 'productId'));
    }

    public function restock(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'referensi' => ['nullable', 'string', 'max:50'],
            'keterangan' => ['nullable', 'string'],
        ]);

        try {
            $product = Product::findOrFail($validated['product_id']);
            $this->stockService->addStock(
                $product,
                $validated['jumlah'],
                $validated['referensi'] ?: 'RESTOCK-' . date('Ymd'),
                $validated['keterangan'] ?: "Penerimaan barang masuk dari supplier"
            );

            return back()->with('success', "Stok {$product->nama_barang} berhasil ditambah sebanyak {$validated['jumlah']} {$product->satuan}.");
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function adjust(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'stok_aktual' => ['required', 'integer', 'min:0'],
            'keterangan' => ['required', 'string', 'max:255'],
        ], [
            'keterangan.required' => 'Wajib mencantumkan alasan penyesuaian stok / stock opname.',
        ]);

        try {
            $product = Product::findOrFail($validated['product_id']);
            $this->stockService->adjustStock($product, $validated['stok_aktual'], $validated['keterangan']);

            return back()->with('success', "Stok {$product->nama_barang} berhasil disesuaikan menjadi {$validated['stok_aktual']} {$product->satuan}.");
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}

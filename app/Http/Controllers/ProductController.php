<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categoryId = $request->input('kategori_id');
        $status = $request->input('status');

        $query = Product::with(['kategori', 'supplier'])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('nama_barang', 'like', "%{$search}%")
                        ->orWhere('kode_barang', 'like', "%{$search}%");
                });
            })
            ->when($categoryId, fn($q) => $q->where('kategori_id', $categoryId))
            ->when($status, fn($q) => $q->where('status', $status));

        $products = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::active()->get();

        return view('products.index', compact('products', 'categories', 'search', 'categoryId', 'status'));
    }

    public function create()
    {
        $categories = Category::active()->get();
        $suppliers = Supplier::active()->get();

        // Auto generate SKU kode_barang TK-XXXX
        $lastProduct = Product::latest('id')->first();
        $nextId = $lastProduct ? ($lastProduct->id + 1) : 1;
        $autoCode = 'TK-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        return view('products.create', compact('categories', 'suppliers', 'autoCode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_barang' => ['required', 'string', 'max:20', 'unique:products,kode_barang'],
            'nama_barang' => ['required', 'string', 'max:200'],
            'kategori_id' => ['required', 'exists:categories,id'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'satuan' => ['required', 'in:kg,pcs,liter,karung,botol,pack'],
            'harga_beli' => ['required', 'numeric', 'min:0'],
            'harga_jual' => ['required', 'numeric', 'min:0', 'gte:harga_beli'],
            'stok_saat_ini' => ['required', 'integer', 'min:0'],
            'stok_minimum' => ['required', 'integer', 'min:0'],
            'biaya_pemesanan' => ['required', 'numeric', 'min:0'],
            'biaya_penyimpanan' => ['required', 'numeric', 'min:0'],
            'deskripsi' => ['nullable', 'string'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'harga_jual.gte' => 'Harga jual harus lebih besar atau sama dengan harga beli.',
            'kode_barang.unique' => 'Kode barang sudah digunakan.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus JPG, JPEG, PNG, atau WEBP.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('products', 'public');
        }

        $validated['status'] = 'active';

        $product = Product::create($validated);

        if ($product->stok_saat_ini > 0) {
            StockMovement::create([
                'product_id' => $product->id,
                'tipe' => 'in',
                'jumlah' => $product->stok_saat_ini,
                'stok_sebelum' => 0,
                'stok_sesudah' => $product->stok_saat_ini,
                'referensi' => 'INIT-STOCK',
                'keterangan' => 'Saldo awal penambahan produk baru',
            ]);
        }

        return redirect()->route('products.index')->with('success', "Produk {$product->nama_barang} berhasil ditambahkan.");
    }

    public function edit(Product $product)
    {
        $categories = Category::active()->get();
        $suppliers = Supplier::active()->get();

        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'kode_barang' => ['required', 'string', 'max:20', Rule::unique('products')->ignore($product->id)],
            'nama_barang' => ['required', 'string', 'max:200'],
            'kategori_id' => ['required', 'exists:categories,id'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'satuan' => ['required', 'in:kg,pcs,liter,karung,botol,pack'],
            'harga_beli' => ['required', 'numeric', 'min:0'],
            'harga_jual' => ['required', 'numeric', 'min:0', 'gte:harga_beli'],
            'stok_minimum' => ['required', 'integer', 'min:0'],
            'biaya_pemesanan' => ['required', 'numeric', 'min:0'],
            'biaya_penyimpanan' => ['required', 'numeric', 'min:0'],
            'deskripsi' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'hapus_gambar' => ['nullable', 'boolean'],
        ], [
            'harga_jual.gte' => 'Harga jual harus lebih besar atau sama dengan harga beli.',
            'kode_barang.unique' => 'Kode barang sudah digunakan.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus JPG, JPEG, PNG, atau WEBP.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        if ($request->hasFile('gambar')) {
            if ($product->gambar && Storage::disk('public')->exists($product->gambar)) {
                Storage::disk('public')->delete($product->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('products', 'public');
        } elseif ($request->boolean('hapus_gambar')) {
            if ($product->gambar && Storage::disk('public')->exists($product->gambar)) {
                Storage::disk('public')->delete($product->gambar);
            }
            $validated['gambar'] = null;
        }

        $product->update($validated);

        return redirect()->route('products.index')->with('success', "Produk {$product->nama_barang} berhasil diperbarui.");
    }

    public function destroy(Product $product)
    {
        if ($product->transactionDetails()->exists()) {
            return back()->with('error', "Produk {$product->nama_barang} tidak dapat dihapus karena memiliki riwayat transaksi.");
        }

        if ($product->gambar && Storage::disk('public')->exists($product->gambar)) {
            Storage::disk('public')->delete($product->gambar);
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', "Produk berhasil dihapus.");
    }
}

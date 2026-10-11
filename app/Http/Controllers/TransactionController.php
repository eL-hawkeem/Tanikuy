<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Services\TransactionService;
use Exception;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(
        protected TransactionService $transactionService
    ) {}

    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $paymentMethod = $request->input('metode_pembayaran');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Transaction::with(['details.product'])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('nomor_transaksi', 'like', "%{$search}%")
                        ->orWhere('nama_pelanggan', 'like', "%{$search}%");
                });
            })
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($paymentMethod, fn($q) => $q->where('metode_pembayaran', $paymentMethod))
            ->when($startDate, fn($q) => $q->whereDate('tanggal', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('tanggal', '<=', $endDate));

        $transactions = $query->latest('tanggal')->paginate(15)->withQueryString();

        $totalRevenue = (clone $query)->where('status', 'completed')->sum('total');

        return view('transactions.index', compact('transactions', 'search', 'status', 'paymentMethod', 'startDate', 'endDate', 'totalRevenue'));
    }

    public function create()
    {
        $products = Product::with('kategori')
            ->where('status', 'active')
            ->where('stok_saat_ini', '>', 0)
            ->orderBy('nama_barang')
            ->get();

        return view('transactions.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => ['nullable', 'string', 'max:150'],
            'metode_pembayaran' => ['required', 'in:cash,transfer,qris'],
            'diskon' => ['nullable', 'numeric', 'min:0'],
            'catatan' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.jumlah' => ['required', 'integer', 'min:1'],
        ], [
            'items.required' => 'Pilih minimal satu barang untuk melakukan transaksi.',
        ]);

        try {
            $transaction = $this->transactionService->createTransaction(
                [
                    'tanggal' => now(),
                    'nama_pelanggan' => $request->input('nama_pelanggan') ?: 'Pelanggan Umum',
                    'metode_pembayaran' => $request->input('metode_pembayaran'),
                    'diskon' => $request->input('diskon', 0),
                    'catatan' => $request->input('catatan'),
                ],
                $request->input('items')
            );

            return redirect()->route('transactions.show', $transaction)
                ->with('success', "Transaksi {$transaction->nomor_transaksi} berhasil dicatat dan stok telah diperbarui.");
        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['details.product.kategori']);
        return view('transactions.show', compact('transaction'));
    }

    public function invoice(Transaction $transaction)
    {
        $transaction->load(['details.product']);
        return view('transactions.invoice', compact('transaction'));
    }

    public function cancel(Transaction $transaction)
    {
        try {
            $this->transactionService->cancelTransaction($transaction);
            return back()->with('success', "Transaksi {$transaction->nomor_transaksi} berhasil dibatalkan dan stok telah dikembalikan.");
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}

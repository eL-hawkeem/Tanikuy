<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use App\Services\EoqService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin & Kasir Users
        $admin = User::updateOrCreate(
            ['email' => 'admin@tanikuy.com'],
            [
                'name' => 'Mas eL (Admin Tanikuy)',
                'role' => 'admin',
                'password' => 'password',
                'email_verified_at' => now(),
            ]
        );

        $kasir = User::updateOrCreate(
            ['email' => 'kasir@tanikuy.com'],
            [
                'name' => 'Siti Aminah (Kasir Tanikuy)',
                'role' => 'kasir',
                'password' => 'password',
                'email_verified_at' => now(),
            ]
        );

        // 2. Kategori, Supplier, Produk
        $this->call([
            CategorySeeder::class,
            SupplierSeeder::class,
            ProductSeeder::class,
        ]);

        // 3. Sample Riwayat Transaksi 30 hari terakhir untuk tren chart & algoritma EOQ
        if (Transaction::count() === 0) {
            $products = Product::all();
            $customers = ['Pak Slamet', 'Kelompok Tani Makmur', 'Ibu Nurul', 'Pak Joko Santoso', 'Gapoktan Subur Lestari', 'Pak Herman'];
            $methods = ['cash', 'transfer', 'qris'];

            for ($i = 25; $i >= 1; $i--) {
                $txDate = Carbon::now()->subDays($i)->setHour(rand(8, 16))->setMinute(rand(0, 59));
                $inv = 'INV-' . $txDate->format('Ymd') . '-' . str_pad(rand(10, 99), 4, '0', STR_PAD_LEFT);
                
                $itemsCount = rand(1, 3);
                $selectedProducts = $products->random($itemsCount);

                $subtotal = 0;
                $detailsData = [];

                foreach ($selectedProducts as $prod) {
                    $qty = rand(1, 5);
                    $lineTotal = $qty * (float) $prod->harga_jual;
                    $subtotal += $lineTotal;

                    $detailsData[] = [
                        'product_id' => $prod->id,
                        'jumlah' => $qty,
                        'harga_satuan' => $prod->harga_jual,
                        'subtotal' => $lineTotal,
                    ];
                }

                $tx = Transaction::create([
                    'nomor_transaksi' => $inv,
                    'tanggal' => $txDate,
                    'nama_pelanggan' => $customers[array_rand($customers)],
                    'metode_pembayaran' => $methods[array_rand($methods)],
                    'subtotal' => $subtotal,
                    'diskon' => 0,
                    'total' => $subtotal,
                    'status' => 'completed',
                    'catatan' => 'Transaksi penjualan pertanian',
                    'created_at' => $txDate,
                    'updated_at' => $txDate,
                ]);

                foreach ($detailsData as $dt) {
                    TransactionDetail::create(array_merge($dt, ['transaction_id' => $tx->id, 'created_at' => $txDate, 'updated_at' => $txDate]));
                }
            }
        }

        // 4. Hitung EOQ awal untuk semua produk
        $eoqService = app(EoqService::class);
        foreach (Product::all() as $prod) {
            $eoqService->calculate($prod);
        }
    }
}

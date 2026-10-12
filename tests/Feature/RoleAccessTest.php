<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use DatabaseTransactions;

    protected User $admin;
    protected User $kasir;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'role' => 'admin',
            'password' => 'password',
        ]);

        $this->kasir = User::create([
            'name' => 'Kasir Test',
            'email' => 'kasir@test.com',
            'role' => 'kasir',
            'password' => 'password',
        ]);
    }

    public function test_admin_can_access_all_modules(): void
    {
        $response = $this->actingAs($this->admin)->get(route('dashboard'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->admin)->get(route('users.index'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->admin)->get(route('eoq.index'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->admin)->get(route('reports.sales'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->admin)->get(route('suppliers.index'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->admin)->get(route('products.create'));
        $response->assertStatus(200);
    }

    public function test_kasir_can_access_pos_and_view_catalog(): void
    {
        $response = $this->actingAs($this->kasir)->get(route('dashboard'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->kasir)->get(route('transactions.create'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->kasir)->get(route('transactions.index'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->kasir)->get(route('products.index'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->kasir)->get(route('stock.index'));
        $response->assertStatus(200);
    }

    public function test_kasir_is_forbidden_from_admin_only_modules(): void
    {
        // Users Management
        $response = $this->actingAs($this->kasir)->get(route('users.index'));
        $response->assertStatus(403);

        // EOQ
        $response = $this->actingAs($this->kasir)->get(route('eoq.index'));
        $response->assertStatus(403);

        // Reports
        $response = $this->actingAs($this->kasir)->get(route('reports.sales'));
        $response->assertStatus(403);

        $response = $this->actingAs($this->kasir)->get(route('reports.profit'));
        $response->assertStatus(403);

        // Suppliers
        $response = $this->actingAs($this->kasir)->get(route('suppliers.index'));
        $response->assertStatus(403);

        // Product creation
        $response = $this->actingAs($this->kasir)->get(route('products.create'));
        $response->assertStatus(403);

        // Stock movements & restock
        $response = $this->actingAs($this->kasir)->get(route('stock.movements'));
        $response->assertStatus(403);
    }

    public function test_admin_can_create_new_kasir_user(): void
    {
        $response = $this->actingAs($this->admin)->post(route('users.store'), [
            'name' => 'Budi Kasir Baru',
            'email' => 'budi.kasir@tanikuy.com',
            'role' => 'kasir',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'budi.kasir@tanikuy.com',
            'role' => 'kasir',
        ]);
    }

    public function test_admin_cannot_delete_self(): void
    {
        $response = $this->actingAs($this->admin)->delete(route('users.destroy', $this->admin));
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }

    public function test_transaction_stores_cashier_user_id(): void
    {
        $cat = Category::create([
            'nama' => 'Pupuk Organik',
            'slug' => 'pupuk-organik',
        ]);

        $prod = Product::create([
            'kode_barang' => 'TK-TEST-01',
            'nama_barang' => 'Pupuk Kandang Test',
            'kategori_id' => $cat->id,
            'satuan' => 'karung',
            'harga_beli' => 20000,
            'harga_jual' => 30000,
            'stok_saat_ini' => 50,
            'stok_minimum' => 5,
            'biaya_pemesanan' => 10000,
            'biaya_penyimpanan' => 1000,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->kasir)->post(route('transactions.store'), [
            'nama_pelanggan' => 'Petani Joko',
            'metode_pembayaran' => 'cash',
            'items' => [
                [
                    'product_id' => $prod->id,
                    'jumlah' => 2,
                ],
            ],
        ]);

        $response->assertRedirect();

        $transaction = Transaction::latest('id')->first();
        $this->assertNotNull($transaction);
        $this->assertEquals($this->kasir->id, $transaction->user_id);
    }

    public function test_kasir_can_login_with_valid_credentials(): void
    {
        $response = $this->post(route('login.post'), [
            'email' => 'kasir@test.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($this->kasir);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductPhotoUploadTest extends TestCase
{
    use DatabaseTransactions;

    protected User $admin;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Product Test',
            'email' => 'admin.product@test.com',
            'role' => 'admin',
            'password' => 'password',
        ]);

        $this->category = Category::firstOrCreate(
            ['slug' => 'kategori-uji'],
            ['nama' => 'Kategori Uji', 'deskripsi' => 'Deskripsi Kategori', 'is_active' => true]
        );
    }

    public function test_admin_can_upload_product_with_photo(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('pupuk-organik.jpg', 600, 600);

        $response = $this->actingAs($this->admin)->post(route('products.store'), [
            'kode_barang' => 'TK-TEST-01',
            'nama_barang' => 'Pupuk Organik Super',
            'kategori_id' => $this->category->id,
            'satuan' => 'kg',
            'harga_beli' => 15000,
            'harga_jual' => 20000,
            'stok_saat_ini' => 50,
            'stok_minimum' => 10,
            'biaya_pemesanan' => 25000,
            'biaya_penyimpanan' => 1500,
            'deskripsi' => 'Pupuk organik hayati kualitas super.',
            'gambar' => $file,
        ]);

        $response->assertRedirect(route('products.index'));

        $product = Product::where('kode_barang', 'TK-TEST-01')->first();
        $this->assertNotNull($product);
        $this->assertNotNull($product->gambar);
        $this->assertNotNull($product->gambar_url);
        Storage::disk('public')->assertExists($product->gambar);
    }

    public function test_upload_invalid_file_type_fails_validation(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('catatan.pdf', 100);

        $response = $this->actingAs($this->admin)->post(route('products.store'), [
            'kode_barang' => 'TK-TEST-02',
            'nama_barang' => 'Produk Invalid File',
            'kategori_id' => $this->category->id,
            'satuan' => 'kg',
            'harga_beli' => 10000,
            'harga_jual' => 15000,
            'stok_saat_ini' => 10,
            'stok_minimum' => 5,
            'biaya_pemesanan' => 10000,
            'biaya_penyimpanan' => 1000,
            'gambar' => $file,
        ]);

        $response->assertSessionHasErrors('gambar');
    }

    public function test_upload_oversized_file_fails_validation(): void
    {
        Storage::fake('public');

        // File lebih besar dari 2048 KB (e.g. 3000 KB)
        $file = UploadedFile::fake()->image('huge-image.jpg')->size(3000);

        $response = $this->actingAs($this->admin)->post(route('products.store'), [
            'kode_barang' => 'TK-TEST-03',
            'nama_barang' => 'Produk File Raksasa',
            'kategori_id' => $this->category->id,
            'satuan' => 'kg',
            'harga_beli' => 10000,
            'harga_jual' => 15000,
            'stok_saat_ini' => 10,
            'stok_minimum' => 5,
            'biaya_pemesanan' => 10000,
            'biaya_penyimpanan' => 1000,
            'gambar' => $file,
        ]);

        $response->assertSessionHasErrors('gambar');
    }

    public function test_admin_can_update_product_photo(): void
    {
        Storage::fake('public');

        $initialFile = UploadedFile::fake()->image('old-photo.jpg');
        $oldPath = $initialFile->store('products', 'public');

        $product = Product::create([
            'kode_barang' => 'TK-TEST-04',
            'nama_barang' => 'Barang Update Foto',
            'kategori_id' => $this->category->id,
            'satuan' => 'pcs',
            'harga_beli' => 10000,
            'harga_jual' => 15000,
            'stok_saat_ini' => 20,
            'stok_minimum' => 5,
            'biaya_pemesanan' => 10000,
            'biaya_penyimpanan' => 1000,
            'status' => 'active',
            'gambar' => $oldPath,
        ]);

        $newFile = UploadedFile::fake()->image('new-photo.jpg');

        $response = $this->actingAs($this->admin)->put(route('products.update', $product), [
            'kode_barang' => 'TK-TEST-04',
            'nama_barang' => 'Barang Update Foto Updated',
            'kategori_id' => $this->category->id,
            'satuan' => 'pcs',
            'harga_beli' => 10000,
            'harga_jual' => 15000,
            'stok_minimum' => 5,
            'biaya_pemesanan' => 10000,
            'biaya_penyimpanan' => 1000,
            'status' => 'active',
            'gambar' => $newFile,
        ]);

        $response->assertRedirect(route('products.index'));

        $product->refresh();
        $this->assertNotEquals($oldPath, $product->gambar);
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($product->gambar);
    }

    public function test_admin_can_remove_product_photo(): void
    {
        Storage::fake('public');

        $initialFile = UploadedFile::fake()->image('remove-photo.jpg');
        $oldPath = $initialFile->store('products', 'public');

        $product = Product::create([
            'kode_barang' => 'TK-TEST-05',
            'nama_barang' => 'Barang Hapus Foto',
            'kategori_id' => $this->category->id,
            'satuan' => 'pcs',
            'harga_beli' => 10000,
            'harga_jual' => 15000,
            'stok_saat_ini' => 20,
            'stok_minimum' => 5,
            'biaya_pemesanan' => 10000,
            'biaya_penyimpanan' => 1000,
            'status' => 'active',
            'gambar' => $oldPath,
        ]);

        $response = $this->actingAs($this->admin)->put(route('products.update', $product), [
            'kode_barang' => 'TK-TEST-05',
            'nama_barang' => 'Barang Hapus Foto Updated',
            'kategori_id' => $this->category->id,
            'satuan' => 'pcs',
            'harga_beli' => 10000,
            'harga_jual' => 15000,
            'stok_minimum' => 5,
            'biaya_pemesanan' => 10000,
            'biaya_penyimpanan' => 1000,
            'status' => 'active',
            'hapus_gambar' => 1,
        ]);

        $response->assertRedirect(route('products.index'));

        $product->refresh();
        $this->assertNull($product->gambar);
        Storage::disk('public')->assertMissing($oldPath);
    }
}

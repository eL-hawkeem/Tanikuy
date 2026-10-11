<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang', 20)->unique();
            $table->string('nama_barang', 200);
            $table->foreignId('kategori_id')->constrained('categories')->restrictOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->enum('satuan', ['kg', 'pcs', 'liter', 'karung', 'botol', 'pack'])->default('pcs');
            $table->decimal('harga_beli', 15, 2)->default(0);
            $table->decimal('harga_jual', 15, 2)->default(0);
            $table->integer('stok_saat_ini')->default(0);
            $table->integer('stok_minimum')->default(0);
            $table->decimal('biaya_pemesanan', 15, 2)->default(0);
            $table->decimal('biaya_penyimpanan', 15, 2)->default(0);
            $table->text('deskripsi')->nullable();
            $table->string('gambar', 255)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->index('kategori_id');
            $table->index('supplier_id');
            $table->index('status');
            $table->index('nama_barang');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('kode_supplier', 20)->unique();
            $table->string('nama', 150);
            $table->string('perusahaan', 200)->nullable();
            $table->string('telepon', 20);
            $table->string('email', 100)->nullable();
            $table->text('alamat');
            $table->string('kota', 100);
            $table->text('catatan')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->index('status');
            $table->index('kota');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eoq_calculations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->decimal('annual_demand', 15, 2);
            $table->decimal('ordering_cost', 15, 2);
            $table->decimal('holding_cost', 15, 2);
            $table->decimal('eoq_qty', 15, 2);
            $table->decimal('order_frequency', 10, 2);
            $table->decimal('total_optimal_cost', 15, 2);
            $table->decimal('daily_demand', 10, 2);
            $table->text('rekomendasi')->nullable();
            $table->date('periode_mulai');
            $table->date('periode_akhir');
            $table->timestamps();

            $table->index('product_id');
            $table->index('periode_mulai');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eoq_calculations');
    }
};

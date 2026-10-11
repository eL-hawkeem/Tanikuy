<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EoqCalculation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'annual_demand',
        'ordering_cost',
        'holding_cost',
        'eoq_qty',
        'order_frequency',
        'total_optimal_cost',
        'daily_demand',
        'rekomendasi',
        'periode_mulai',
        'periode_akhir',
    ];

    protected $casts = [
        'annual_demand' => 'decimal:2',
        'ordering_cost' => 'decimal:2',
        'holding_cost' => 'decimal:2',
        'eoq_qty' => 'decimal:2',
        'order_frequency' => 'decimal:2',
        'total_optimal_cost' => 'decimal:2',
        'daily_demand' => 'decimal:2',
        'periode_mulai' => 'date',
        'periode_akhir' => 'date',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}

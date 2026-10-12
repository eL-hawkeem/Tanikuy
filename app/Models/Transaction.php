<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nomor_transaksi',
        'tanggal',
        'nama_pelanggan',
        'metode_pembayaran',
        'subtotal',
        'diskon',
        'total',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'subtotal' => 'decimal:2',
        'diskon' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}

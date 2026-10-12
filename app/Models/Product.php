<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori_id',
        'supplier_id',
        'satuan',
        'harga_beli',
        'harga_jual',
        'stok_saat_ini',
        'stok_minimum',
        'biaya_pemesanan',
        'biaya_penyimpanan',
        'deskripsi',
        'gambar',
        'status',
    ];

    protected $casts = [
        'harga_beli' => 'decimal:2',
        'harga_jual' => 'decimal:2',
        'stok_saat_ini' => 'integer',
        'stok_minimum' => 'integer',
        'biaya_pemesanan' => 'decimal:2',
        'biaya_penyimpanan' => 'decimal:2',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'kategori_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function transactionDetails(): HasMany
    {
        return $this->hasMany(TransactionDetail::class, 'product_id');
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class, 'product_id')->latest();
    }

    public function eoqCalculations(): HasMany
    {
        return $this->hasMany(EoqCalculation::class, 'product_id')->latest();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stok_saat_ini', '<=', 'stok_minimum');
    }

    public function getGambarUrlAttribute(): ?string
    {
        if (!$this->gambar) {
            return null;
        }

        if (str_starts_with($this->gambar, 'http://') || str_starts_with($this->gambar, 'https://')) {
            return $this->gambar;
        }

        return asset('storage/' . $this->gambar);
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->stok_saat_ini <= $this->stok_minimum) {
            return 'critical';
        }
        if ($this->stok_saat_ini <= ($this->stok_minimum * 1.5)) {
            return 'warning';
        }
        return 'normal';
    }
}

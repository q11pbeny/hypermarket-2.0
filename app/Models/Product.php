<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'code',
        'barcode',
        'description',
        'price',
        'cost_price',
        'stock_quantity',
        'min_stock_level',
        'unit',
        'expiry_date',
        'brand',
        'images',
        'category_id',
        'supplier_id',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'is_active' => 'boolean',
        'expiry_date' => 'date',
        'images' => 'array'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function inventoryLogs(): HasMany
    {
        return $this->hasMany(InventoryLog::class);
    }

    // محاسبه سود
    public function getProfitAttribute()
    {
        return $this->price - $this->cost_price;
    }

    // بررسی موجودی کم
    public function getIsLowStockAttribute()
    {
        return $this->stock_quantity <= $this->min_stock_level;
    }

    // بررسی انقضای نزدیک
    public function getIsExpiringSoonAttribute()
    {
        if (!$this->expiry_date) return false;
        return $this->expiry_date->diffInDays(now()) <= 30;
    }
}

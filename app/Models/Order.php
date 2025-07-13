<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'customer_id',
        'total_amount',
        'status',
        'payment_method',
        'discount_amount',
        'tax_amount',
        'shipping_cost',
        'shipping_address',
        'delivery_date',
        'notes'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'delivery_date' => 'date'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    // محاسبه مبلغ نهایی
    public function getFinalAmountAttribute()
    {
        return $this->total_amount - $this->discount_amount + $this->tax_amount + $this->shipping_cost;
    }

    // محاسبه درصد تخفیف
    public function getDiscountPercentageAttribute()
    {
        if ($this->total_amount == 0) return 0;
        return ($this->discount_amount / $this->total_amount) * 100;
    }
}
